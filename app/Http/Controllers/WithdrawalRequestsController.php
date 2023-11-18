<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal_requests;
use App\Events\WithdrawalRequestUpdated;
use App\Models\User;
use App\Models\Employee;


use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class WithdrawalRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validated = $request->validate([
                'amount' => 'required|numeric',
                'request_sent_by' => 'required',
            ]);
                
            $hashSeed = "abcdefghijklmnopqrstuvwxyz" . rand(1,1000000);
                
            $validated['withdrawal_request_link_id'] = hash('sha256', $hashSeed);
        
            $user_id = $validated['request_sent_by'];
            
            $employeeModel = User::find($user_id)->employee();
    
            $employee = $employeeModel->first();
    
            $employee_balance = (float) $employee->balance;
            $employee_id = (float) $employee->employee_id;
            $amount = (float) $validated['amount'];
    
            if($employee_balance < $amount){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => "insufficent funds"
                ]);
            }
    
            $amount = number_format($amount, 2, '.', '');
    
            DB::beginTransaction();
                
            Employee::find($employee_id)->withdrawal_requests()->create([
                'withdrawal_request_link_id' => $validated['withdrawal_request_link_id'],
                'amount' => $amount,
                'status' => 'pending',
            ]);
    
            $employeeModel->decrement('balance', $amount);
            
            DB::commit(); 
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'general' => 'action not successful'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdrawal_requests  $withdrawal_requests
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawal_requests $withdrawal_requests)
    {
        $withdrawal_request_id = request()->withdrawal_request_id;

        $withdrawal_request_data = $this->getWithdrawalRequestByID($withdrawal_request_id);

        return Inertia::render('Transaction/withdrawal_request', [    
            'withdrawal_request_data' => $withdrawal_request_data
        ]);
    }

    private function getWithdrawalRequestByID($withdrawal_request_id){
        $withdrawal_request_data = DB::table('withdrawal_requests')
            ->join('employees', 'withdrawal_requests.employee_id', '=', 'employees.employee_id')
            ->join('users', 'withdrawal_requests.employee_id', '=', 'users.user_id')
            ->leftJoin('withdrawals', 'withdrawal_requests.withdrawal_request_link_id', '=', 'withdrawals.withdrawal_request_link_id')
            ->select([
                'users.user_id AS user_id',
                'users.name AS user_fullname',
                'users.email AS user_email',
                'employees.employee_id AS employee_id',
                'employees.balance AS employee_bal',   
                'employees.created_at AS employee_created_at',             
                'withdrawal_requests.created_at AS withdrawal_requested_at',             
                'withdrawal_requests.withdrawal_request_link_id AS withdrawal_request_link_id',             
                'withdrawal_requests.amount AS withdrawal_request_amount',             
                'withdrawal_requests.status AS withdrawal_request_status',             
                'withdrawals.amount AS successfull_withdrawal_amount',             
                ]
            )
            ->where('withdrawal_requests.withdrawal_request_link_id', '=', $withdrawal_request_id)->first();

        return $withdrawal_request_data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdrawal_requests  $withdrawal_requests
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdrawal_requests $withdrawal_requests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdrawal_requests  $withdrawal_requests
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdrawal_requests $withdrawal_requests)
    {
        try {
            //code...
            $withdrawal_request_id = request()->withdrawal_request_id;
    
            $validated = $request->validate([
                'action' => 'required|string|in:approve,reject',
            ]);
    
            $withdrawal_request = Withdrawal_requests::where('withdrawal_request_link_id', $withdrawal_request_id)->first();
    
            $employee_id = $withdrawal_request->employee_id;
    
            $employee = Employee::where('employee_id', $employee_id)->first();
    
            switch ($validated['action']) {
                case 'approve':               
                   $this->approveWithdrawalRequest($employee, $withdrawal_request);
                    break;
    
                case 'reject':
                    $this->rejectWithdrawalRequest($employee, $withdrawal_request);
                    break;
                
            }
            
            // return json_encode($withdrawal_request);
    
            $withdrawal_request_data = $this->getWithdrawalRequestByID($withdrawal_request_id);
    
            return Inertia::render('Transaction/withdrawal_request', [    
                'withdrawal_request_data' => $withdrawal_request_data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Inertia::render('Transaction/withdrawal_request', [    
                'withdrawal_request_data' => $withdrawal_request_data,
                'error' => 'action not successful'
            ]);
        }
    }

    private function approveWithdrawalRequest(Employee $employee, Withdrawal_requests $withdrawal_request) {
        try {            
            $amount = number_format($withdrawal_request->amount, 2, '.', '');
            $employee_id = $withdrawal_request->employee_id;
            $withdrawal_request_id = $withdrawal_request->withdrawal_request_link_id;

            DB::beginTransaction();
                
            Employee::find($employee_id)
                ->withdrawal_requests()
                ->where(
                    'withdrawal_request_link_id', $withdrawal_request_id)
                ->update([
                    'status' => 'success',
                ]);
            
            $withdrawal_request = Withdrawal_requests::where('withdrawal_request_link_id', $withdrawal_request_id)->first();
            WithdrawalRequestUpdated::dispatch($withdrawal_request);
            
            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('action not successful');
        }
    }

    private function rejectWithdrawalRequest(Employee $employee, Withdrawal_requests $withdrawal_request) {
        try {            
            $amount = number_format($withdrawal_request->amount, 2, '.', '');
            $employee_id = $withdrawal_request->employee_id;
            $withdrawal_request_id = $withdrawal_request->withdrawal_request_link_id;

            DB::beginTransaction();
                
            Employee::find($employee_id)
                ->withdrawal_requests()
                ->where(
                    'withdrawal_request_link_id', $withdrawal_request_id)
                ->update([
                    'status' => 'failed',
                ]);
    
            $employee->increment('balance', $amount);
            
            $withdrawal_request = Withdrawal_requests::where('withdrawal_request_link_id', $withdrawal_request_id)->first();
            WithdrawalRequestUpdated::dispatch($withdrawal_request);
            
            DB::commit(); 

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('action not successful');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdrawal_requests  $withdrawal_requests
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdrawal_requests $withdrawal_requests)
    {
        //
    }
}
