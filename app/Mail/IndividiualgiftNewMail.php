<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Incentives_gift_transfer;
use App\Models\Employee;
use App\Models\Incentive_gift;
use App\Models\User;

class IndividiualgiftNewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipentsEmail = null;

    public $imageURL = "https://incentive.ejpapc.com/images/pizza/5.jpg";
    public $imageAlt = "";

    public $allEmployeesGettingGift = [ ];
    public $allGiftImages = [ 
        "coffee" => [
            "https://incentive.ejpapc.com/images/coffee/1.jpg",
            "https://incentive.ejpapc.com/images/coffee/2.jpg"
        ],
        "cupcake" => [
            "https://incentive.ejpapc.com/images/cupcake/1.jpg",
            "https://incentive.ejpapc.com/images/cupcake/2.jpg",
            "https://incentive.ejpapc.com/images/cupcake/3.jpg",
            
        ],
        "flower" => [
            "https://incentive.ejpapc.com/images/flower/1.jpg",
            "https://incentive.ejpapc.com/images/flower/2.jpg",
            "https://incentive.ejpapc.com/images/flower/3.jpg",
            "https://incentive.ejpapc.com/images/flower/4.jpg",
            "https://incentive.ejpapc.com/images/flower/5.jpg",
            
        ],
        "pizza" => [
            "https://incentive.ejpapc.com/images/pizza/1.jpg",                        
            "https://incentive.ejpapc.com/images/pizza/2.jpg",                        
            "https://incentive.ejpapc.com/images/pizza/3.jpg",                        
            "https://incentive.ejpapc.com/images/pizza/4.jpg",                        
            "https://incentive.ejpapc.com/images/pizza/5.jpg",                        
        ],
        "silver_pen" => [
            "https://incentive.ejpapc.com/images/pen/2.jpg",            
        ],        
    ];

    public $incentiveGiftGeneralData_name = null;
    public $incentiveGiftGeneralData_icon_name = null;
    public $incentiveGiftGeneralData_amount_per_item = null;
    public $incentiveGiftGeneralData_quantity = null;
    public $incentiveGiftGeneralData_total_amount = null;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Incentives_gift_transfer $Incentives_gift_transfer, $allRecipientData, Incentive_gift|null $incentiveGift)
    {        
        $to_employee = Employee::find($Incentives_gift_transfer->to_employee_id);
        $user_id = $to_employee->user_id;
        
        $user = User::find($user_id);    
        
        $this->recipentsEmail = $user->email;

        array_push($this->allEmployeesGettingGift, [
            "isRecipentOfMail" => true,
            "email" => $user->email,
            "amount" => $Incentives_gift_transfer->amount
        ]);   
        

        // {"incentives_gift_id":1,"incentives_gift_type_id":"single_9f844a1860e7d70be0aa98d16775c4d11ce26febc83a394bf8ef23c21ba2567c","incentives_gift_type":"individual","amount":"7.50","gift_quantity":"3","note":"test","created_at":"2023-11-26T14:39:32.000000Z","updated_at":"2023-11-26T14:39:32.000000Z","incentive_id":2,"incentive":{"incentive_id":2,"name":"Cupcake","icon_name":"cupcake","amount_per_item":"2.50","type":"individual","created_at":"2023-11-20T06:46:52.000000Z","updated_at":"2023-11-20T06:46:52.000000Z"}}

        $this->incentiveGiftGeneralData_name = $incentiveGift->incentive->name ?? '';
        $this->incentiveGiftGeneralData_icon_name = $incentiveGift->incentive->icon_name ?? '';
        $this->incentiveGiftGeneralData_amount_per_item = $incentiveGift->incentive->amount_per_item ?? '';
        $this->incentiveGiftGeneralData_quantity = $incentiveGift->gift_quantity ?? '';
        $this->incentiveGiftGeneralData_total_amount = $incentiveGift->amount ?? '';
                            
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: "Congratulations! You've Received a Special Recognition at E-Justice Project 🎉",
            to: [$this->recipentsEmail]
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            html: 'vendor.custom.gifts.giftMail',
            text: 'vendor.custom.gifts.giftMail_plain_text',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

}
