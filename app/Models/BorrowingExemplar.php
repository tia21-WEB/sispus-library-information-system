<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingExemplar extends Model
{
    protected $fillable = [

        'borrowing_id',
        'borrowing_detail_id',
        'exemplar_id',
        'status'

    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function borrowingDetail()
    {
        return $this->belongsTo(BorrowingDetail::class);
    }

    public function exemplar()
    {
        return $this->belongsTo(Exemplar::class);
    }
}