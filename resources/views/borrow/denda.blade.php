@extends('layouts.main')

@section('contentPustakawan')
<!-- Header untuk tabel daftar denda -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Denda Peminjaman</h2>

<!-- Informasi tarif denda saat ini -->
<div class="bg-blue-50 p-4 rounded-md mb-4 mx-6">
    <h3 class="font-medium text-blue-800">Tarif Denda Saat Ini:</h3>
    <p class="text-blue-700">- Keterlambatan: Rp {{ number_format($dendaPerHari, 0, ',', '.') }},- per hari</p>
    <p class="text-blue-700">- Buku Hilang: Rp {{ number_format($dendaHilang, 0, ',', '.') }},-</p>
    <p class="text-blue-700">- Buku Rusak: Rp {{ number_format($dendaRusak, 0, ',', '.') }},-</p>
</div>

<!-- Kondisi jika tidak ada data peminjaman dengan denda -->
@if($borrows->isEmpty())
<div class="bg-yellow-50 p-4 rounded-md mb-4 mx-6">
    <p class="text-yellow-700">Tidak ada data peminjaman yang memiliki denda.</p>
</div>
@else
<!-- Tabel daftar peminjaman dengan denda -->
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Keterangan</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Denda</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- Loop data peminjaman -->
        @foreach ($borrows as $borrow)
        @php
            // Inisialisasi variabel denda dan keterangan
            $denda = 0;
            $keterangan = $borrow->keterangan;
            $status = $borrow->status;

            // Menentukan teks status berdasarkan keterangan ketika dikembalikan
            if ($status == 'dikembalikan') {
                $statusText = ucfirst($keterangan);
            } else {
                $statusText = ucfirst($status);
            }

            // Logika perhitungan denda
            if ($keterangan == 'terlambat') {
                // Hitung denda keterlambatan
                $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
                $returnDate = $borrow->tanggal_dikembalikan ? \Carbon\Carbon::parse($borrow->tanggal_dikembalikan) : now();
                $lateDays = max($dueDate->diffInDays($returnDate), 0);
                $denda = $lateDays * $dendaPerHari;
            } elseif ($keterangan == 'hilang') {
                // Denda untuk buku hilang
                $denda = $dendaHilang;
            } elseif ($keterangan == 'rusak') {
                // Denda untuk buku rusak
                $denda = $dendaRusak;
            }
        @endphp
        <tr>
            <!-- Nomor urut -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
            <!-- Nama peminjam -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            <!-- Judul buku -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            <!-- Tanggal pinjam -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <!-- Tanggal kembali -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <!-- Status peminjaman -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                {{ $statusText }}
            </td>
            <!-- Form untuk mengubah keterangan denda -->
            <td class="px-4 py-2 text-sm text-gray-600">
                <form action="{{ route('borrow.updateDenda') }}" method="POST">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">

                    <!-- Dropdown pilihan keterangan denda -->
                    <select name="keterangan" class="border p-1 bg-gray-100 text-gray-800 rounded" onchange="this.form.submit()" {{ $borrow->status == 'dikembalikan' ? 'disabled' : '' }}>
                        <option value="">Pilih</option>
                        <option value="terlambat" {{ $keterangan == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="hilang" {{ $keterangan == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        <option value="rusak" {{ $keterangan == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </form>
            </td>
            <!-- Jumlah denda -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                Rp {{ number_format($denda, 0, ',', '.') }},-
            </td>
            <!-- Tombol aksi -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                @if($borrow->status != 'dikembalikan')
                <!-- Form untuk mengubah status menjadi dikembalikan -->
                <form action="{{ route('borrow.updateFromDenda') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dikembalikan">
                    <input type="hidden" name="keterangan" value="{{ $keterangan }}">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Kembalikan
                    </button>
                </form>
                @else
                <!-- Tampilan jika buku sudah dikembalikan -->
                <span class="text-green-600">Sudah dikembalikan</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
