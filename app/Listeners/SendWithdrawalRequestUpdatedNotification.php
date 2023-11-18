<?php


namespace App\Listeners;

use App\Events\WithdrawalRequestUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Withdrawal_requests;
use App\Notifications\WithdrawalRequestUpdated_Attorney;
use App\Notifications\WithdrawalRequestUpdated_Employee;

class SendWithdrawalRequestUpdatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\WithdrawalRequestUpdated  $event
     * @return void
     */
    public function handle(WithdrawalRequestUpdated $event)
    {
        // print_r(json_encode($event));
        // die(); 
        $withdrawal_requests = Withdrawal_requests::where(
                                            'withdrawal_request_id', $event->withdrawal_requests->withdrawal_request_id
                                        )->cursor();
        
        foreach ( $withdrawal_requests as $withdrawal_request) {
            $withdrawal_request->notify(new WithdrawalRequestUpdated_Employee($event->withdrawal_requests));
            $withdrawal_request->notify(new WithdrawalRequestUpdated_Attorney($event->withdrawal_requests));
        }
    }
}
