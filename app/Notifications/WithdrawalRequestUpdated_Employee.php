<?php

namespace App\Notifications;

use Illuminate\Support\HtmlString;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Withdrawal_requests;


class WithdrawalRequestUpdated_Employee extends Notification
{
    use Queueable;

    
    public $Withdrawal_requests = null;
    public $sender = null;
    public $for_attorney = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Withdrawal_requests $updated_W_Request)
    {
        //
        $this->Withdrawal_requests =  $updated_W_Request;
        $this->sender =  $this->Withdrawal_requests->employee()
            ->join('users', 'employees.user_id', 'users.user_id' )            
            ->first();
        $this->for_attorney =  false;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $withdrawal_requests_link = route('withdrawal_requests.show', ["withdrawal_request_id" => $this->Withdrawal_requests->withdrawal_request_link_id]);

        return (new MailMessage)
                ->subject("Update on Your Withdrawal Request ")
                ->line(new HtmlString("I hope this email finds you well. I wanted to inform you that there has been an update regarding your withdrawal request for <b>USD ".$this->Withdrawal_requests->amount."</b>. The current status is as follows:"))

                ->line(new HtmlString("<b>Status:</b> ".$this->Withdrawal_requests->status.""))                

                ->line("Click the button review this request ")
                ->action('Withdrawal', $withdrawal_requests_link)
                ->line("If you have any questions or concerns about this update, please feel free to reach out to me. Your understanding and cooperation are appreciated.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
