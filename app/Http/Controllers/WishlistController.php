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
        $user_id = Auth::id();

        // Ambil daftar wishlist milik user saat ini
        $wishlistBooks = Books::whereIn('id', function ($query) use ($user_id) {
            $query->select('book_id')->from('wishlists')->where('user_id', $user_id);
        })->get();

        return view('wishlist.index', compact('wishlistBooks'));
    }

    /**
     * Mendapatkan daftar wishlist untuk admin
     */
    public function getAdminWishlist()
    {
        $wishlist = Wishlists::with(['book', 'user'])
            ->whereHas('book')
            ->whereHas('user')
            ->get();

        $books = Books::withCount('wishlists')->get();

        return view('getwishlist.index', compact('books', 'wishlist'));
    }

    /**
     * Mendapatkan daftar buku yang disukai oleh pengguna yang sedang login
     */
    public function getUserWishlist()
    {
        $user = Auth::user();

        $wishlist = Wishlists::where('user_id', $user->id)
            ->with('book')
            ->get();

        $wishlistWithLikes = $wishlist->map(function ($item) {
            $item->book->suka = $item->book->suka;
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'wishlist' => $wishlistWithLikes
        ]);
    }

    /**
     * Menambahkan buku ke wishlist
     */
    public function store(Request $request, $slug)
    {
        $user_id = Auth::id();
        $book = Books::where('slug', $slug)->first();

        if (!$book) {
            return redirect()->route('wishlist.index')->with('error', 'Buku tidak ditemukan.');
        }

        // Periksa apakah buku sudah ada di wishlist
        $wishlist = Wishlists::where('user_id', $user_id)
                            ->where('book_id', $book->id)
                            ->first();

        if ($wishlist) {
            return redirect()->route('wishlist.index')->with('error', 'Buku sudah ada di Wishlist.');
        }

        Wishlists::create([
            'user_id' => $user_id,
            'book_id' => $book->id,
            'keep' => 1,
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Buku berhasil ditambahkan ke Wishlist.');
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

    /**
     * Menghapus buku dari wishlist pengguna
     */
    public function destroy($id)
    {
        $wishlist = Wishlists::where('user_id', Auth::id())
                            ->where('book_id', $id)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->route('wishlist.index')->with('success', 'Buku dihapus dari Wishlist.');
        }

        return redirect()->route('wishlist.index')->with('error', 'Buku tidak ditemukan di Wishlist.');
    }
}
