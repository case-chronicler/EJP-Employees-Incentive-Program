<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Response;

class EmployeePageDataController extends Controller
{
    //
    public function incentives(){
        try {
            //code...
            $employee_id = request()->employee_id;
    
            $incentiveData = DB::table('incentives_gift')
                    ->join('incentives', 'incentives_gift.incentive_id', '=', 'incentives.incentive_id')
                    ->join('incentives_gift_transfer', 'incentives_gift.incentives_gift_type_id', '=', 'incentives_gift_transfer.incentives_gift_type_id')
                    ->join('employees AS employees_from', 'incentives_gift_transfer.from_employee_id', '=', 'employees_from.employee_id')
                    ->join('users AS users_from', 'employees_from.user_id', '=', 'users_from.user_id')
                    ->join('employees AS employees_to', 'incentives_gift_transfer.to_employee_id', '=', 'employees_to.employee_id')
                    ->join('users AS users_to', 'employees_to.user_id', '=', 'users_to.user_id')
                    ->orderBy('incentives_gift.created_at', 'desc')
                    ->select([
                        'incentives_gift.incentives_gift_id AS incentives_gift_incentives_gift_id',
                        'incentives_gift.incentives_gift_type_id AS incentives_gift_incentives_gift_type_id',
                        'incentives_gift.incentives_gift_type AS incentives_gift_incentives_gift_type',
                        'incentives_gift.amount AS incentives_gift_total_amount',
                        'incentives_gift.gift_quantity AS incentives_gift_gift_quantity',
                        'incentives_gift.note AS incentives_gift_note',
                        'incentives_gift.created_at AS incentives_gift_created_at',
    
                        'incentives_gift_transfer.amount AS incentives_gift_transfer_amount_per_employee',
                        'incentives_gift_transfer.incentives_gift_transfer_id AS incentives_gift_transfer_incentives_gift_transfer_id',
                        
                        'users_from.user_id AS user_from_id',
                        'users_from.name AS user_from_fullname',
                        'users_from.email AS user_from_email',
    
                        'users_to.user_id AS user_to_id',
                        'users_to.name AS user_to_fullname',
                        'users_to.email AS user_to_email',
    
                        'incentives.name AS incentives_name',
                        'incentives.icon_name AS incentives_icon_name'
                        
                        ]
                    )->where('incentives_gift_transfer.to_employee_id', '=', $employee_id)
                    ->latest('incentives_gift.created_at')                
                    ->paginate(6);
    
            $resData = [
                "data" => $incentiveData->items(),
                'links' => [
                    'prev' => $incentiveData->previousPageUrl() ?? false,
                    'next' => $incentiveData->nextPageUrl() ?? false
                ],
            ];
    
            
            return response()->json($resData);
        } catch (\Throwable $th) {
            $resData = [
                "data" => [],
                'links' => [
                    'prev' => false,
                    'next' => false
                ],
            ];
    
            
            return response()->json($resData);
        }
    }

    public function transactions()
    {
        try {
            //code...
            $employee_id = request()->employee_id;
    
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
                ->where('withdrawal_requests.employee_id', '=', $employee_id)
                ->latest('withdrawal_requests.created_at')                
                ->paginate(6);
    
            $resData = [
                "data" => $withdrawal_request_data->items(),
                'links' => [
                    'prev' => $withdrawal_request_data->previousPageUrl() ?? false,
                    'next' => $withdrawal_request_data->nextPageUrl() ?? false
                ],
            ];
    
            
            return response()->json($resData);
        } catch (\Throwable $th) {
            $resData = [
                "data" => [],
                'links' => [
                    'prev' => false,
                    'next' => false
                ],
            ];
    
            
            return response()->json($resData);
        }
        
    }
}
