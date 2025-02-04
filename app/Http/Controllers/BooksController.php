<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Review;
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
        $books = Books::where('title', 'like', '%' . request('search') . '%')->orWhere('penulis', 'like', '%' .  request('search') . '%')->get();
        if (request('search')) {
            $title = 'Hasil pencarian dari ' . request('search') . ' | ';
        }
        if (Gate::allows('isUser')) {
            $title .= 'Overview';
        }
        if (Gate::allows('isAdmin')) {
            $title .= 'All Book';
        }
        return view('book.books', [
            'title' => $title,
            'books' => $books
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create', [
            'title' => 'Add a new book',
            'categories' => Category::all(),
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
        $request['user_id'] = auth()->user()->id;
        $request['slug'] = Str::of($request->title)->slug('-');
        $validateData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:books',
            'kode_buku' => 'required|unique:books',
            'category_id' => 'required',
            'user_id' => 'required',
            'penulis' => 'required',
            'description' => 'required',
            'image' => 'required',
            'penerbit' => 'required',
            'stok' => 'required',
            'thn_terbit' => 'required',
        ]);
        $validateData['image'] = $request->file('image')->store('image_post');
        $store = Books::create($validateData);
        return $store ? redirect()->route('books.index')->with('success', 'New post has been added!') : redirect()->route('books.index')->with('failed', 'New post failed to add!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
{
    $book = Books::where('slug', $slug)->firstOrFail(); // Retrieve the book by its slug
    $reviews = Review::where('book_id', $book->id)->get(); // Fetch reviews for the current book

    return view('book.show', compact('book', 'reviews')); // Pass both book and reviews to the view
}

    
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Books $book)
    {
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
        $request['user_id'] = auth()->user()->id;
        // $request['slug'] = Str::of($request->title)->slug('-');
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
        ];
        $slug = Str::of($request->title)->slug('-');
        if ($slug != $book->slug) {
            $validate['slug'] = 'sometimes|required|unique:books';
            $request['slug'] = Str::of($request->title)->slug('-');
        }
        if ($request['kode_buku'] != $book->kode_buku) {
            $validate['kode_buku'] = 'sometimes|required|unique:books';
        }
        $validateData = $request->validate($validate);
        if ($request['image']) {
            $validateData['image'] = $request->file('image')->store('/');
        }
        $update = $book->update($validateData);
        return $update ? redirect()->route('books.index')->with('success', 'New post has been added!') : redirect()->route('books.index')->with('failed', 'New post failed to add!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Books::destroy($id);
        $bor = Borrow::where('book_id', $id)->delete();
        // $bor->delete();

        return redirect()->route('books.index')->with('successDelete', 'Book has been deleted!');
    }
    public function review(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string',
        ]);

        // Attempt to create the review
        $review = Review::create($validateData);

        // Fetch all reviews for the book
        $reviews = Review::where('book_id', $validateData['book_id'])->get();

        // Fetch the book for the review form
        $book = Books::find($validateData['book_id']);

        // Store the reviews in session
        session()->flash('reviews', $reviews);

        // Check if the review was created and send data back to the page
        if ($review) {
            return redirect()->back()
                ->with('success', 'Review added successfully!')
                ->with('book', $book); // Send the book back to the page for the review form
        }

        return redirect()->back()->with('failed', 'Failed to add review.');
    } 
    public function reviewstore(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string',
        ]);
    
        // Simpan review ke dalam database
        $review = Review::create($validateData);
    
        if ($review) {
            return redirect()->back()->with('success', 'Review added successfully!');
        }
    
        return redirect()->back()->with('failed', 'Failed to add review.');
    }
    public function storeWishlist(Request $request, Books $book)
    {
        $request->validate([
            'suka' => 'required|string',
        ]);
    
        // Update the book's suka field
        $book->update(['suka' => $request->suka]);
    
        return redirect()->route('wishlist.index')->with('success', 'Wishlist updated successfully!');
    }
    
    public function indexWishlist()
    {
        $wishlistBooks = Books::where('suka', 'liked')->get(); // Adjust based on your preferred string value
        return view('wishlist.index', compact('wishlistBooks'));
    }
    public function removeFromWishlist(Books $book)
{
    $book->update(['suka' => null]); // Set suka jadi null untuk menghapus dari wishlist

    return redirect()->route('wishlist.index')->with('success', 'Buku telah dihapus dari wishlist.');
}
      
    }

