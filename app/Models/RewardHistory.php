<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardHistory extends Model
{
    protected $fillable = [
        'reward_period_id',
        'user_id',
        'rank',
        'points',
        'badge',
      
    ];

    public function period()
    {
        return $this->belongsTo(RewardPeriod::class, 'reward_period_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}