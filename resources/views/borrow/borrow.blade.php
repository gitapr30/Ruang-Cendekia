@extends('layouts.main')

@section('content')
    <div class="p-4">
        <h1 class="text-xl font-bold text-gray-800 mb-3">Peminjaman Buku</h1>
        <form action="{{ route('borrow.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <!-- Pemilihan User -->
            @php
                $selectedBook = isset($books) && request('book_id') ? $books->where('id', request('book_id'))->first() : null;
            @endphp

        <div class="flex gap-6">
        <img src="{{ asset($selectedBook->image ?? 'images/default-book.jpg') }}" alt="{{ $selectedBook->title }}" class="w-60 h-80 object-cover rounded-lg border-blue-400">

            <div class="flex-1">
                <h3 class="text-lg font-bold">{{ $selectedBook->title }}</h3>
                <p class="text-gray-600">ciptaan: <span class="font-semibold">{{ $selectedBook->penulis }}</span></p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-yellow-400">⭐⭐⭐⭐☆</span>
                    <span class="text-gray-700">4.5</span>
                </div>
                <p class="mt-1 text-gray-600">Stok: <span class="font-semibold">{{ $selectedBook->stok }}</span></p>

                <table class="mt-4 text-sm w-full border border-gray-300">
                    <tr><td class="border p-2">Penerbit</td><td class="border p-2">{{ $selectedBook->penerbit }}</td></tr>
                    <tr><td class="border p-2">Tahun Terbit</td><td class="border p-2">{{ $selectedBook->thn_terbit }}</td></tr>
                    <tr><td class="border p-2">Nomor Buku</td><td class="border p-2">{{ $selectedBook->kode_buku }}</td></tr>
                    <tr><td class="border p-2">Sisa Stok</td><td class="border p-2">{{ $selectedBook->stok }}</td></tr>
                    <tr><td class="border p-2">Tanggal Upload</td><td class="border p-2">{{ $selectedBook->created_at }}</td></tr>
                </table>
            </div>
        </div>

            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700" style="margin-top: 15px;">Nama
                    User</label>
                <input type="text" id="user_id_display" value="{{ auth()->user()->username ?? 'Guest' }}" disabled
                    class="form-control bg-white border text-black sm:text-sm rounded-lg block w-full p-2.5">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @error('user_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>


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
                    <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700" style="margin-top: 20px;">
                        Tanggal Pinjam
                    </label>
                    <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" readonly
                        required
                        class="form-control bg-gray-100 border text-black sm:text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    @error('tanggal_pinjam')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700" style="margin-top: 20px;">
                        Tanggal Kembali
                    </label>
                    <input type="date" id="tanggal_kembali" name="tanggal_kembali"
                        value="{{ date('Y-m-d', strtotime('+5 days')) }}" readonly required
                        class="form-control bg-gray-100 border text-black sm:text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    @error('tanggal_kembali')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4" style="margin-top: 20px;">
                    Pinjam Buku
                </button>
            </div>
            @if(session('successMessage'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "{{ session('successMessage') }}",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('books.index') }}"; // Redirect ke halaman buku
                            }
                        });
                    });
                </script>
            @endif


            @if(session('errorMessage'))
                <p class="text-red-500 text-sm mt-2">{{ session('errorMessage') }}</p>
            @endif
        </form>
    </div>

@endsection
