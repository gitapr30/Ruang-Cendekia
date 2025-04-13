<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Review;
use App\Models\Bookshelves;
use App\Models\Wishlists;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = '';
        $search = request('search');

        // Mencari buku berdasarkan berbagai kriteria pencarian
        $books = Books::where('title', 'like', '%' . $search . '%')
            ->orWhere('kode_buku', 'like', '%' . $search . '%')
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhere('penulis', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('penerbit', 'like', '%' . $search . '%')
            ->orWhere('stok', 'like', '%' . $search . '%')
            ->orWhere('thn_terbit', 'like', '%' . $search . '%')
            ->paginate(10); // Menggunakan pagination untuk membatasi hasil

        // Menentukan judul halaman berdasarkan hasil pencarian
        if ($search) {
            $title = 'Hasil pencarian dari ' . $search . ' | ';
        }

        // Menyesuaikan judul berdasarkan role user
        if (Gate::allows('isUser')) {
            $title .= 'Overview';
        }

        if (Gate::allows('isAdmin')) {
            $title .= 'All Book';
        }

        // Mengambil semua kategori dengan buku terkait
        $categories = Category::with('books')->get();
        // Mengambil buku terbaru berdasarkan tanggal dibuat
        $newBooks = Books::orderByDesc('created_at')->paginate(10);

        // Memeriksa status wishlist untuk user yang login
        $wishlistStatus = [];
        if (auth()->check()) {
            $wishlistStatus = Wishlists::where('user_id', auth()->id())
                ->pluck('book_id')
                ->toArray();
        }

        // Mengembalikan view dengan data yang diperlukan
        return view('book.books', [
            'title' => $title,
            'books' => $books,
            'categories' => $categories,
            'newBooks' => $newBooks,
            'wishlistStatus' => $wishlistStatus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan buku baru
        return view('book.create', [
            'title' => 'Add a new book',
            'categories' => Category::all(),
            'racks' => Bookshelves::orderBy('rak', 'asc')->get(), // Mengambil data rak buku
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Menyiapkan data untuk validasi
        $request['user_id'] = auth()->user()->id;
        $request['slug'] = Str::slug($request->title);

        // Validasi data input
        $validateData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:books',
            'kode_buku' => 'required|unique:books',
            'category_id' => 'required',
            'rak_id' => 'required|exists:bookshelves,id',
            'user_id' => 'required',
            'penulis' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'penerbit' => 'required',
            'stok' => 'required',
            'thn_terbit' => 'required',
            'halaman' => 'required|numeric',
        ]);

        // Menetapkan nilai default untuk suka
        $validateData['suka'] = 0;

        // Menangani upload gambar
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('book', $fileName, 'public');
            $validateData['image'] = 'storage/book/' . $fileName;
        }

        // Menyimpan data buku baru
        $store = Books::create($validateData);

        // Mengembalikan response berdasarkan hasil penyimpanan
        return $store
            ? redirect()->route('books.index')->with('success', 'New book has been added!')
            : redirect()->route('books.index')->with('failed', 'Failed to add new book.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // Mengambil data buku berdasarkan slug
        $book = Books::where('slug', $slug)->firstOrFail();
        // Mengambil review untuk buku tersebut
        $reviews = Review::where('book_id', $book->id)->get();

        // Menghitung rata-rata rating
        $averageRating = Review::where('book_id', $book->id)->avg('rating') ?? 0;

        // Menghitung total ulasan
        $totalReviews = $reviews->count();

        // Menghitung distribusi rating
        $ratingDistribution = Review::where('book_id', $book->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Memastikan semua rating (1-5) ada dalam distribusi
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }

        // Mengurutkan rating dari tertinggi ke terendah
        krsort($ratingDistribution);

        // Mengembalikan view dengan data buku dan review
        return view('book.show', compact('book', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Books $book)
    {
        // Menampilkan form edit buku
        return view('book.edit', [
            'title' => $book->title,
            'book' => $book,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Books $book)
    {
        // Menyiapkan data untuk validasi
        $request['user_id'] = auth()->user()->id;
        $validate = [
            'title' => 'sometimes|required|max:255',
            'slug' => 'sometimes|required',
            'kode_buku' => 'sometimes|required|',
            'category_id' => 'sometimes|required',
            'user_id' => 'sometimes|required',
            'penulis' => 'sometimes|required',
            'description' => 'sometimes|required',
            'image' => 'sometimes|required',
            'penerbit' => 'sometimes|required',
            'stok' => 'sometimes|required',
            'thn_terbit' => 'sometimes|required',
            'halaman' => 'sometimes|required|numeric',
        ];

        // Validasi slug jika berubah
        $slug = Str::of($request->title)->slug('-');
        if ($slug != $book->slug) {
            $validate['slug'] = 'sometimes|required|unique:books';
            $request['slug'] = Str::of($request->title)->slug('-');
        }

        // Validasi kode buku jika berubah
        if ($request['kode_buku'] != $book->kode_buku) {
            $validate['kode_buku'] = 'sometimes|required|unique:books';
        }

        // Validasi data
        $validateData = $request->validate($validate);

        // Menangani upload gambar baru
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('book', $fileName, 'public');
            $validateData['image'] = 'storage/book/' . $fileName;
        }

        // Melakukan update data buku
        $update = $book->update($validateData);

        // Mengembalikan response berdasarkan hasil update
        return $update
            ? redirect()->route('books.index')->with('success', 'New post has been added!')
            : redirect()->route('books.index')->with('failed', 'New post failed to add!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Menghapus buku dan data peminjaman terkait
        Books::destroy($id);
        $bor = Borrow::where('book_id', $id)->delete();

        // Mengembalikan ke halaman index dengan pesan sukses
        return redirect()->route('books.index')->with('successDelete', 'Book has been deleted!');
    }

    /**
     * Menangani pembuatan review
     */
    public function review(Request $request)
    {
        // Validasi data review
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string',
        ]);

        // Membuat review baru
        $review = Review::create($validateData);

        // Mengambil semua review untuk buku tersebut
        $reviews = Review::where('book_id', $validateData['book_id'])->get();

        // Mengambil data buku untuk form review
        $book = Books::find($validateData['book_id']);

        // Menyimpan review dalam session
        session()->flash('reviews', $reviews);

        // Mengembalikan response berdasarkan hasil pembuatan review
        if ($review) {
            return redirect()->back()
                ->with('success', 'Review added successfully!')
                ->with('book', $book);
        }

        return redirect()->back()->with('failed', 'Failed to add review.');
    }

    /**
     * Menyimpan review ke database
     */
    public function reviewstore(Request $request)
    {
        // Validasi data review
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string',
        ]);

        // Menyimpan review ke database
        $review = Review::create($validateData);

        if ($review) {
            return redirect()->back()->with('success', 'Review added successfully!');
        }

        return redirect()->back()->with('failed', 'Failed to add review.');
    }

    /**
     * Menyimpan buku ke wishlist
     */
    public function storeWishlist(Request $request, Books $book)
    {
        // Validasi input
        $request->validate([
            'suka' => 'required|string',
        ]);

        // Mengupdate status suka pada buku
        $book->update(['suka' => $request->suka]);

        return redirect()->route('wishlist.index')->with('success', 'Wishlist updated successfully!');
    }

    /**
     * Menampilkan daftar wishlist
     */
    public function indexWishlist()
    {
        // Mengambil buku yang disukai
        $wishlistBooks = Books::where('suka', 'liked')->get();
        return view('wishlist.index', compact('wishlistBooks'));
    }

    /**
     * Menghapus buku dari wishlist
     */
    public function removeFromWishlist(Books $book)
    {
        // Mengupdate status suka menjadi null
        $book->update(['suka' => null]);

        return redirect()->route('wishlist.index')->with('success', 'Buku telah dihapus dari wishlist.');
    }
}
