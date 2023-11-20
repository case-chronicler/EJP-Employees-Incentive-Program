<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Incentive_gift extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'incentives_gift';


    protected $primaryKey = 'incentives_gift_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'incentives_gift_type_id',
        'incentives_gift_type',
        'gift_quantity',
        'amount',
        'note',
    ];

    /**
     * Get the Incentives_gift_transfer associated with the employee.
     */
    public function incentives_gift_transfer(): HasMany {
        return $this->hasMany(Incentives_gift_transfer::class, 'incentives_gift_type_id', 'incentives_gift_type_id');
    }

    /**
     * Get the Incentive_gift that gave rise to this Incentive_gift_transfer.
     */
    public function Incentive(): HasOne
    {
        return $this->hasOne(Incentives::class, 'incentive_id', 'incentive_id');
    }

}
