<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's plural convention
    protected $table = 'histories';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'user_id',
        'books_id',
    ];

    /**
     * Get the user associated with the history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book associated with the history.
     */
    public function book()
    {
        return $this->belongsTo(Books::class, 'books_id');
    }
}
