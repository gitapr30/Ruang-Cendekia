@extends('layouts.main')

@section('content')
<div class="p-4">
    <h1 class="text-lg font-semibold text-gray-800 mb-3">Peminjaman Buku</h1>
    <form action="{{ route('borrow.index') }}" method="post" class="w-full">
        @csrf

        <!-- Form Pemilihan User dan Buku -->
        <div>
            <label for="user_name" class="block text-sm font-medium text-gray-700">Nama User</label>
            <input type="text" id="user_name" name="user_name" 
                value="{{ old('username', request()->cookie('username') ?? auth()->user()->username) }}" 
                disabled
                class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
            <input type="hidden" name="user_id" value="{{ old('user_id', request()->cookie('user_id') ?? auth()->id()) }}">
        </div>

        @isset($book)
            <div>
                <label for="book_name" class="block text-sm font-medium text-gray-700">Nama Buku</label>
                <input type="text" id="book_name" name="book_name" 
                    value="{{ $book->title }}" disabled
                    class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                <input type="hidden" name="book_id" value="{{ $book->id }}">
            </div>
        @endisset

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required
                    class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
            </div>
            <div>
                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" required
                    class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
            </div>
        </div>

        <!-- <input type="hidden" name="status" value="meminjam"> -->

        <div>
        <button type="submit" class="transition-all duration-500 bg-gradient-to-br from-blue-400 to-blue-500 px-4 py-2 rounded-lg ml-2 font-medium text-sm text-white shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:shadow-none shadow-blue-100">
            Pinjam Buku
        </button>
        </div>
    </form>
</div>
@endsection

@section('contentAdmin')
<form action="{{ route('borrow.index') }}" method="post" class="space-y-4">
    <!-- Tabel Daftar Peminjaman Buku -->
    <h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3">Daftar Peminjaman Buku</h2>
    <table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
        <thead>
            <tr>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">User</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
                <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrows as $borrow)
            <tr>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">
                    {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-600">
                    {{ \Carbon\Carbon::parse($borrow->tanggal_kembl)->format('d-m-Y') }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ ucfirst($borrow->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
</div>
@endsection
