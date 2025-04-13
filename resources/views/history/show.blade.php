@extends('layouts.app')

@section('content')
    {{-- Main container for history page --}}
    <div class="container mx-auto px-4 py-8">
        {{-- Page title --}}
        <h1 class="text-2xl font-bold mb-6">History Peminjaman</h1>

        {{-- Check if history data is empty --}}
        @if($history->isEmpty())
            {{-- Warning message when no history exists --}}
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                <p>Anda belum memiliki riwayat peminjaman.</p>
            </div>
        @else
            {{-- Table container with shadow and rounded corners --}}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                {{-- Table showing borrowing history --}}
                <table class="min-w-full">
                    {{-- Table header --}}
                    <thead class="bg-gray-100">
                        <tr>
                            {{-- Column headers --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                        </tr>
                    </thead>
                    {{-- Table body --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Loop through each history item --}}
                        @foreach($history as $item)
                        <tr>
                            {{-- Row number --}}
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            {{-- Book title --}}
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->book->title }}</td>
                            {{-- Borrow date formatted --}}
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                            {{-- Return date (if exists) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y') : '-' }}
                            </td>
                            {{-- Status with conditional styling --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $item->keterangan === 'dikembalikan' ? 'bg-green-100 text-green-800' :
                                       ($item->keterangan === 'dipinjam' ? 'bg-blue-100 text-blue-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            {{-- Fine amount (if exists) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->denda > 0)
                                    Rp {{ number_format($item->denda * 1000, 0, ',', '.') }},-
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination links --}}
            <div class="mt-4">
                {{ $history->links() }}
            </div>
        @endif
    </div>
@endsection
