@extends('layouts.main') <!-- Menggunakan layout utama -->

@section('contentAdmin')
    <!-- Konten untuk Admin -->
    <div class="container mx-auto mt-8">
        <!-- Judul halaman -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4 ml-7">Data Wishlist</h1>

        <!-- Subjudul untuk statistik wishlist -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2 ml-7">Jumlah Wishlist per Buku</h2>

        <!-- Container untuk daftar buku -->
        <div class="book-list bg-white shadow-md p-4 rounded-lg ml-5">
            <!-- Pengecekan jika tidak ada buku -->
            @if ($books->isEmpty())
                <p class="text-gray-600">Belum ada buku di database.</p>
            @else
                <!-- List buku dengan wishlist -->
                <ul class="divide-y divide-gray-200">
                    <!-- Loop untuk setiap buku -->
                    @foreach ($books as $book)
                        <li class="py-4 flex items-start gap-4">
                            <!-- Gambar cover buku -->
                            <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}"
                            alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded-md shadow">

                            <!-- Detail buku -->
                            <div>
                                <!-- Judul buku -->
                                <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                                <!-- Jumlah wishlist -->
                                <p>Total dalam Wishlist: {{ $book->wishlists_count }} pengguna</p>
                                <!-- Label untuk daftar pengguna -->
                                <p><strong>Ditambahkan oleh:</strong></p>
                                <!-- List pengguna yang menambahkan ke wishlist -->
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


@section('contentPustakawan')
    <!-- Konten untuk Pustakawan (mirip dengan admin) -->
    <div class="container mx-auto mt-8">
        <!-- Judul halaman -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4 ml-7">Data Wishlist</h1>

        <!-- Subjudul untuk statistik wishlist -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2 ml-7">Jumlah Wishlist per Buku</h2>

        <!-- Container untuk daftar buku -->
        <div class="book-list bg-white shadow-md p-4 rounded-lg ml-5">
            <!-- Pengecekan jika tidak ada buku -->
            @if ($books->isEmpty())
                <p class="text-gray-600">Belum ada buku di database.</p>
            @else
                <!-- List buku dengan wishlist -->
                <ul class="divide-y divide-gray-200">
                    <!-- Loop untuk setiap buku -->
                    @foreach ($books as $book)
                        <li class="py-4 flex items-start gap-4">
                            <!-- Gambar cover buku -->
                            <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}"
                            alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded-md shadow">

                            <!-- Detail buku -->
                            <div>
                                <!-- Judul buku -->
                                <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                                <!-- Jumlah wishlist -->
                                <p>Total dalam Wishlist: {{ $book->wishlists_count }} pengguna</p>
                                <!-- Label untuk daftar pengguna -->
                                <p><strong>Ditambahkan oleh:</strong></p>
                                <!-- List pengguna yang menambahkan ke wishlist -->
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
