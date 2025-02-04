@extends('layouts.main')

@section('contentAdmin')
    <div class="container">
        <h1>Your Wishlist</h1>
        
        <div class="wishlist-container">
            @foreach($wishlist as $item)
                <div class="wishlist-item">
                    <h3>{{ $item->book->title }}</h3>
                    <p>Author: {{ $item->book->author }}</p>
                    <p>Likes (Suka): {{ $item->book->suka }}</p> <!-- Display the 'suka' (like) value here -->
                    
                    <!-- Other wishlist item details can go here -->
                </div>
            @endforeach
        </div>

        <h2>All Books</h2>
        <div class="book-list">
            @foreach($books as $book)
                <div class="book-item">
                    <h3>{{ $book->title }}</h3>
                    <p>Likes (Suka): {{ $book->suka }}</p> <!-- Display the 'suka' value for all books -->
                    <p>Total Wishlist: {{ $book->wishlists_count }} wishlists</p> <!-- Display the total wishlist count -->
                </div>
            @endforeach
        </div>
    </div>
@endsection
