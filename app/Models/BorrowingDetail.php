<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $fillable = [

        'borrowing_id',
        'book_id',
        'exemplar_id',
        'qty'

    ];

    public function borrowing()
    {
        return $this->belongsTo(
            Borrowing::class
        );
    }

    public function book()
    {
        return $this->belongsTo(
            Book::class
        );
    }

    public function exemplar()
    {
        return $this->belongsTo(
            Exemplar::class
        );
    }
    public function borrowedExemplars()
{
    return $this->hasMany(
        BorrowingExemplar::class
    );
}
}