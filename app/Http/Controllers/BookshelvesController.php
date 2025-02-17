<?php

namespace App\Http\Controllers;

use App\Models\Bookshelves;
use App\Models\Category;
use Illuminate\Http\Request;

class BookshelvesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookshelves = Bookshelves::paginate(10);

        if ($bookshelves->total() == 0) {
            return view('bookshelves.index', ['message' => 'Tidak ada rak buku.']);
        }

        return view('bookshelves.index', compact('bookshelves'));
    } 

    public function create()
{
    $categories = Category::all(); // Get all categories
    $bookshelves = Bookshelves::all(); // Fetch all bookshelves
    return view('bookshelves.create', compact('categories', 'bookshelves')); // Pass both categories and bookshelves to the view
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
    public function show(Bookshelves $bookshelves)
    {
        return view('bookshelves.show', compact('bookshelves'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookshelves $bookshelves) 
{
    $categories = Category::all();
    return view('bookshelves.update', compact('bookshelves', 'categories')); 
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bookshelves $bookshelves) // Gunakan route model binding
    {
        $request->validate([
            'rak' => 'required|string|max:255',
            'baris' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $bookshelves->update($request->only(['rak', 'baris', 'category_id']));

        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookshelves $bookshelves) // Perbaikan huruf kapital
    {
        $bookshelves->delete();

        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil dihapus.');
    }
}