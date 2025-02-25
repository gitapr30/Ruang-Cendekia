@extends('layouts.main')

@section('content')
<div class="container">
    <h3 class="font-semibold text-slate-800 mb-4" style="font-size: 25px; margin-top: 40px; margin-left: 40px;">Riwayat Peminjaman</h3>

    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6">
        @foreach ($history as $item)
        @php
            // Tentukan warna berdasarkan status
            $bgColor = match($item->status) {
                'menunggu konfirmasi' => 'bg-yellow-300',
                'dipinjam' => 'bg-green-300',
                'dikembalikan' => 'bg-blue-300',
                default => 'bg-red-300'
            };
        @endphp

        <div class="w-full bg-white rounded-lg p-4 shadow-lg flex mx-auto" style=" width: 700px;">
            <!-- Gambar Buku -->
            <div class="w-1/3">
                <img src="{{ asset($item->book->image) }}" alt="{{ $item->book->title }}" class="w-40 h-60 object-cover rounded-lg shadow-md">
            </div>

            <!-- Detail Peminjaman -->
            <div class="w-2/3 pl-4 flex flex-col justify-between">
                <h5 class="text-xl font-semibold text-slate-800">{{ $item->book->title }}</h5>
                <p class="text-sm text-slate-700">
                    <strong>Status:</strong>
                    <span class="px-2 py-1 text-white rounded-lg {{ $bgColor }}">
                        {{ $item->status }}
                    </span>
                </p>
                <p class="text-sm text-slate-700"><strong>Tanggal Peminjaman:</strong> {{ $item->tanggal_pinjam->format('d M Y') }}</p>
                <p class="text-sm text-slate-700"><strong>Tanggal Pengembalian:</strong> {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y') : 'Not Returned Yet' }}</p>
                <p class="text-sm text-slate-700"><strong>Denda:</strong> {{ $item->denda ? 'Rp ' . number_format($item->denda, 2) : 'Tidak Ada Denda' }}</p>
                <p class="text-sm text-slate-700"><strong>Kode:</strong> {{ $item->kode_peminjaman }}</p>
            </div>

            <!-- QR Code -->
            <!-- <div class="w-1/3 flex items-center justify-center">
                <img src="{{ asset($item->qr_code_path) }}" alt="QR Code {{ $item->kode_peminjaman }}" class="w-24 h-24">
            </div> -->
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="my-6 justify-center" style="margin-left: 20px;">
        {{ $history->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection
