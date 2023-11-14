<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    use HasFactory;


    protected $table = 'withdrawals';

    protected $primaryKey = 'withdrawal_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'withdrawal_request_link_id',
        'amount',
    ];    

}
