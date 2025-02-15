<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Books;
use App\Models\Category;
use App\Models\Review;

class LandingController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBooks = Books::count();
        $totalCategories = Category::count();
        $reviews = Review::with('user')->latest()->take(5)->get();

        return view('landing', compact('totalUsers', 'totalBooks', 'totalCategories', 'reviews'));
    }
}
