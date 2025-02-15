<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookshelves extends Model
{
    use HasFactory;

    protected $fillable = ['rak', 'baris', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
