<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $dates = ['tanggal_pinjam'];
    protected $datess = ['tanggal_kembali']; 
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'denda',
        'tanggal_pinjam',
        'tanggal_kembali',
        'kode_peminjaman',
        // 'qr_code_path', // Menyimpan lokasi QR Code

    ];
    protected $casts = [
        'tanggal_pinjam' => 'date',  
        'tanggal_kembali' => 'date', 
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Book
     */
    public function book()
    {
        return $this->belongsTo(Books::class);
    }
}
