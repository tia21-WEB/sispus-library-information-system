<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nis_nip',
        'email',
        'password',
        'role',
        'points',
        'badge',
        'phone',
        'address',
        'is_active',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    public function rewardHistories()
{
    return $this->hasMany(RewardHistory::class);
}
}