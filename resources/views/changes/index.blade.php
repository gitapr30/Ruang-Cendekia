@extends('layouts.main')

@section('contentAdmin')
<div class="p-4">
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 mb-3">Daftar Perubahan</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="mt-6">
        <div class="overflow-auto rounded-lg shadow block w-full">
            <table class="table-auto w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Nama Website</th>
                        <th class="w-40 p-3 text-sm font-semibold tracking-wide text-left">Alamat</th>
                        <th class="w-40 p-3 text-sm font-semibold tracking-wide text-left">Email</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($changes->isEmpty())
                    <tr>
                        <td colspan="5" class="p-5 text-sm text-gray-700 text-center">Tidak terdapat perubahan</td>
                    </tr>
                    @endif
                    @foreach ($changes as $change)
                    <tr>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $change->nama_website }}</td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $change->alamat }}</td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">{{ $change->email }}</td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('change.show', $change->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-400 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.5 4.5M19.5 10L15 14.5" />
                                </svg>
                            </a>
                            <a href="{{ route('change.edit', $change->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
