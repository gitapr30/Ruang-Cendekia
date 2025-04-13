@extends('layouts.main')

{{-- Section untuk konten Admin --}}
@section('contentAdmin')
    <div class="p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-800 mb-3">Data Ulasan</h1>
        </div>
        <div class="mt-6">
            {{-- Tabel untuk menampilkan data ulasan --}}
            <div class="overflow-auto rounded-lg shadow block w-full mt-5 md:mt-0 md:col-span-2">
                <table class="table-auto w-full">
                    {{-- Header tabel --}}
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Judul</th>
                            <th class="w-64 p-3 text-sm font-semibold tracking-wide text-left">Ulasan</th>
                            <th class="w-64 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Body tabel --}}
                    <tbody class="divide-y divide-gray-200">
                        {{-- Pengecekan jika tidak ada review --}}
                        @if ($reviews->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <p class="text-sm p-5">Tidak terdapat review</p>
                                </td>
                            </tr>
                        @endif
                        {{-- Loop untuk menampilkan setiap review --}}
                        @foreach ($reviews as $review)
                            <tr>
                                {{-- Nomor urut --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                {{-- Nama user yang memberi review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->user->name }}
                                </td>
                                {{-- Judul buku yang di-review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->book->title }}
                                </td>
                                {{-- Isi review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->review }}
                                </td>
                                {{-- Tombol aksi untuk menghapus review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                            onclick="return confirm('Are you sure you want to delete this?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- Section untuk konten Pustakawan --}}
@section('contentPustakawan')
    <div class="p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-800 mb-3">Data Ulasan</h1>
        </div>
        <div class="mt-6">
            {{-- Tabel untuk menampilkan data ulasan --}}
            <div class="overflow-auto rounded-lg shadow block w-full mt-5 md:mt-0 md:col-span-2">
                <table class="table-auto w-full">
                    {{-- Header tabel --}}
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Username</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Judul</th>
                            <th class="w-64 p-3 text-sm font-semibold tracking-wide text-left">Ulasan</th>
                            <th class="w-64 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Body tabel --}}
                    <tbody class="divide-y divide-gray-200">
                        {{-- Pengecekan jika tidak ada review --}}
                        @if ($reviews->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <p class="text-sm p-5">Tidak terdapat review</p>
                                </td>
                            </tr>
                        @endif
                        {{-- Loop untuk menampilkan setiap review --}}
                        @foreach ($reviews as $review)
                            <tr>
                                {{-- Nomor urut --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                {{-- Nama user yang memberi review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->user->name }}
                                </td>
                                {{-- Judul buku yang di-review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->book->title }}
                                </td>
                                {{-- Isi review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->review }}
                                </td>
                                {{-- Tombol aksi untuk menghapus review --}}
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                            onclick="return confirm('Are you sure you want to delete this?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
