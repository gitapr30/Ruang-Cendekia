<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_website',
        'alamat',
        'no_telp', '
        email',
        'maps',
        'denda',
        'denda_hilang',
        'max_peminjaman',
        'tittle',
        'description',
        'content',
        'footer',
        'waktu_operasional',
        'logo',
        'image'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
