@extends('layouts.main')

@section('contentAdmin')
<div class="p-6">
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Detail Perubahan</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h2 class="p-4 text-lg font-semibold text-gray-700">Informasi Website</h2>
                <p class="p-4 text-gray-600"><strong>Nama Website:</strong> {{ $change->nama_website }}</p>
                <p class="p-4 text-gray-600"><strong>Alamat:</strong> {{ $change->alamat }}</p>
                <p class="p-4 text-gray-600"><strong>No. Telepon:</strong> {{ $change->no_telp }}</p>
                <p class="p-4 text-gray-600"><strong>Email:</strong> {{ $change->email }}</p>
                <p class="p-4 text-gray-600"><strong>Maps:</strong> <a href="{{ $change->maps }}" target="_blank" class="text-blue-500 underline">Lihat Lokasi</a></p>
                <p class="p-4 text-gray-600"><strong>Denda Terlambat:</strong> Rp. {{ $change->denda }}</p>
                <p class="p-4 text-gray-600"><strong>Denda Hilang/Rusak:</strong> Rp. {{ $change->denda_hilang }}</p>
            </div>

            <div>
                <h2 class="p-4 text-lg font-semibold text-gray-700">Detail Tambahan</h2>
                <p class="p-4 text-gray-600"><strong>Judul:</strong> {{ $change->tittle }}</p>
                <p class="p-4 text-gray-600"><strong>Deskripsi:</strong> {{ $change->description }}</p>
                <p class="p-4 text-gray-600"><strong>Konten:</strong> {!! nl2br(e($change->content)) !!}</p>
                <p class="p-4 text-gray-600"><strong>Footer:</strong> {{ $change->footer }}</p>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="p-4 text-lg font-semibold text-gray-700">Gambar</h2>
            <div class="p-4 flex space-x-4">
                @if($change->logo)
                <div>
                    <p class="text-gray-600 font-semibold">Logo:</p>
                    <img src="{{ asset('storage/' . $change->logo) }}" class="w-32 h-32 object-cover rounded-lg shadow-md" alt="Logo">
                </div>
                @endif

                @if($change->image)
                <div>
                    <p class="text-gray-600 font-semibold">Gambar:</p>
                    <img src="{{ asset('storage/' . $change->image) }}" class="w-32 h-32 object-cover rounded-lg shadow-md" alt="Image">
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('change.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Kembali</a>
        </div>
    </div>
</div>
@endsection
