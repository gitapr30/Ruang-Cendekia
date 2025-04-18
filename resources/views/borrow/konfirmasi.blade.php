@extends('layouts.main')

@section('contentAdmin')
<!-- Tabel Daftar Peminjaman Buku -->
<!-- Judul section daftar peminjaman yang menunggu konfirmasi -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Menunggu Konfirmasi</h2>
<!-- Tabel untuk menampilkan data peminjaman -->
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
            <!-- Kolom-kolom header tabel -->
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- Looping data peminjaman dari variabel $borrows -->
        @foreach ($borrows as $borrow)
        <tr>
            <!-- Nomor urut menggunakan $loop->iteration -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
            <!-- Nama pengguna yang meminjam -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            <!-- Judul buku yang dipinjam -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            <!-- Tanggal pinjam dengan format d-m-Y -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <!-- Tanggal kembali dengan format d-m-Y -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <!-- Status peminjaman (diubah huruf pertama menjadi kapital) -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
            </td>
            <!-- Kolom aksi berdasarkan status peminjaman -->
            <td class="px-4 py-2 text-sm text-gray-600">
                @if($borrow->status === 'menunggu konfirmasi')
                <!-- Form untuk mengkonfirmasi peminjaman -->
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
                @elseif($borrow->status === 'dipinjam')
                <!-- Form untuk mengkonfirmasi pengembalian buku -->
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

@extends('layouts.main')

@section('contentPustakawan')
<!-- Tabel Daftar Peminjaman Buku untuk Pustakawan -->
<!-- Judul section daftar peminjaman yang menunggu konfirmasi -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Menunggu Konfirmasi</h2>
<!-- Tabel untuk menampilkan data peminjaman -->
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
            <!-- Kolom-kolom header tabel -->
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- Looping data peminjaman dari variabel $borrows -->
        @foreach ($borrows as $borrow)
        <tr>
            <!-- Nomor urut menggunakan $loop->iteration -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
            <!-- Nama pengguna yang meminjam -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->user->name }}</td>
            <!-- Judul buku yang dipinjam -->
            <td class="px-4 py-2 text-sm text-gray-600">{{ $borrow->book->title }}</td>
            <!-- Tanggal pinjam dengan format d-m-Y -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <!-- Tanggal kembali dengan format d-m-Y -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <!-- Status peminjaman (diubah huruf pertama menjadi kapital) -->
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
            </td>
            <!-- Kolom aksi berdasarkan status peminjaman -->
            <td class="px-4 py-2 text-sm text-gray-600">
                @if($borrow->status === 'menunggu konfirmasi')
                <!-- Form untuk mengkonfirmasi peminjaman -->
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
                @elseif($borrow->status === 'dipinjam')
                <!-- Form untuk mengkonfirmasi pengembalian buku -->
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
