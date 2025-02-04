<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlists;
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
            ->with('book') // Eager load the book relationship
            ->get();
        
        // Fetch books along with their 'suka' count (likes)
        $books = Books::withCount('wishlists')->get();

        return view('getwishlist.index', compact('wishlist', 'books'));
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
}
