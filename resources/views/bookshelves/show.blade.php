@extends('layouts.main')

@section('contentAdmin')
<div class="p-4">
    {{-- Header dan tombol aksi --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Rak Buku</h1>
        <div>
            {{-- Tombol kembali ke daftar rak --}}
            <a href="{{ route('bookshelves.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">
                Kembali ke Daftar Rak
            </a>
            {{-- Tombol tambah buku baru ke rak ini --}}
            <a href="{{ route('books.create', ['rak_id' => $bookshelf->id]) }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                + Tambah Buku ke Rak Ini
            </a>
        </div>
    </div>

    {{-- Bagian informasi detail rak buku --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Rak</h2>

        {{-- Grid untuk menampilkan informasi rak --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Nomor rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Nomor Rak</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->rak ?? 'Belum diisi' }}
                </p>
            </div>
            {{-- Baris rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Baris</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->baris ?? 'Belum diisi' }}
                </p>
            </div>
            {{-- Kategori rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Kategori</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->category->name ?? 'Tidak ada kategori' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Bagian daftar buku dalam rak --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- Header dan fitur pencarian --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Daftar Buku di Rak Ini</h2>
            <div class="flex items-center">
                {{-- Badge total buku --}}
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mr-3">
                    Total: {{ $books->total() }} Buku
                </span>
                {{-- Form pencarian buku --}}
                <form action="" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari buku..."
                           class="px-3 py-1 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="bg-blue-500 text-white px-3 py-1 rounded-r-md hover:bg-blue-600">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        {{-- Kondisi jika tidak ada buku di rak --}}
        @if($books->isEmpty())
            <div class="text-center py-8">
                {{-- Ikon buku kosong --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="mt-2 text-gray-500">Tidak ada buku di rak ini</p>
                {{-- Tombol tambah buku --}}
                <a href="{{ route('books.create', ['rak_id' => $bookshelf->id]) }}"
                   class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Tambah Buku ke Rak Ini
                </a>
            </div>
        @else
            {{-- Tabel daftar buku --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengarang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Loop data buku --}}
                        @foreach($books as $book)
                        <tr>
                            {{-- Kolom cover buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}"
                                     alt="{{ $book->title }}"
                                     class="h-12 w-12 object-cover rounded">
                            </td>
                            {{-- Kolom judul dan kode buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('books.show', $book->slug) }}" class="hover:text-blue-600">
                                        {{ $book->title }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">{{ $book->kode_buku }}</div>
                            </td>
                            {{-- Kolom kategori buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $book->category->name ?? '-' }}</div>
                            </td>
                            {{-- Kolom pengarang buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $book->penulis ?? '-' }}</div>
                            </td>
                            {{-- Kolom status ketersediaan buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($book->borrows->where('status', '!=', 'returned')->count() == 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                            {{-- Kolom aksi (edit dan hapus) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('books.edit', $book->slug) }}" type="submit"
                                   class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.main')

@section('contentPustakawan')
<div class="p-4">
    {{-- Header dan tombol aksi --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Rak Buku</h1>
        <div>
            {{-- Tombol kembali ke daftar rak --}}
            <a href="{{ route('bookshelves.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">
                Kembali ke Daftar Rak
            </a>
            {{-- Tombol tambah buku baru ke rak ini --}}
            <a href="{{ route('books.create', ['rak_id' => $bookshelf->id]) }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                + Tambah Buku ke Rak Ini
            </a>
        </div>
    </div>

    {{-- Bagian informasi detail rak buku --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Rak</h2>

        {{-- Grid untuk menampilkan informasi rak --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Nomor rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Nomor Rak</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->rak ?? 'Belum diisi' }}
                </p>
            </div>
            {{-- Baris rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Baris</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->baris ?? 'Belum diisi' }}
                </p>
            </div>
            {{-- Kategori rak --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Kategori</p>
                <p class="text-lg font-semibold">
                    {{ $bookshelf->category->name ?? 'Tidak ada kategori' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Bagian daftar buku dalam rak --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- Header dan fitur pencarian --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Daftar Buku di Rak Ini</h2>
            <div class="flex items-center">
                {{-- Badge total buku --}}
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mr-3">
                    Total: {{ $books->total() }} Buku
                </span>
                {{-- Form pencarian buku --}}
                <form action="" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari buku..."
                           class="px-3 py-1 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="bg-blue-500 text-white px-3 py-1 rounded-r-md hover:bg-blue-600">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        {{-- Kondisi jika tidak ada buku di rak --}}
        @if($books->isEmpty())
            <div class="text-center py-8">
                {{-- Ikon buku kosong --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="mt-2 text-gray-500">Tidak ada buku di rak ini</p>
                {{-- Tombol tambah buku --}}
                <a href="{{ route('books.create', ['rak_id' => $bookshelf->id]) }}"
                   class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Tambah Buku ke Rak Ini
                </a>
            </div>
        @else
            {{-- Tabel daftar buku --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengarang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Loop data buku --}}
                        @foreach($books as $book)
                        <tr>
                            {{-- Kolom cover buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}"
                                     alt="{{ $book->title }}"
                                     class="h-12 w-12 object-cover rounded">
                            </td>
                            {{-- Kolom judul dan kode buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('books.show', $book->slug) }}" class="hover:text-blue-600">
                                        {{ $book->title }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">{{ $book->kode_buku }}</div>
                            </td>
                            {{-- Kolom kategori buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $book->category->name ?? '-' }}</div>
                            </td>
                            {{-- Kolom pengarang buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $book->penulis ?? '-' }}</div>
                            </td>
                            {{-- Kolom status ketersediaan buku --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($book->borrows->where('status', '!=', 'returned')->count() == 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                            {{-- Kolom aksi (edit dan hapus) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('books.edit', $book->slug) }}" type="submit"
                                   class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
