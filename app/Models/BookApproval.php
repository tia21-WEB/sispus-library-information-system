<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookApproval extends Model
{
    protected $fillable = [

        'requested_by',
        'action',
        'book_data',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason'

    ];

    protected $casts = [
        'book_data' => 'array',
        'approved_at' => 'datetime'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}