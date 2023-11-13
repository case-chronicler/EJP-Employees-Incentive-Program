<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Incentives extends Model
{
    use HasFactory;

    protected $primaryKey = 'incentive_id';


    /**
     * Get the Incentive_gift associated with the employee.
     */
    public function incentive_gift(): HasMany {
        return $this->hasMany(Incentive_gift::class, 'incentive_id');
    }
}
