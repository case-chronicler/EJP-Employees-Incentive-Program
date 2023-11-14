<?php

namespace App\Http\Controllers;

use App\Models\Withdrawals;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WithdrawalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_id = auth()->user()->user_id;
        $withdrawals = [];

        $employee = User::find($user_id)->employee()->first();

        $employee_id = $employee->employee_id;

        $isEmployeeAnAttorney = EmployeeController::getIsEmployeeAnAttorney();

        $withdrawals = DB::table('withdrawal_requests')
            ->join('employees', 'withdrawal_requests.employee_id', '=', 'employees.employee_id')
            ->join('users', 'employees.user_id', '=', 'users.user_id')
            ->leftJoin('withdrawals', 'withdrawal_requests.withdrawal_request_link_id', '=', 'withdrawals.withdrawal_request_link_id')
            ->orderBy('withdrawal_requests.updated_at', 'desc')
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
            );

        if($isEmployeeAnAttorney){
            $withdrawals = $withdrawals->get();
        }else{
            $withdrawals = $withdrawals->where('withdrawal_requests.employee_id', '=', $employee_id)->get();
        }

        $withdrawals = $withdrawals ?? [];

        return Inertia::render('Transaction/withdrawals', [    
            'allWithdrawals' => $withdrawals,
        ]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdrawals  $withdrawals
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdrawals  $withdrawals
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdrawals  $withdrawals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdrawals  $withdrawals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdrawals $withdrawals)
    {
        //
    }
}
