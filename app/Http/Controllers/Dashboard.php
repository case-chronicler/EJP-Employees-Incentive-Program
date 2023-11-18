<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Incentive_gift;
use App\Models\Withdrawal_requests;

use Exception;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class Dashboard extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isLoggedInUserAnAttorney = EmployeeController::getIsEmployeeAnAttorney();
        $user_id = auth()->user()->user_id;
    
        $employee = User::find($user_id)->employee()->first();

        // return json_encode($employee);

        $incentive_gift = DB::table('incentives_gift')
                ->join('incentives', 'incentives_gift.incentive_id', '=', 'incentives.incentive_id')
                ->join('incentives_gift_transfer', 'incentives_gift.incentives_gift_type_id', '=', 'incentives_gift_transfer.incentives_gift_type_id')
                ->select([
                    'incentives_gift.incentives_gift_id AS incentives_gift_incentives_gift_id',
                    'incentives_gift.incentives_gift_type_id AS incentives_gift_incentives_gift_type_id',
                    'incentives_gift.incentives_gift_type AS incentives_gift_incentives_gift_type',
                    'incentives_gift.amount AS incentives_gift_total_amount',
                    'incentives_gift.gift_quantity AS incentives_gift_gift_quantity',
                    'incentives_gift.note AS incentives_gift_note',
                    'incentives_gift.created_at AS incentives_gift_created_at',
                    
                    'incentives.created_at AS incentives_created_at',
                    'incentives.name AS incentives_name',
                    'incentives.icon_name AS incentives_icon_name'                    
                    ]
                );

        if(!$isLoggedInUserAnAttorney){
            $incentive_gift = $incentive_gift->where('incentives_gift_transfer.to_employee_id', '=', $employee->employee_id);
        }

        $incentive_gift = $incentive_gift->latest('incentives_gift.created_at')
                ->limit(4)
                ->get();
        
        $withdrawal_request = DB::table('withdrawal_requests')
                ->leftJoin('employees', 'withdrawal_requests.employee_id', '=', 'employees.employee_id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.user_id')
                ->select([
                    'users.user_id AS user_id',
                    'users.name AS user_fullname',
                    'users.email AS user_email',
                    'users.is_employee AS is_user_employed',
                    'employees.employee_id AS employee_id',
                    'employees.balance AS employee_bal',   
                    'employees.created_at AS employee_created_at', 
                    
                    
                    'withdrawal_requests.created_at AS withdrawal_requests_created_at', 
                    'withdrawal_requests.amount AS withdrawal_requests_amount', 
                    'withdrawal_requests.status AS withdrawal_requests_status', 
                    'withdrawal_requests.withdrawal_request_link_id AS withdrawal_requests_withdrawal_request_link_id', 

                    ]
                );
                

        if(!$isLoggedInUserAnAttorney){
            $withdrawal_request = $withdrawal_request->where('withdrawal_requests.employee_id', '=', $employee->employee_id);
        }

        $withdrawal_request = $withdrawal_request->latest('withdrawal_requests.created_at')
                ->limit(6)
                ->get();

       return Inertia::render('Dashboard', [    
            'incentive_gift' => $incentive_gift,
            'withdrawal_request' => $withdrawal_request,       
            'isLoggedInUserAnAttorney' => $isLoggedInUserAnAttorney
        ]);
    }
}
