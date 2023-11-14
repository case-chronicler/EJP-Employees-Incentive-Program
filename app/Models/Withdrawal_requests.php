<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Withdrawal_requests extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';

    protected $primaryKey = 'withdrawal_request_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'withdrawal_request_link_id',
        'amount',
        'status',
    ];

    /**
     * Successfull withdrawal arising from this request.
     */
    public function withdrawal(): HasOne {
        return $this->hasOne(Withdrawals::class, 'withdrawal_request_link_id');
    }

}
