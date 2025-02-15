@extends('layouts.main')

@section('content')
<div class="p-4">
    <h1 class="text-lg font-semibold text-gray-800 mb-3">Peminjaman Buku</h1>
    <form action="{{ route('borrow.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- Pemilihan User -->
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700">Nama User</label>
            <input type="text" id="user_id_display"
                value="{{ auth()->user()->username ?? 'Guest' }}"
                disabled
                class="form-control bg-white border text-black sm:text-sm rounded-lg block w-full p-2.5">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            @error('user_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        @php
        $selectedBook = isset($books) && request('book_id') ? $books->where('id', request('book_id'))->first() : null;
    @endphp

    @if($selectedBook)
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Buku yang Dipilih</label>
            <input type="hidden" name="book_id" value="{{ $selectedBook ? $selectedBook->id : '' }}">
            <input type="text" value="{{ $selectedBook->title }}" disabled
                class="form-control bg-gray-200 border text-black sm:text-sm rounded-lg block w-full p-2.5">
        </div>
    @endif


        <!-- Input Tanggal -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam"
                    value="{{ old('tanggal_pinjam') }}"
                    required
                    class="form-control bg-white border text-black sm:text-sm rounded-lg block w-full p-2.5">
                @error('tanggal_pinjam')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali"
                    value="{{ old('tanggal_kembali') }}"
                    required
                    class="form-control bg-white border text-black sm:text-sm rounded-lg block w-full p-2.5">
                @error('tanggal_kembali')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4">
                Pinjam Buku
            </button>
        </div>

        @if(session('errorMessage'))
            <p class="text-red-500 text-sm mt-2">{{ session('errorMessage') }}</p>
        @endif
    </form>
</div>

@endsection
