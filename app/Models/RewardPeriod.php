<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPeriod extends Model
{
    protected $fillable = [
        'name',
        'semester',
        'academic_year',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function histories()
    {
        return $this->hasMany(RewardHistory::class);
    }
}