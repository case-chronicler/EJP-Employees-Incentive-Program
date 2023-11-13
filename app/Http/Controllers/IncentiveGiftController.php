<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Incentive_gift;
use App\Models\User;
use App\Models\Incentives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class IncentiveGiftController extends Controller
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incentive_gift  $incentive_gift
     * @return \Illuminate\Http\Response
     */
    public function show(Incentive_gift $incentive_gift)
    {
        //
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
    
            $newIncentiveGift->incentives_gift_transfer()->create([
                'amount' => $gift_total_price,
                'to_employee_id' => $receiver_user_id,
                'from_employee_id' => $sender_employee_id,
            ]);

            Employee::find($receiver_user_id)->increment('balance', $gift_total_price);
            
            DB::commit(); 
    
        } catch (\Throwable $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'general' => 'Sorry, we are unable to process your request. Please try again'
                // 'general' => $th->getMessage()
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
                
            $calculated_total_price = count($employees) * $gift_price_per_employee;
            $calculated_total_price = (float) $calculated_total_price;
    
            if($gift_total_price !== $calculated_total_price){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => "We experienced an error while sending your gift. Please try again"
                ]);
            }
    
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
    

            
            DB::commit(); 
    
        } catch (\Throwable $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'general' => 'Sorry, we are unable to process your request. Please try again'
                // 'general' => $th->getMessage()
            ]);
        }
        

    }
}
