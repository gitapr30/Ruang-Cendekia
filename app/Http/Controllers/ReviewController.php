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
        // Menampilkan semua review
  // Menampilkan semua review
  $reviews = Review::with(['user', 'book'])->get();
  return view('reviews.review', ['reviews' => $reviews]); }

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
        ]);

        // Simpan review
        $reviews = Review::create($validated);
        return redirect()->back()->with('successMessage', 'Berhasil review');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Tampilkan review berdasarkan ID
        $reviews = Review::with(['user', 'book'])->findOrFail($id);
        return response()->json($reviews);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'reviews' => 'required|string|max:255',
        ]);

        // Update review
        $reviews = Review::findOrFail($id);
        $reviews->update($validated);

        return response()->json($reviews);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Hapus review
        $reviews = Review::findOrFail($id);
        $reviews->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
