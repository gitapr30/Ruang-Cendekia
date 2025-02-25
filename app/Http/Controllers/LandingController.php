<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Books;
use App\Models\Category;
use App\Models\Review;
use App\Models\Change;
use App\Models\Borrow; //perubahan vira

class LandingController extends Controller
{
    public function index()
{
    $totalUsers = User::count();
    $totalBooks = Books::count();
    $totalCategories = Category::count();
    $reviews = Review::with('user')->latest()->take(5)->get();
    $change = Change::latest()->first(); // Ambil data terbaru dari tabel Change

    // Ambil buku dengan rating terbanyak
    $recommendedBooks = Books::withAvg('reviews', 'rating')
        ->orderByDesc('reviews_avg_rating') // Urutkan berdasarkan rating tertinggi
        ->take(6) // Ambil 6 buku terbaik
        ->get();

    $mostBorrowedBooks = Books::withCount('borrows') // Ambil jumlah peminjaman
        ->orderByDesc('borrows_count') // Urutkan berdasarkan jumlah peminjaman terbanyak
        ->take(6) // Ambil 6 buku yang paling banyak dipinjam
        ->get();


    return view('landing', compact(
        'totalUsers',
        'totalBooks',
        'totalCategories',
        'reviews',
        'change',
        'recommendedBooks',
        'mostBorrowedBooks'
    ));
}

}
