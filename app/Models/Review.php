<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'review',
        'rating',
    ];

    // Relasi ke model User
    public function user()
{
    return $this->belongsTo(User::class);
}

    // Relasi ke model Book
    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id');
    }
    
}
