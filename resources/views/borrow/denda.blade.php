@extends('layouts.main')

@section('contentAdmin')
<!-- Tabel Daftar Peminjaman Buku -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Denda Peminjaman</h2>
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Keterangan</th> <!-- Tambahan Kolom -->
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Denda</th> <!-- Fine column added here -->
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
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
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
                @if($borrow->status === 'menunggu konfirmasi')
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
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
            <td class="px-4 py-2 text-sm text-gray-600">
                <form action="{{ route('borrow.updateDenda') }}" method="POST">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                        <span class="border p-1 w-20 inline-block bg-gray-100 text-gray-800 rounded">
                            Rp {{ number_format($borrow->denda, 0, ',', '.') }}
                        </span>

                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

@extends('layouts.main')

@section('contentPustakawan')
<!-- Tabel Daftar Peminjaman Buku -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Denda Peminjaman</h2>

<!-- Info tarif denda -->
<div class="bg-blue-50 p-4 rounded-md mb-4 mx-6">
    <h3 class="font-medium text-blue-800">Tarif Denda Saat Ini:</h3>
    <p class="text-blue-700">Rp {{ number_format($dendaPerHari, 0, ',', '.') }} per hari keterlambatan</p>
</div>
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Buku</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Status</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Keterangan</th> <!-- Tambahan Kolom -->
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Aksi</th>
            <th class="px-4 py-2 text-sm font-medium text-gray-700">Denda</th> <!-- Fine column added here -->
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
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-600">
                {{ ucfirst($borrow->status) }}
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
                @if($borrow->status === 'menunggu konfirmasi')
                <form action="{{ route('borrow.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <input type="hidden" name="status" value="dipinjam">
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
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
            <td class="px-4 py-2 text-sm text-gray-600">
                <form action="{{ route('borrow.updateDenda') }}" method="POST">
                    @csrf
                    <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    <span class="border p-1 w-20 inline-block bg-gray-100 text-gray-800 rounded">
                        Rp {{ number_format($dendaPerHari, 0, ',', '.') }}
                    </span>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
