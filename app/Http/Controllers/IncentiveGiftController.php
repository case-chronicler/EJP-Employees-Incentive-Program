<?php

namespace App\Http\Controllers;

use App\Notifications\NewGift_Attorney;

use App\Models\Employee;
use App\Models\Incentive_gift;
use App\Models\User;
use App\Models\Incentives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;


class IncentiveGiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->user_id;
        $incentive_gift = [];

        $employee = User::find($user_id)->employee()->first();

        $employee_id = $employee->employee_id;

        $isEmployeeAnAttorney = EmployeeController::getIsEmployeeAnAttorney();

        $incentive_gift = DB::table('incentives_gift')
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
            );

        if($isEmployeeAnAttorney){
            $incentive_gift = $incentive_gift->paginate(6);
        }else{
            $incentive_gift = $incentive_gift->where('incentives_gift_transfer.to_employee_id', '=', $employee_id)->paginate(6);
        }

        // return json_encode($incentive_gift->query);
        // die();

        $incentive_gift_data = $incentive_gift->items() ?? [];

        $processed_incentive_gifts = [];

        foreach ($incentive_gift_data as $current_incentive_gift) {

            if(!isset($processed_incentive_gifts[$current_incentive_gift->incentives_gift_incentives_gift_type_id])){
                $processed_incentive_gifts[$current_incentive_gift->incentives_gift_incentives_gift_type_id] = [
                    'incentives_name' => $current_incentive_gift->incentives_name,
                    'incentives_icon_name' => $current_incentive_gift->incentives_icon_name,
                    'user_from_fullname' => $current_incentive_gift->user_from_fullname,
                    'user_from_email' => $current_incentive_gift->user_from_email,
                    'incentives_gift_total_amount' => $current_incentive_gift->incentives_gift_total_amount,
                    'incentives_gift_gift_quantity' => $current_incentive_gift->incentives_gift_gift_quantity,
                    'incentives_gift_note' => $current_incentive_gift->incentives_gift_note,
                    'incentives_gift_created_at' => $current_incentive_gift->incentives_gift_created_at,
                    'incentives_gift_incentives_gift_type_id' => $current_incentive_gift->incentives_gift_incentives_gift_type_id,

                    "gift_distribution" => [
                        [
                            'incentives_gift_incentives_gift_id' => $current_incentive_gift->incentives_gift_incentives_gift_id,
                            'incentives_gift_transfer_incentives_gift_transfer_id' => $current_incentive_gift->incentives_gift_transfer_incentives_gift_transfer_id,
                            'incentives_gift_incentives_gift_type_id' => $current_incentive_gift->incentives_gift_incentives_gift_type_id,
                            'incentives_gift_incentives_gift_type' => $current_incentive_gift->incentives_gift_incentives_gift_type,
                            'incentives_gift_transfer_amount_per_employee' => $current_incentive_gift->incentives_gift_transfer_amount_per_employee,
                            'user_to_fullname' => $current_incentive_gift->user_to_fullname,
                            'user_to_email' => $current_incentive_gift->user_to_email,
                        ]
                    ]
                ];
            }else{

                array_push($processed_incentive_gifts[$current_incentive_gift->incentives_gift_incentives_gift_type_id]['gift_distribution'], [
                    'incentives_gift_incentives_gift_id' => $current_incentive_gift->incentives_gift_incentives_gift_id,
                    'incentives_gift_transfer_incentives_gift_transfer_id' => $current_incentive_gift->incentives_gift_transfer_incentives_gift_transfer_id,
                    'incentives_gift_incentives_gift_type_id' => $current_incentive_gift->incentives_gift_incentives_gift_type_id,
                    'incentives_gift_incentives_gift_type' => $current_incentive_gift->incentives_gift_incentives_gift_type,
                    'incentives_gift_transfer_amount_per_employee' => $current_incentive_gift->incentives_gift_transfer_amount_per_employee,
                    'user_to_fullname' => $current_incentive_gift->user_to_fullname,
                    'user_to_email' => $current_incentive_gift->user_to_email,
                ]);
            }

        }

        // return json_encode($processed_incentive_gifts);
        
        return Inertia::render('Gift/index', [    
            "incentive_gift" => [
                'data' => $processed_incentive_gifts,
                'links' => [
                    'prev' => $incentive_gift->previousPageUrl() ?? false,
                    'next' => $incentive_gift->nextPageUrl() ?? false
                ],
            ]
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
        $validated_general = $request->validate([
            'gift_type' => 'required|string|in:individual,group',
            'gift_total_price' => 'required|string',
            'gift_id' => 'required|numeric',
            'employees' => 'required|array',
            'gift_note' => 'required|string',
        ]);
        
        $validated_general['gift_total_price'] = floatval($validated_general['gift_total_price']);   

        if($validated_general["gift_type"] === 'individual'){
            $validated_individual = $request->validate([
                'gift_quantity' => 'required|numeric',
            ]);

            $validated_individual = array_merge($validated_individual, $validated_general);
    
            return $this->giftIndividual($validated_individual);
        }
        
        if($validated_general["gift_type"] === 'group'){
            $validated_group = $request->validate([
                'gift_price_per_employee'  => 'required|numeric',
            ]);

            $validated_group = array_merge($validated_group, $validated_general);

            return $this->giftGroup($validated_group);            
        }
        

    }

    static function getIncentiveDataByIncentiveGiftTypeId ($incentive_gift_type_id) {
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
                )->where('incentives_gift.incentives_gift_type_id', '=', $incentive_gift_type_id)
                ->latest('incentives_gift.created_at')                
                ->get();

            return $incentiveData ?? [];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incentive_gift  $incentive_gift
     * @return \Illuminate\Http\Response
     */
    public function show(Incentive_gift $incentive_gift)
    {
        $incentive_gift_type_id = request()->incentive_gift_type_id;

        $incentiveData = self::getIncentiveDataByIncentiveGiftTypeId($incentive_gift_type_id);

        $incentiveData_processed = [
            "recipient" => [],
            "sender" => [],
            'incentives_gift_incentives_gift_type' => '',
            'incentives_gift_gift_quantity' => '',
            'incentives_gift_note' => '',
            'incentives_gift_created_at' => '',
            
            'incentives_gift_transfer_amount_per_employee' => '',
            'incentives_gift_total_amount' => '',
            'incentives_name' => '',
            'incentives_icon_name' => '',
        ];

        for ($i=0; $i < count($incentiveData) ; $i++) { 
            $elem = $incentiveData[$i];

            if(! in_array($elem->user_from_email, $incentiveData_processed['sender'])){
                array_push($incentiveData_processed['sender'], $elem->user_from_email);
            }

            if(! in_array($elem->user_to_email, $incentiveData_processed['recipient'])){
                array_push($incentiveData_processed['recipient'], $elem->user_to_email);
            }
                                    
            $incentiveData_processed['incentives_gift_incentives_gift_type'] = $elem->incentives_gift_incentives_gift_type;
            $incentiveData_processed['incentives_gift_gift_quantity'] = $elem->incentives_gift_gift_quantity;
            $incentiveData_processed['incentives_gift_note'] = $elem->incentives_gift_note;
            $incentiveData_processed['incentives_gift_created_at'] = $elem->incentives_gift_created_at;
            
            $incentiveData_processed['incentives_gift_transfer_amount_per_employee'] = $elem->incentives_gift_transfer_amount_per_employee;
            $incentiveData_processed['incentives_gift_total_amount'] = $elem->incentives_gift_total_amount;

            $incentiveData_processed['incentives_name'] = $elem->incentives_name;
            $incentiveData_processed['incentives_icon_name'] = $elem->incentives_icon_name;
        }        

        return Inertia::render('Gift/single', [    
            'incentiveData_processed' => $incentiveData_processed,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incentive_gift  $incentive_gift
     * @return \Illuminate\Http\Response
     */
    public function edit(Incentive_gift $incentive_gift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incentive_gift  $incentive_gift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incentive_gift $incentive_gift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Incentive_gift  $incentive_gift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incentive_gift $incentive_gift)
    {
        //
    }


    private function giftIndividual ($validated) {
        try {
            //code...
            $sender = auth()->user();
            
            $sender_employee = Employee::where('user_id', $sender->user_id)->first();
            $sender_employee_id = $sender_employee->employee_id;
    
            $employees = $validated['employees'] ?? [];
            $gift_id = $validated['gift_id'];
            $gift_total_price = $validated['gift_total_price'];
            $gift_quantity = $validated['gift_quantity'];
            $gift_note = $validated['gift_note'];
    
            $gift_quantity = (float) $gift_quantity;
            $gift_total_price = (float) $gift_total_price;
    
            if(count($employees) === 0 ){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => "Individual gift can only be attached to one(1) employee"
                ]);
            }
            
            if(count($employees) > 1 ){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => "Too many employees, Individual gift can only be attached to one(1) employee"
                ]);
            }
    
            $incentive = Incentives::find($gift_id);
    
            $incentive_amount_per_item = $incentive->amount_per_item;
            $incentive_amount_per_item = (float) $incentive_amount_per_item;
            
            $calculated_total_price = $incentive_amount_per_item * $gift_quantity;
            $calculated_total_price = (float) $calculated_total_price;
    
            if($gift_total_price !== $calculated_total_price){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => "We experienced an error while sending your gift. Please try again"
                ]);
            }
    
            $hashSeed = now() . rand(1,1000);            
            $incentives_gift_type_id = "single_".hash('sha256', $hashSeed);
    
            $incentives_gift_type = 'individual';
            $receiver_user_id = $employees[0];
            
            DB::beginTransaction();
            
            $newIncentiveGift = $incentive->incentive_gift()->create([
                'incentives_gift_type_id' => $incentives_gift_type_id,
                'incentives_gift_type' => $incentives_gift_type,
                'gift_quantity' => $gift_quantity,
                'amount' => $gift_total_price,
                'note' => $gift_note,
            ]);
    
            $newIncentiveGiftTransfer = $newIncentiveGift->incentives_gift_transfer()->create([
                'amount' => $gift_total_price,
                'to_employee_id' => $receiver_user_id,
                'from_employee_id' => $sender_employee_id,
            ]);

            Employee::find($receiver_user_id)->increment('balance', $gift_total_price);

            $employee = Employee::find($sender_employee->employee_id);
            $employee->notify(new NewGift_Attorney($newIncentiveGift));            
            
            DB::commit(); 
            
            $allRecipientData = self::getIncentiveDataByIncentiveGiftTypeId($incentives_gift_type_id);
            
            $incentiveGiftData = $newIncentiveGift::with('Incentive')->where('incentives_gift_type_id', '=', $incentives_gift_type_id)->first();

        //     print_r(json_encode($newIncentiveGift));
        //     print_r(json_encode($incentiveGiftData));
        // die();

            Mail::send(new \App\Mail\IndividiualgiftNewMail($newIncentiveGiftTransfer, $allRecipientData, $incentiveGiftData));
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                // 'general' => 'Sorry, we are unable to process your request. Please try again'
                'general' => $th->getMessage()
            ]);
        }
        

    }

    private function giftGroup ($validated) {
        try {
            //code...
            $sender = auth()->user();
            
            $sender_employee = Employee::where('user_id', $sender->user_id)->first();
            $sender_employee_id = $sender_employee->employee_id;
    
            $employees = $validated['employees'] ?? [];
            $gift_id = $validated['gift_id'];
            $gift_total_price = $validated['gift_total_price'];
            $gift_price_per_employee = $validated['gift_price_per_employee'];
            $gift_note = $validated['gift_note'];
    
            $gift_price_per_employee = (float) $gift_price_per_employee;
            $gift_total_price = (float) $gift_total_price;

    
            if(count($employees) === 0 ){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => "Group gift needs atleast one(1) employee"
                ]);
            }
    
            $incentive = Incentives::find($gift_id);                     
    
            $hashSeed = now() . rand(1,1000);            
            $incentives_gift_type_id = "group_".hash('sha256', $hashSeed);
    
            $incentives_gift_type = 'group';
            
            DB::beginTransaction();
            
            $newIncentiveGift = $incentive->incentive_gift()->create([
                'incentives_gift_type_id' => $incentives_gift_type_id,
                'incentives_gift_type' => $incentives_gift_type,
                'gift_quantity' => 1,
                'amount' => $gift_total_price,
                'note' => $gift_note,
            ]);
            
            for ($i=0; $i < count($employees) ; $i++) { 
                $receiver_user_id = $employees[$i];
                
                $newIncentiveGift->incentives_gift_transfer()->create([
                    'amount' => $gift_price_per_employee,
                    'to_employee_id' => $receiver_user_id,
                    'from_employee_id' => $sender_employee_id,
                ]);

                Employee::find($receiver_user_id)->increment('balance', $gift_price_per_employee);
            }

            $employee = Employee::find($sender_employee->employee_id);
            $employee->notify(new NewGift_Attorney($newIncentiveGift));
            
            DB::commit(); 
    
        } catch (\Throwable $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                // 'general' => 'Sorry, we are unable to process your request. Please try again'
                'general' => $th->getMessage()
            ]);
        }
        

    }
}
