<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function getNotifications()
{
    $userId = auth()->id(); // Ambil ID pengguna yang sedang login
    $today = Carbon::today();
    $warningDate = $today->addDays(3); // Ambil peminjaman yang mendekati 3 hari sebelum pengembalian

    $notifications = Borrow::where('user_id', $userId)
        ->where('return_date', '<=', $warningDate)
        ->where('return_date', '>=', $today)
        ->where('status', '!=', 'returned')
        ->get();

    return response()->json($notifications);
}
}
