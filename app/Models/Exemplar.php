<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exemplar extends Model
{
    use HasFactory;

    protected $fillable = [

        'book_id',
        'code',
        'status'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    public function borrowingExemplars()
{
    return $this->hasMany(
        BorrowingExemplar::class
    );
}
}