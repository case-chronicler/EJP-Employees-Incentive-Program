<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance',
        'has_elevated_permission'
    ];

    /**
     * Get the roles associated with the employee.
     */
    public function role(): HasMany {
        return $this->hasMany(Role::class, 'employee_id');
    }

    /**
     * Get the withdrawals associated with the employee.
     */
    public function withdrawal_requests(): HasMany {
        return $this->hasMany(Withdrawal_requests::class, 'employee_id');
    }

}
