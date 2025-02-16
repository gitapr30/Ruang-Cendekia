@extends('layouts.main')

@section('contentAdmin')
<div class="p-4">
    <h1 class="text-lg font-semibold text-gray-800 mb-4">Edit Rak Buku</h1>

    <!-- Form Edit -->
    <form action="{{ route('bookshelves.update', $bookshelves->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Input Rak -->
        <div class="mb-4">
            <label for="rak" class="block text-sm font-medium text-gray-700">Rak</label>
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="text" name="rak" id="rak" value="{{ old('rak', $bookshelves->rak) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
        </div>

        <!-- Input Baris -->
        <div class="mb-4">
            <label for="baris" class="block text-sm font-medium text-gray-700">Baris</label>
            <input type="text" name="baris" id="baris" value="{{ old('baris', $bookshelves->baris) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
        </div>

        <!-- Pilih Kategori -->
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category_id" id="category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $bookshelves->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex space-x-2">
            <button type="submit"
                class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition-all duration-300">
                Simpan Perubahan
            </button>
            <a href="{{ route('bookshelves.index') }}"
                class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition-all duration-300">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
