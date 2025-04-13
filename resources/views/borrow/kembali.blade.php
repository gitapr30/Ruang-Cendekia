@extends('layouts.main')

{{-- Section untuk konten Admin --}}
@section('contentAdmin')
<!-- Tabel Daftar Peminjaman Buku untuk Admin -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Buku Sudah Kembali</h2>

{{-- Tabel untuk menampilkan data peminjaman --}}
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    {{-- Header tabel --}}
    <thead>
        <tr>
            {{-- Kolom nomor urut --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            {{-- Kolom nama pengguna --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            {{-- Kolom judul buku --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            {{-- Kolom tanggal pinjam --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            {{-- Kolom tanggal kembali --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            {{-- Kolom status peminjaman --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            {{-- Kolom aksi (saat ini di-comment) --}}
            {{-- <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th> --}}
        </tr>
    </thead>

    {{-- Body tabel --}}
    <tbody>
        {{-- Loop melalui data peminjaman --}}
        @foreach ($borrows as $borrow)
        <tr>
            {{-- Nomor urut --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
            {{-- Nama pengguna --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            {{-- Judul buku --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            {{-- Tanggal pinjam dengan format tertentu --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            {{-- Tanggal kembali dengan format tertentu --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            {{-- Status peminjaman (dengan kapitalisasi pertama) --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
            </td>
            {{-- Kolom aksi --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{-- Jika status menunggu konfirmasi, tampilkan tombol konfirmasi --}}
                @if($borrow->status === 'menunggu konfirmasi')
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
                {{-- Jika status dipinjam, tampilkan tombol kembalikan --}}
                @elseif($borrow->status === 'dipinjam')
                <form action="{{ route('borrow.update')}}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{$borrow->id}}">
                    <input type="hidden" name="status" value="dikembalikan">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Kembalikan
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

{{-- Section untuk konten Pustakawan --}}
@extends('layouts.main')

@section('contentPustakawan')
<!-- Tabel Daftar Peminjaman Buku untuk Pustakawan -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Buku Sudah Kembali</h2>

{{-- Tabel untuk menampilkan data peminjaman --}}
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    {{-- Header tabel --}}
    <thead>
        <tr>
            {{-- Kolom nomor urut --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            {{-- Kolom nama pengguna --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            {{-- Kolom judul buku --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            {{-- Kolom tanggal pinjam --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            {{-- Kolom tanggal kembali --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            {{-- Kolom status peminjaman --}}
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            {{-- Kolom aksi (saat ini di-comment) --}}
            {{-- <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th> --}}
        </tr>
    </thead>

    {{-- Body tabel --}}
    <tbody>
        {{-- Loop melalui data peminjaman --}}
        @foreach ($borrows as $borrow)
        <tr>
            {{-- Nomor urut --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
            {{-- Nama pengguna --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            {{-- Judul buku --}}
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            {{-- Tanggal pinjam dengan format tertentu --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            {{-- Tanggal kembali dengan format tertentu --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            {{-- Status peminjaman (dengan kapitalisasi pertama) --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
            </td>
            {{-- Kolom aksi --}}
            <td class="px-4 py-2 text-sm text-gray-600">
                {{-- Jika status menunggu konfirmasi, tampilkan tombol konfirmasi --}}
                @if($borrow->status === 'menunggu konfirmasi')
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
                {{-- Jika status dipinjam, tampilkan tombol kembalikan --}}
                @elseif($borrow->status === 'dipinjam')
                <form action="{{ route('borrow.update')}}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{$borrow->id}}">
                    <input type="hidden" name="status" value="dikembalikan">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Kembalikan
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
