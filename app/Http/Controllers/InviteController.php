<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InviteController extends Controller
{
    private function mergeInvitePositionIds($new_ids, $old_ids = []) {
        $result_array = $old_ids;

        if(count($result_array)){            
            foreach ($new_ids as $key => $value) {
                if (!in_array($new_ids[$key], $result_array)) {
                    array_push($result_array, $value);
                }
            }

        }else {
            $result_array = array_merge($result_array, $new_ids);
        }

        return json_encode($result_array);

    }


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

            $validated = $request->validate([
                'invite_email' => 'required|string|email|max:255',
                'invite_status' => 'required|string|max:255',
                'invite_sent_by' => 'required',
                'positions_assigned' => 'required|array',
                'days_before_withdrawal_eligibility' => 'required|numeric|min:10',
            ]);
            
            $hashSeed = $validated['invite_email'] . rand(1,1000000);
            
            $validated['invite_link_ref'] = hash('sha256', $hashSeed);
    
            $user_id = $validated['invite_sent_by'];

            $user = User::find($user_id);

            $inviteAlreadySent = Invite::where('invite_email', $validated['invite_email'])->first();

            if($inviteAlreadySent){

                $positions_assigned_inviteAlreadySent = json_decode($inviteAlreadySent->positions_assigned, JSON_OBJECT_AS_ARRAY) ?? [];

                $positions_assigned_merged = $this->mergeInvitePositionIds($validated['positions_assigned'], $positions_assigned_inviteAlreadySent);

                $user->invite()->updateOrCreate(
                    [
                        'invite_email' => $validated['invite_email'],
                    ],
                    [
                        'invite_status' => $validated['invite_status'],
                        'invite_link_ref' => $validated['invite_link_ref'],
                        'positions_assigned' => $positions_assigned_merged,
                        'days_before_first_withdrawal' => $validated['days_before_withdrawal_eligibility']
                    ]
                );


            }else{
                $validated['positions_assigned'] = $this->mergeInvitePositionIds($validated['positions_assigned']) ;
                
                $user->invite()->updateOrCreate(
                    [
                        'invite_email' => $validated['invite_email'],
                    ],
                    [
                        'invite_status' => $validated['invite_status'],
                        'invite_link_ref' => $validated['invite_link_ref'],
                        'positions_assigned' => $validated['positions_assigned'],
                        'days_before_first_withdrawal' => $validated['days_before_withdrawal_eligibility']
                    ]
                );
            }   

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function edit(Invite $invite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invite $invite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invite $invite)
    {
        //
    }
}
