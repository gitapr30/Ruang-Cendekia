@extends('layouts.main')

@section('contentAdmin')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Wishlist Admin</h1>

        <h2 class="text-xl font-semibold text-gray-700 mb-2">Jumlah Wishlist per Buku</h2>
        <div class="book-list bg-white shadow-md p-4 rounded-lg">
            @if ($books->isEmpty())
                <p class="text-gray-600">Belum ada buku di database.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($books as $book)
                        <li class="py-4 flex items-start gap-4">
                            <!-- Cover Buku -->
                            <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}"
                            alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded-md shadow">

                            <div>
                                <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                                <p>Total dalam Wishlist: {{ $book->wishlists_count }} pengguna</p>
                                <p><strong>Ditambahkan oleh:</strong></p>
                                <ul class="ml-4 list-disc">
                                    @foreach ($book->wishlists as $wishlist)
                                        <li>{{ $wishlist->user->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
