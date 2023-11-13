<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

use App\Events\newEmployeeGift;

class Incentives_gift_transfer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'incentives_gift_transfer';

    protected $primaryKey = 'incentives_gift_transfer_id';

    protected $dispatchesEvents = [
        'created' => newEmployeeGift::class,
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'to_employee_id',
        'from_employee_id',
    ];

     /**
     * Get the Incentive_gift that gave rise to this Incentive_gift_transfer.
     */
    public function Incentive_gift(): BelongsTo
    {
        return $this->belongsTo(Incentive_gift::class, 'incentives_gift_type_id', 'incentives_gift_type_id');
    }

    /**
     * Get the Employee receiving this Incentive_gift_transfer.
     */
    public function to_employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'to_employee_id', 'employee_id');
    }

    /**
     * Get the Employee sending this Incentive_gift_transfer.
     */
    public function from_employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'from_employee_id', 'employee_id');
    }

    public function routeNotificationForMail(Notification $notification): array|string
    {    
        $to_employee =  $this->to_employee()->first();
        
        $user_id = $to_employee->user_id;

        $user = User::find($user_id);

        // print_r(json_encode($user));
        // die();
        
        // Return email address only...
        return $user->email;
 
    }

}
