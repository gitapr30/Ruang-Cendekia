@extends('layouts.main')

@section('contentPustakawan')
<!-- Tabel Daftar Peminjaman Buku -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Denda Peminjaman</h2>

<!-- Info tarif denda -->
<div class="bg-blue-50 p-4 rounded-md mb-4 mx-6">
    <h3 class="font-medium text-blue-800">Tarif Denda Saat Ini:</h3>
    <p class="text-blue-700">- Keterlambatan: Rp {{ number_format($dendaPerHari, 0, ',', '.') }},- per hari</p>
    <p class="text-blue-700">- Buku Hilang: Rp {{ number_format($dendaHilang, 0, ',', '.') }},-</p>
    <p class="text-blue-700">- Buku Rusak: Rp {{ number_format($dendaRusak, 0, ',', '.') }},-</p>
</div>

@if($borrows->isEmpty())
<div class="bg-yellow-50 p-4 rounded-md mb-4 mx-6">
    <p class="text-yellow-700">Tidak ada data peminjaman yang melewati tanggal kembali.</p>
</div>
@else
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
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Denda</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($borrows as $borrow)
        @php
            $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
            $lateDays = max($dueDate->diffInDays(now()), 0);
            $dendaTerlambat = $lateDays * $dendaPerHari;
        @endphp
        <tr>
            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                @if($borrow->keterangan == 'terlambat')
                    Terlambat
                @elseif($borrow->keterangan == 'hilang')
                    Hilang
                @elseif($borrow->keterangan == 'rusak')
                    Rusak
                @else
                    {{ ucfirst($borrow->status) }}
                @endif
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                <form action="{{ route('borrow.updateDenda') }}" method="POST">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">

                    <select name="keterangan" class="border p-1 bg-gray-100 text-gray-800 rounded" onchange="this.form.submit()">
                        <option value="">Pilih</option>
                        <option value="terlambat" {{ $borrow->keterangan == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="hilang" {{ $borrow->keterangan == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        <option value="rusak" {{ $borrow->keterangan == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </form>
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                <form action="{{ route('borrow.updateFromDenda') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="{{ $borrow->keterangan ?? 'terlambat' }}">
                    <input type="hidden" name="keterangan" value="{{ $borrow->keterangan ?? 'terlambat' }}">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Kembalikan
                    </button>
                </form>
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                @if($borrow->keterangan == 'terlambat')
                    Rp {{ number_format($dendaTerlambat, 0, ',', '.') }},-
                @elseif($borrow->keterangan == 'hilang')
                    Rp {{ number_format($dendaHilang, 0, ',', '.') }},-
                @elseif($borrow->keterangan == 'rusak')
                    Rp {{ number_format($dendaRusak, 0, ',', '.') }},-
                @else
                    Rp 0,-
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
