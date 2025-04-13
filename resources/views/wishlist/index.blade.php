@extends('layouts.main')

@section('content')
<div class="container mx-auto mt-8">
    <!-- Judul halaman wishlist -->
    <h2 class="font-bold text-gray-800 mb-4 ml-7" style="font-size: 21px;">Wishlist Buku</h2>

    <!-- Notifikasi pop-up untuk pesan sukses -->
    @if (session('success'))
        <div id="popup-message" class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>

        <script>
            // Menghilangkan popup notifikasi setelah 3 detik
            setTimeout(function () {
                document.getElementById("popup-message").style.display = "none";
            }, 3000);
        </script>
    @endif

    <!-- Notifikasi pop-up untuk pesan error -->
    @if (session('error'))
        <div id="popup-message" class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>

        <script>
            // Menghilangkan popup notifikasi setelah 3 detik
            setTimeout(function () {
                document.getElementById("popup-message").style.display = "none";
            }, 3000);
        </script>
    @endif

    <!-- Kondisi jika wishlist kosong -->
    @if ($wishlistBooks->isEmpty())
        <p class="text-gray-600">Belum ada buku di wishlist.</p>
    @else
        <!-- Grid untuk menampilkan daftar buku dalam wishlist -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 ml-7">
            <!-- Loop untuk setiap buku dalam wishlist -->
            @foreach ($wishlistBooks as $book)
                <div class="w-full bg-white rounded-lg p-4 shadow-lg flex flex-col">
                    <div class="flex">
                        <!-- Bagian gambar buku -->
                        <div class="w-1/3">
                            <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}" alt="{{ $book->title }}" class="w-full h-40 object-cover rounded-lg shadow-md">
                        </div>

                        <!-- Bagian detail buku -->
                        <div class="w-2/3 pl-4 flex flex-col justify-between">
                            <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                            <p class="text-gray-600 mt-4">Stok: {{ $book->stok }}</p>
                            <p class="text-gray-600">Penulis: {{ $book->penulis }}</p>
                            <p class="text-gray-600">Tahun Terbit : {{ $book->thn_terbit }}</p>
                            <p class="text-gray-600">Kode Buku: {{ $book->kode_buku }}</p>
                            <p class="text-gray-600">Penerbit: {{ $book->penerbit }}</p>

                            <!-- Tombol Hapus buku dari wishlist -->
                            <form action="{{ route('wishlist.destroy', $book->id) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition duration-300">
                                    Hapus dari Wishlist
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
