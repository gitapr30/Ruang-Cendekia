<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Books;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menghitung rata-rata rating dari semua buku
        $averageRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // Menampilkan semua review dengan user dan buku terkait
        $reviews = Review::with(['user', 'book'])->get();

        return view('reviews.review', compact('reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Simpan review dan rating
        Review::create($validated);

        return redirect()->back()->with('successMessage', 'Berhasil memberikan ulasan dan rating');
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     // Tampilkan review berdasarkan ID
    //     $review = Review::with(['user', 'book'])->findOrFail($id);
    //     return response()->json($review);
    // }

    public function show($id)
{
    $book = Books::with('reviews')->findOrFail($id);

    // Hitung rata-rata rating dari ulasan yang ada
    $averageRating = $book->reviews->avg('rating') ?? 0;
    $totalReviews = $book->reviews->count();

    // Hitung distribusi rating (persentase setiap rating 1-5)
    $ratingCounts = $book->reviews->groupBy('rating')->map->count();
    $ratingDistribution = [];

    for ($i = 5; $i >= 1; $i--) {
        $ratingDistribution[$i] = isset($ratingCounts[$i]) ? round(($ratingCounts[$i] / max(1, $totalReviews)) * 100, 1) : 0;
    }

    return view('books.show', compact('book', 'averageRating', 'totalReviews', 'ratingDistribution'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'review' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Update review
        $review = Review::findOrFail($id);
        $review->update($validated);

        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return redirect()->back()->with('successMessage', 'Review deleted successfully');
    }
}
