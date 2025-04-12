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
        $bookshelves = Bookshelves::withCount('books')->paginate(10);

        if ($bookshelves->total() == 0) {
            return view('bookshelves.index', ['message' => 'Tidak ada rak buku.']);
        }

        return view('bookshelves.index', compact('bookshelves'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('bookshelves.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rak' => 'required|string|max:255',
            'baris' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        Bookshelves::create($request->all());

        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookshelves $bookshelf)
{
    $books = $bookshelf->books()
                ->with(['category', 'borrows'])
                ->paginate(10);

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
    $request->validate([
        'rak' => 'required|string|max:255',
        'baris' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
    ]);

    $bookshelf->update($request->only(['rak', 'baris', 'category_id']));

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

        $bookshelf->delete();

        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil dihapus.');
    }
}
