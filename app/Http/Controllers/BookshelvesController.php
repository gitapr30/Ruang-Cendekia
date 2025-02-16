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
        $bookshelves = Bookshelves::with('category')->get();
        return view('bookshelves.index', compact('bookshelves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $categories = Category::all();
    //     return view('bookshelves.create', compact('categories'));
    // }

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
    public function edit($id)
{
    $bookshelves = Bookshelves::findOrFail($id);
    $categories = Category::all();
    return view('bookshelves.update', compact('bookshelves', 'categories'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $bookshelves = Bookshelves::findOrFail($id); // Gunakan findOrFail agar error lebih jelas

    $request->validate([
        'rak' => 'required|string|max:255',
        'baris' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
    ]);

    $bookshelves->update([
        'rak' => $request->rak,
        'baris' => $request->baris,
        'category_id' => $request->category_id,
    ]);

    return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(bookshelves $bookshelves)
    {
        $bookshelves->delete();

        return redirect()->route('bookshelves.index')->with('success', 'Rak buku berhasil dihapus.');
    }
}
