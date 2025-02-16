<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'borrows'; // Pastikan tabel sesuai dengan database

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'keterangan',
        'tanggal_pinjam',
        'tanggal_kembali',
        'kode_peminjaman',
        'denda',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'denda' => 'integer', // Tambahkan ini
    ];

    public $timestamps = true; // Pastikan timestamps diaktifkan jika tabel memiliki 'created_at' dan 'updated_at'

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Book
     */
    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id');
    }
}
