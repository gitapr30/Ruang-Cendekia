@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<div class="p-4">
    <h1 class="text-lg font-semibold text-gray-800 mb-3">Peminjaman Buku</h1>

    <div class="grid grid-cols-4 gap-6">
        <!-- Bagian Kiri: Informasi Buku -->
        <div class="flex flex-col items-center sticky top-0">
            <img src="{{ asset('' . $book->image) }}" alt="{{ $book->title }}"
                class="w-full object-cover rounded-lg shadow-lg">
        </div>

        <!-- Bagian Kanan: Formulir Peminjaman dan Tabel -->
        <div class="col-span-3">
            <h1 class="text-3xl font-semibold text-slate-800">{{ $book->title }}</h1>
            <p class="text-slate-700 mt-2 text-sm font-medium">Jumlah Buku: {{ $book->stok }}</p>
            <h2 class="text-base font-medium mt-2 text-slate-700">
                {{ $book->penulis }}
            </h2>

            <!-- Rating Bintang -->
            <div class="flex items-center mt-2">
                <i data-feather="star" class="w-4 h-4 text-yellow-400"></i>
                <i data-feather="star" class="w-4 h-4 text-yellow-400"></i>
                <i data-feather="star" class="w-4 h-4 text-yellow-400"></i>
                <i data-feather="star" class="w-4 h-4 text-yellow-400"></i>
                <i data-feather="star" class="w-4 h-4 text-gray-300"></i>
            </div>

            <!-- Tabel Informasi Buku -->
            <div class="mt-6 bg-white p-4 rounded-lg shadow-lg border-2 border-slate-300">
                <table class="w-full table-auto border-collapse">
                    <tbody>
                        <tr class="border-b-2">
                            <td class="text-[12px] font-medium text-slate-700 py-2 px-4 border-r-2">Penerbit</td>
                            <td class="text-[12px] text-slate-600 py-2 px-4"> {{ $book->penerbit }} </td>
                        </tr>
                        <tr class="border-b-2">
                            <td class="text-[12px] font-medium text-slate-700 py-2 px-4 border-r-2">Tahun Terbit</td>
                            <td class="text-[12px] text-slate-600 py-2 px-4">{{ $book->thn_terbit }}</td>
                        </tr>
                        <tr class="border-b-2">
                            <td class="text-[12px] font-medium text-slate-700 py-2 px-4 border-r-2">Kode Buku</td>
                            <td class="text-[12px] text-slate-600 py-2 px-4">{{ $book->kode_buku }}</td>
                        </tr>
                        <tr class="border-b-2">
                            <td class="text-[12px] font-medium text-slate-700 py-2 px-4 border-r-2">Stok</td>
                            <td class="text-[12px] text-slate-600 py-2 px-4">{{ $book->stok }}</td>
                        </tr>
                        <tr>
                            <td class="text-[12px] font-medium text-slate-700 py-2 px-4 border-r-2">Dibuat pada</td>
                            <td class="text-[12px] text-slate-600 py-2 px-4">{{ $book->created_at->format('d M Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Deskripsi Buku -->
            <h1 class="text-2xl font-semibold text-black mt-6">Deskripsi</h1>
            <p class="text-base text-slate-700 mt-6">{{ $book->description }}</p>
        </div>

    </div>
    <!-- Wishlist and Pinjam Buttons Side by Side -->
    <div class="flex space-x-4 mt-6">
        <!-- Pinjam Button -->
        <form action="{{ route('borrow.index') }}" method="post" class="w-full">
            @csrf
            <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
            <input type="text" name="book_id" value="{{ $book->id }}" hidden>
            <input type="text" name="kode_peminjaman" value="{{ date('d') . auth()->user()->id . $book->kode_buku }}" hidden>
            <button type="submit"
                class="w-full transition-all duration-500 enabled:bg-gradient-to-br enabled:from-blue-400 enabled:to-blue-600 rounded-lg text-white font-medium p-4 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm shadow-lg hover:shadow-xl shadow-blue-200 hover:shadow-blue-200 focus:shadow-none disabled:shadow-none disabled:bg-slate-700 disabled:cursor-not-allowed"
                @if ($book->stok == 0)
                @disabled(true)
                @elseif ($book->borrow->isNotEmpty())
                @foreach ($book->borrow->where('status', 'meminjam') as $borrow)
                @if ($book->stok == 0 || $borrow->user_id == auth()->user()->id)
                @disabled(true)
                @endif
                @endforeach
                @endif>Pinjam
            </button>
        </form>
        <!-- Wishlist Button -->
        <form action="{{ route('wishlist.store', $book->slug) }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="suka" value="liked">
            <button type="submit"
                class="w-full flex items-center justify-center bg-gradient-to-br from-green-400 to-green-600 text-white font-medium p-4 rounded-lg text-sm shadow-lg hover:shadow-xl shadow-green-200 hover:shadow-green-200 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-500">
                <i data-feather="bookmark" class="w-5 h-5 mr-2"></i>
                Simpan ke Wishlist
            </button>
        </form>
    </div>

    <!-- Tampilkan Ulasan dan Rating -->
    <div class="mt-6">
        <h2 class="text-2xl font-semibold text-slate-800 mb-4">Ulasan dan Rating</h2>

        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex flex-col md:flex-row md:justify-between">
            <!-- Bagian Kiri: Rata-rata Rating dan Total Ulasan -->
            <div class="mb-4 md:mb-0 md:w-1/2">
                <p class="text-black font-bold text-2xl ml-2">4.2 / 5</p>
                <div class="flex items-center mt-1 space-x-1">
                    <!-- Bintang Penuh -->
                    <span class="text-yellow-500 text-xl">⭐</span>
                    <span class="text-yellow-500 text-xl">⭐</span>
                    <span class="text-yellow-500 text-xl">⭐</span>
                    <span class="text-yellow-500 text-xl">⭐</span>
                    <!-- Bintang Setengah -->
                    <span class="text-yellow-500 text-xl opacity-70">⭐</span>
                </div>
                <p class="text-gray-700 font-medium mt-1">(125)</p>
            </div>

            <!-- Bagian Kanan: Distribusi Rating -->
            <div class="md:w-1/2">
                <p class="text-gray-700 font-semibold mb-3">Distribusi Rating</p>

                <div class="space-y-2">
                    <!-- 5 Stars -->
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium flex items-center">
                            ⭐ <span class="ml-1">5</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full" style="width: 70%; background-color: #197BBA;"></div>
                        </div>
                        <span class="text-gray-700 font-medium">70%</span>
                    </div>

                    <!-- 4 Stars -->
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium flex items-center">
                            ⭐ <span class="ml-1">4</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full" style="width: 20%; background-color: #197BBA;"></div>
                        </div>
                        <span class="text-gray-700 font-medium">20%</span>
                    </div>

                    <!-- 3 Stars -->
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium flex items-center">
                            ⭐ <span class="ml-1">3</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full" style="width: 5%; background-color: #197BBA;"></div>
                        </div>
                        <span class="text-gray-700 font-medium">5%</span>
                    </div>

                    <!-- 2 Stars -->
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium flex items-center">
                            ⭐ <span class="ml-1">2</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full" style="width: 3%; background-color: #197BBA;"></div>
                        </div>
                        <span class="text-gray-700 font-medium">3%</span>
                    </div>

                    <!-- 1 Star -->
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium flex items-center">
                            ⭐ <span class="ml-1">1</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full" style="width: 2%; background-color: #197BBA;"></div>
                        </div>
                        <span class="text-gray-700 font-medium">2%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir Ulasan Baru -->
    <div class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-800 mb-5">Tulis Ulasan Anda</h3>

        <!-- Tambahkan rating statis (misalnya default 4 dari 5 bintang) -->
        <div class="flex text-yellow-400 mb-4">
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span class="text-gray-300">★</span> <!-- Bintang ke-5 abu-abu -->
        </div>
        <p class="text-gray-600 text-sm mb-4">Rating Anda: <strong>4/5</strong></p>

        <form action="{{ route('review.store') }}" method="POST" id="reviewForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="review" class="block text-gray-700 font-medium mb-1">Ulasan</label>
                    <textarea name="review" id="review" rows="4"
                        maxlength="150"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all shadow-sm"
                        placeholder="Tulis ulasan tentang buku ini..." required></textarea>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 150 karakter</p>
                </div>
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-500 text-white w-full font-semibold px-5 py-2.5 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all"
                        id="submitButton" disabled>
                        Kirim Ulasan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const reviewInput = document.getElementById('review');
        const submitButton = document.getElementById('submitButton');

        // Event listener to check the length of the review
        reviewInput.addEventListener('input', function() {
            if (reviewInput.value.length > 150) {
                reviewInput.setCustomValidity('Teks ulasan tidak boleh melebihi 150 karakter.');
            } else {
                reviewInput.setCustomValidity('');
            }

            // Enable the submit button only if the input length is valid (<= 150 characters)
            if (reviewInput.value.length <= 150 && reviewInput.value.trim() !== '') {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        });
    </script>

    <style>
        /* Ensure the swiper container does not overflow */
        .swiper-container {
            width: 100%;
            overflow: hidden;
        }

        /* Ensure slides fit within the container */
        .swiper-wrapper {
            display: flex;
            align-items: center;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            /* Center the content */
            width: 100%;
            /* Ensure each slide takes full width */
        }

        /* Prevent body from overflowing */
        body {
            overflow-x: hidden;
        }
    </style>

    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach($reviews as $review)
            <div class="swiper-slide">
                <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md mx-auto mt-6">
                    <div class="text-gray-700">
                        <div class="flex text-yellow-400 mb-2">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span class="text-gray-300">★</span> <!-- Bintang ke-5 abu-abu -->
                        </div>
                        <p class="text-lg italic">"{{ $review->review }}"</p>
                        <p class="mt-4 font-medium text-right text-blue-600">- {{ $review->user->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper('.swiper-container', {
                loop: true,
                spaceBetween: 20,
                slidesPerView: 3,
                autoplay: {
                    delay: 3000, // Adjust time (milliseconds) between slides
                    disableOnInteraction: false, // Keeps autoplay running after user interaction
                },
            });
        });
    </script>
</div>

<!-- Include Swiper JS -->

</div>
</div>
@endsection
