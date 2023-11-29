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
use App\Models\User;

class IndividiualgiftNewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipent = null;

    public $imageURL = "https://incentive.ejpapc.com/images/pizza/5.jpg";

    // <img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://khjthp.stripocdn.email/content/guids/CABINET_1ce849b9d6fc2f13978e163ad3c663df/images/3991592481152831.png" alt="" width="600" />

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Incentives_gift_transfer $Incentives_gift_transfer)
    {
        $to_employee = Employee::find($Incentives_gift_transfer->to_employee_id);
        
        $user_id = $to_employee->user_id;
        
        $user = User::find($user_id);    
        
        // Return email address only...
        $this->recipent = $user->email;
        
        // print_r(json_encode($this->to));
        // die();
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
            to: [$this->recipent]
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
