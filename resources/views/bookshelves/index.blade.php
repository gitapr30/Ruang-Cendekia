@extends('layouts.main') {{-- Menggunakan layout utama --}}

@section('contentAdmin') {{-- Section untuk konten admin --}}
<div class="p-4">
    {{-- Header dengan judul dan tombol tambah --}}
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 mb-3">Daftar Rak Buku</h1> {{-- Judul halaman --}}
        {{-- Tombol untuk menambah rak buku baru --}}
        <a href="{{ route('bookshelves.create') }}"
    class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
    focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm">
    Tambah Rak Buku
</a>
    </div>
    <div class="mt-6">
        {{-- Tabel daftar rak buku --}}
        <div class="overflow-auto rounded-lg shadow block w-full">
            <table class="table-auto w-full">
                {{-- Header tabel --}}
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th> {{-- Kolom nomor --}}
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Rak</th> {{-- Kolom nama rak --}}
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Baris</th> {{-- Kolom baris --}}
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Kategori</th> {{-- Kolom kategori --}}
                        <th class="w-24 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th> {{-- Kolom aksi --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- Pesan alert jika ada --}}
                    @if(isset($message))
                    <div class="alert alert-warning">
                        {{ $message }}
                    </div>
                @endif
                   {{-- Loop untuk menampilkan data rak buku --}}
                   @foreach ($bookshelves as $bookshelf)
<tr>
    {{-- Nomor urut --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $loop->iteration }}</td>
    {{-- Nama rak --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->rak }}</td>
    {{-- Nomor baris --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->baris }}</td>
    {{-- Nama kategori dengan fallback jika tidak ada --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->category->name ?? 'Tidak ada kategori' }}</td>

                        {{-- Kolom aksi --}}
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap flex space-x-2">
                            <!-- Tombol Detail -->
                            <a href="{{ route('bookshelves.show', $bookshelf->id) }}"
                               class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 no-underline"
                               title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                            <!-- Tombol Edit -->
                            <a href="{{ route('bookshelves.edit', $bookshelf->id) }}"
                               class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 no-underline"
                               title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('bookshelves.destroy', $bookshelf->id) }}" method="POST" class="inline">
                                @csrf {{-- CSRF token --}}
                                @method('DELETE') {{-- Method spoofing untuk DELETE --}}
                                <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                        title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus perubahan ini?');"> {{-- Konfirmasi sebelum hapus --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main') {{-- Menggunakan layout utama --}}

@section('contentPustakawan') {{-- Section untuk konten admin --}}
<div class="p-4">
    {{-- Header dengan judul dan tombol tambah --}}
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 mb-3">Daftar Rak Buku</h1> {{-- Judul halaman --}}
        {{-- Tombol untuk menambah rak buku baru --}}
        <a href="{{ route('bookshelves.create') }}"
    class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
    focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm">
    Tambah Rak Buku
</a>
    </div>
    <div class="mt-6">
        {{-- Tabel daftar rak buku --}}
        <div class="overflow-auto rounded-lg shadow block w-full">
            <table class="table-auto w-full">
                {{-- Header tabel --}}
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th> {{-- Kolom nomor --}}
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Rak</th> {{-- Kolom nama rak --}}
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Baris</th> {{-- Kolom baris --}}
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Kategori</th> {{-- Kolom kategori --}}
                        <th class="w-24 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th> {{-- Kolom aksi --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- Pesan alert jika ada --}}
                    @if(isset($message))
                    <div class="alert alert-warning">
                        {{ $message }}
                    </div>
                @endif
                   {{-- Loop untuk menampilkan data rak buku --}}
                   @foreach ($bookshelves as $bookshelf)
<tr>
    {{-- Nomor urut --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $loop->iteration }}</td>
    {{-- Nama rak --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->rak }}</td>
    {{-- Nomor baris --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->baris }}</td>
    {{-- Nama kategori dengan fallback jika tidak ada --}}
    <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $bookshelf->category->name ?? 'Tidak ada kategori' }}</td>

                        {{-- Kolom aksi --}}
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap flex space-x-2">
                            <!-- Tombol Detail -->
                            <a href="{{ route('bookshelves.show', $bookshelf->id) }}"
                               class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 no-underline"
                               title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                            <!-- Tombol Edit -->
                            <a href="{{ route('bookshelves.edit', $bookshelf->id) }}"
                               class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 no-underline"
                               title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('bookshelves.destroy', $bookshelf->id) }}" method="POST" class="inline">
                                @csrf {{-- CSRF token --}}
                                @method('DELETE') {{-- Method spoofing untuk DELETE --}}
                                <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                        title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus perubahan ini?');"> {{-- Konfirmasi sebelum hapus --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
