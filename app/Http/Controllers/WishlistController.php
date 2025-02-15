<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlists;
use App\Models\Borrow;
use App\Models\Books;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Menampilkan halaman wishlist
     */
    public function index()
    {
        $user = Auth::user();
        $wishlist = Wishlists::where('user_id', $user->id)
                             ->with('book')
                             ->get();

        return view('wishlist.index', compact('wishlist'));
    }

    /**
     * Mendapatkan daftar buku yang disukai oleh pengguna yang sedang login
     */
    public function getUserWishlist()
    {
        $user = Auth::user();

        $wishlist = Wishlists::where('user_id', $user->id)
            ->with('book') // Eager load the book relationship
            ->get();

        // Include the 'suka' field in the response for each book
        $wishlistWithLikes = $wishlist->map(function ($item) {
            $item->book->suka = $item->book->suka; // You can directly access 'suka' here
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'wishlist' => $wishlistWithLikes
        ]);
    }

    public function store(Request $request, $slug)
    {
        $user_id = Auth::id();
        $book = Books::where('slug', $slug)->firstOrFail();

        // Cek apakah buku sudah ada di wishlist
        $wishlist = Wishlists::where('user_id', $user_id)
                            ->where('book_id', $book->id)
                            ->first();

        if (!$wishlist) {
            Wishlists::create([
                'user_id' => $user_id,
                'book_id' => $book->id
            ]);
        }

        return back()->with('success', 'Buku ditambahkan ke Wishlist.');
    }

    /**
     * Mendapatkan total like untuk setiap buku
     */
    public function getTotalLikes()
    {
        $books = Books::withCount('wishlists')->get();

        return response()->json([
            'status' => 'success',
            'books' => $books
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlists::where('user_id', Auth::id())
                             ->where('book_id', $id)
                             ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Buku dihapus dari Wishlist.');
        }

        return back()->with('error', 'Buku tidak ditemukan di Wishlist.');
    }
}
