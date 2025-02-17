@extends('layouts.main')

@section('contentAdmin')
<!-- Tabel Daftar Peminjaman Buku -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Buku Dipinjam</h2>
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
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
                    <form action="{{ route('borrow.return', $borrow->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Kembalikan</button>
                    </form>

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
<!-- Tabel Daftar Peminjaman Buku -->
<h2 class="text-lg font-semibold text-gray-800 mt-10 mb-3 ml-7">Daftar Buku Dipinjam</h2>
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5">
    <thead>
        <tr>
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
                    <form action="{{ route('borrow.return', $borrow->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Kembalikan</button>
                    </form>

                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

