<?php

namespace App\Listeners;

use App\Events\newEmployeeGift;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Incentives_gift_transfer;
use App\Notifications\newIndividualGift;

class SendNewGiftNotification
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
     * @param  \App\Events\newEmployeeGift  $event
     * @return void
     */
    public function handle(newEmployeeGift $event)
    {
        $Incentives_gift_transfers = Incentives_gift_transfer::where(
                                            'incentives_gift_transfer_id', $event->Incentives_gift_transfer->incentives_gift_transfer_id
                                        )->cursor();
        
        foreach ( $Incentives_gift_transfers as $Incentives_gift_transfer) {
            $Incentives_gift_transfer->notify(new newIndividualGift($event->Incentives_gift_transfer));
        }
    }
}
