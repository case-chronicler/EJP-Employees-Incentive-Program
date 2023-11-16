<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    static function getLoggedinEmployeeBalance(){
        try {
            if (!auth()->user()) {
                return 0.00;
            }
            $user_id = auth()->user()->user_id;
    
            $employee = User::find($user_id)->employee()->first();
    
            return $employee->balance ?? 0.00;
        } catch (\Throwable $th) {
            return 0.00;            
        }
    }

    static function getIsEmployeeAnAttorney() {
        try {
            if (!auth()->user()) {
                return false;
            }
            
            $auth_user = auth()->user();
    
            $isEmployeeAnAttorney = DB::table('users')
                ->leftJoin('employees', 'users.user_id', '=', 'employees.user_id')
                ->leftJoin('roles', 'employees.employee_id', '=', 'roles.employee_id')
                ->where('users.user_id', '=', $auth_user->user_id)
                ->where("position_id", '=', 1)
                ->select([
                    'users.user_id AS user_id',
                    'users.name AS user_fullname',
                    'users.email AS user_email',
                    'users.is_employee AS is_user_employed',
                    'employees.employee_id AS employee_id',
                    'employees.balance AS employee_bal',   
                    'employees.created_at AS employee_created_at',             
                    'roles.id AS role_id',
                    ]
                )->first();
    
            return ($isEmployeeAnAttorney) ? true : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function fetchEmployeeUserData($user_id = '', $excludedUser = ''){
        $processed_users_and_employees = [];
        $users_and_employees = DB::table('users')
            ->leftJoin('employees', 'users.user_id', '=', 'employees.user_id')
            ->leftJoin('roles', 'employees.employee_id', '=', 'roles.employee_id')
            ->leftJoin('positions', 'roles.position_id', '=', 'positions.position_id')
            ->select([
                'users.user_id AS user_id',
                'users.name AS user_fullname',
                'users.email AS user_email',
                'users.is_employee AS is_user_employed',
                'employees.employee_id AS employee_id',
                'employees.balance AS employee_bal',   
                'employees.created_at AS employee_created_at',             
                'roles.id AS role_id',
                'positions.position_id AS position_id',
                'positions.position_name AS position_name'
                ]
            );

        if($user_id !== ''){
            $users_and_employees = $users_and_employees
                ->where('users.user_id', '==', $user_id);
        }

        if($excludedUser !== ''){
            $users_and_employees = $users_and_employees
                ->where('users.user_id', '!=', $excludedUser);
        }
        
        $users_and_employees = $users_and_employees
            ->orderBy('employee_created_at', 'desc')
            ->get(); 

            
        foreach ($users_and_employees as $current_user_and_employee) {

            if(!isset($processed_users_and_employees['00'.$current_user_and_employee->user_id])){
                $processed_users_and_employees['00'.$current_user_and_employee->user_id] = [
                    'user_id' => $current_user_and_employee->user_id,
                    'employee_id' => $current_user_and_employee->employee_id,
                    'user_fullname' => $current_user_and_employee->user_fullname,
                    'user_email' => $current_user_and_employee->user_email,
                    'is_user_employed' => $current_user_and_employee->is_user_employed,
                    'positions' => ($current_user_and_employee->position_name) ? [$current_user_and_employee->position_name] : [],
                ];
            }else{
                $newPosition = ($current_user_and_employee->position_name) ? $current_user_and_employee->position_name : false;

                if($newPosition){
                    array_push($processed_users_and_employees['00'.$current_user_and_employee->user_id]['positions'], $newPosition);
                }
            }

        }

        return $processed_users_and_employees;

    }

    public function isEmployeeAnAttorney(){
        return self::getIsEmployeeAnAttorney();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $excluded_user_id = '';

        if (auth()->user()) {
            $excluded_user_id = auth()->user()->user_id ?? '';
        }
    

        $processed_users_and_employees = $this->fetchEmployeeUserData('', $excluded_user_id);
        $allPositions = DB::table('positions')->where('position_name', '!=', 'Attorney')
            ->get();
        $isEmployeeAnAttorney = $this->isEmployeeAnAttorney();
        $allGifts = DB::table('incentives')->get();

        return Inertia::render('Employee/index', [    
            'users_and_employees' => $processed_users_and_employees,
            // 'users_and_employees' => $users_and_employees,
            'allPositions' => $allPositions,
            'allGifts' => $allGifts,
            'isEmployeeAnAttorney' => $isEmployeeAnAttorney
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
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
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            'position_id' => 'required|numeric',
        ]);

        $user_id = $validated['user_id'];
        $position_id = $validated['position_id'];
        
        $allEmployees = Employee::with('user')->where('user_id', $user_id)->get();        
        $user = User::find($user_id);
        $position = Position::find($position_id);
        $newRole = null;
        $processed_users_and_employees = null;

        try {
            //code...
            DB::beginTransaction();
            if(! count($allEmployees)){
     
                $newEmployee = $user->employee()->create([
                    'balance' => 0.00,
                ]);
    
                $newRole = $this->addEmployeeRole($newEmployee, $position);
    
            }else{
                foreach ($allEmployees as $current_employee_in_loop) {
                    $employee = Employee::find($current_employee_in_loop->employee_id);;
                    $newRole = $this->addEmployeeRole($employee, $position);
                }
            }   

            $processed_users_and_employees = $this->fetchEmployeeUserData();
            DB::commit(); 
        } catch (Exception $th) {
            DB::rollBack();
        }


        return response()->json([
            'processed_users_and_employees' => $processed_users_and_employees
        ]);
    }

    public function addEmployeeRole(Employee $employee, Position $position){
        
        $newRole = Role::updateOrCreate(
            [
                'employee_id' => $employee->employee_id,
                'position_id' => $position->position_id
            ],
            [
                'start_date' => now(),
                'is_active' => true,
                'employee_id' => $employee->employee_id,
                'position_id' => $position->position_id
            ]
        );

        return $newRole;        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
