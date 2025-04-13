@extends('layouts.main')

@section('contentAdmin')
<div class="p-6">
    {{-- Page title for edit changes --}}
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Edit Perubahan</h1>

    {{-- Main form container --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        {{-- Form for updating changes --}}
        <form action="{{ route('change.update', $change->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Two-column grid layout --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Left column: Website information --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Informasi Website</h2>
                    {{-- Hidden user ID field --}}
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    {{-- Website name field --}}
                    <label class="block text-gray-600">Nama Website</label>
                    <input type="text" name="nama_website" value="{{ old('nama_website', $change->nama_website) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Address field --}}
                    <label class="block text-gray-600 mt-2">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $change->alamat) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Phone number field --}}
                    <label class="block text-gray-600 mt-2">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $change->no_telp) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Email field --}}
                    <label class="block text-gray-600 mt-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $change->email) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Google Maps URL field --}}
                    <label class="block text-gray-600 mt-2">Pilih Lokasi (Google Maps URL)</label>
                    <input id="maps" type="text" name="maps" value="{{ old('maps', $change->maps ?? '') }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Link to view current maps location --}}
                    @if($change->maps)
                        <a href="{{ $change->maps }}" target="_blank"
                        class="mt-2 inline-block text-blue-600 underline">Lihat di Google Maps</a>
                    @endif

                    {{-- Late fine amount field --}}
                    <label class="block text-gray-600 mt-2">Denda</label>
                    <input type="text" name="denda" value="{{ old('denda', $change->denda) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Lost/damaged fine amount field --}}
                    <label class="block text-gray-600 mt-2">Denda Hilang/Rusak</label>
                    <input type="text" name="denda_hilang" value="{{ old('denda_hilang', $change->denda_hilang) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Maximum borrowing limit field --}}
                    <label class="block text-gray-600 mt-2">Max Peminjaman</label>
                    <input type="text" name="max_peminjaman" value="{{ old('max_peminjaman', $change->max_peminjaman) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">
                </div>

                {{-- Right column: Additional details --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Detail Tambahan</h2>

                    {{-- Title field --}}
                    <label class="block text-gray-600">Judul</label>
                    <input type="text" name="tittle" value="{{ old('tittle', $change->tittle) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Description textarea --}}
                    <label class="block text-gray-600 mt-2">Deskripsi</label>
                    <textarea name="description" rows="2"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">{{ old('description', $change->description) }}</textarea>

                    {{-- Content textarea --}}
                    <label class="block text-gray-600 mt-2">Konten</label>
                    <textarea name="content" rows="4"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">{{ old('content', $change->content) }}</textarea>

                    {{-- Footer text field --}}
                    <label class="block text-gray-600 mt-2">Footer</label>
                    <input type="text" name="footer" value="{{ old('footer', $change->footer) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">

                    {{-- Operational hours field --}}
                    <label class="block text-gray-600 mt-2">Waktu Operasional</label>
                    <input type="text" name="waktu_operasional" value="{{ old('waktu_operasional', $change->waktu_operasional) }}"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-300">
                </div>
            </div>

            {{-- Image upload section --}}
            <div class="mt-4">
                <h2 class="text-lg font-semibold text-gray-700">Gambar</h2>
                <div class="flex space-x-4">
                    {{-- Logo upload field --}}
                    <div>
                        <label class="block text-gray-600">Logo</label>
                        <input type="file" name="logo" class="w-full p-2 border rounded-md">
                        {{-- Show current logo if exists --}}
                        @if($change->logo)
                        <img src="{{ asset('storage/' . $change->logo) }}" class="w-32 h-32 object-cover mt-2 rounded-lg shadow-md" alt="Logo">
                        @endif
                    </div>

                    {{-- Image upload field --}}
                    <div>
                        <label class="block text-gray-600">Gambar</label>
                        <input type="file" name="image" class="w-full p-2 border rounded-md">
                        {{-- Show current image if exists --}}
                        @if($change->image)
                            <img src="{{ asset('storage/' . $change->image) }}" class="w-32 h-32 object-cover mt-2 rounded-lg shadow-md" alt="Image">
                        @endif
                    </div>
                </div>
            </div>

            {{-- Form action buttons --}}
            <div class="mt-6 flex space-x-4">
                {{-- Submit button --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-800">Simpan</button>
                {{-- Cancel button --}}
                <a href="{{ route('change.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
