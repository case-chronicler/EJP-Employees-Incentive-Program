<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Events\NewWithdrawalRequest;
use App\Events\WithdrawalRequestUpdated;

use App\Notifications\NewWithdrawalRequest_Attorney;
use App\Notifications\NewWithdrawalRequest_Employee;

use App\Notifications\WithdrawalRequestUpdated_Attorney;
use App\Notifications\WithdrawalRequestUpdated_Employee;

use App\Http\Controllers\EmployeeController;

class Withdrawal_requests extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'withdrawal_requests';

    protected $primaryKey = 'withdrawal_request_id';

    protected $dispatchesEvents = [
        'created' => NewWithdrawalRequest::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'withdrawal_request_link_id',
        'amount',
        'withdrawal_remark',
        'status',
    ];

    /**
     * Successfull withdrawal arising from this request.
     */
    public function withdrawal(): HasOne {
        return $this->hasOne(Withdrawals::class, 'withdrawal_request_link_id');
    }

    /**
     * Get the Employee sending this withdrawal request.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function routeNotificationForMail(
        NewWithdrawalRequest_Attorney | NewWithdrawalRequest_Employee | WithdrawalRequestUpdated_Attorney | WithdrawalRequestUpdated_Employee $notification): array|string
    {   
        $forAttorney = $notification->for_attorney;
        
        
        if($forAttorney){
            $attorneysEmails = [];

            $allAttorneys = EmployeeController::getAllAttorneys();

            if(count($allAttorneys)){
                for ($i=0; $i < count($allAttorneys); $i++) { 
                    $attorneyEmail = $allAttorneys[$i]->user_email;
                    array_push($attorneysEmails, $attorneyEmail);
                }
            }

            return $attorneysEmails;
        }

        $employee =  $this->employee()->first();
        
        $user_id = $employee->user_id;

        $user = User::find($user_id);

        // print_r(json_encode($user));
        // die();
        
        // Return email address only...
        return $user->email;
 
    }

}
