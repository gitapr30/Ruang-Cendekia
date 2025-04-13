<?php

namespace App\Http\Controllers;

use App\Models\Bookshelves;
use App\Models\Category;
use App\Models\Books;
use Illuminate\Http\Request;

class BookshelvesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data rak buku dengan menghitung jumlah buku di setiap rak
        $bookshelves = Bookshelves::withCount('books')->paginate(10);

        // Jika tidak ada rak buku, tampilkan pesan
        if ($bookshelves->total() == 0) {
            return view('bookshelves.index', ['message' => 'Tidak ada rak buku.']);
        }

        // Menampilkan view dengan data rak buku
        return view('bookshelves.index', compact('bookshelves'));
    }

    /**
     * Menampilkan form untuk membuat rak buku baru
     */
    public function create()
    {
        // Mengambil semua kategori untuk dropdown form
        $categories = Category::all();
        return view('bookshelves.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'rak' => 'required|string|max:255',
            'baris' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Membuat rak buku baru
        Bookshelves::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookshelves $bookshelf)
    {
        // Mengambil data buku dalam rak tertentu beserta relasi category dan borrows
        $books = $bookshelf->books()
                    ->with(['category', 'borrows'])
                    ->paginate(10);

        // Menampilkan view detail rak buku dengan data buku
        return view('bookshelves.show', [
            'bookshelf' => $bookshelf,
            'books' => $books
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookshelves $bookshelf)
    {
        // Mengambil semua kategori dan data rak yang akan diedit
        $categories = Category::all();
        return view('bookshelves.update', [
            'bookshelf' => $bookshelf,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bookshelves $bookshelf)
    {
        // Validasi input form update
        $request->validate([
            'rak' => 'required|string|max:255',
            'baris' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Melakukan update data rak buku
        $bookshelf->update($request->only(['rak', 'baris', 'category_id']));

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('bookshelves.index')
            ->with('success', 'Rak buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookshelves $bookshelf)
    {
        // Cek apakah rak memiliki buku sebelum dihapus
        if ($bookshelf->books()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus rak karena masih terdapat buku di dalamnya.');
        }

        // Menghapus rak buku
        $bookshelf->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil dihapus.');
    }
}
