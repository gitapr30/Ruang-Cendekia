@extends('layouts.main')

@section('contentAdmin')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        {{-- Container untuk card form edit --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Header card dengan gradient background --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-edit mr-2"></i>Edit Rak Buku
                </h2>
            </div>

            {{-- Body card yang berisi form --}}
            <div class="p-6">
                {{-- Validasi error message --}}
                @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan input</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Form untuk mengupdate data rak buku --}}
                <form action="{{ route('bookshelves.update', $bookshelf) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Input untuk nomor rak --}}
                    <div class="mb-6">
                        <label for="rak" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-hashtag mr-1 text-blue-600"></i>Nomor Rak
                        </label>
                        <input type="text" name="rak" id="rak"
                               value="{{ old('rak', $bookshelf->rak) }}"
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border"
                               required>
                        @error('rak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input untuk baris rak --}}
                    <div class="mb-6">
                        <label for="baris" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group mr-1 text-blue-600"></i>Baris Rak
                        </label>
                        <input type="text" name="baris" id="baris"
                               value="{{ old('baris', $bookshelf->baris) }}"
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border"
                               required>
                        @error('baris')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dropdown untuk memilih kategori buku --}}
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag mr-1 text-blue-600"></i>Kategori Buku
                        </label>
                        <select name="category_id" id="category_id"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $bookshelf->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Container untuk tombol aksi --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        {{-- Tombol batal untuk kembali ke halaman sebelumnya --}}
                        <a href="{{ route('bookshelves.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        {{-- Tombol submit untuk menyimpan perubahan --}}
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main')

@section('contentPustakawan')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        {{-- Container untuk card form edit --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Header card dengan gradient background --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-edit mr-2"></i>Edit Rak Buku
                </h2>
            </div>

            {{-- Body card yang berisi form --}}
            <div class="p-6">
                {{-- Validasi error message --}}
                @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan input</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Form untuk mengupdate data rak buku --}}
                <form action="{{ route('bookshelves.update', $bookshelf) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Input untuk nomor rak --}}
                    <div class="mb-6">
                        <label for="rak" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-hashtag mr-1 text-blue-600"></i>Nomor Rak
                        </label>
                        <input type="text" name="rak" id="rak"
                               value="{{ old('rak', $bookshelf->rak) }}"
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border"
                               required>
                        @error('rak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input untuk baris rak --}}
                    <div class="mb-6">
                        <label for="baris" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group mr-1 text-blue-600"></i>Baris Rak
                        </label>
                        <input type="text" name="baris" id="baris"
                               value="{{ old('baris', $bookshelf->baris) }}"
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border"
                               required>
                        @error('baris')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dropdown untuk memilih kategori buku --}}
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag mr-1 text-blue-600"></i>Kategori Buku
                        </label>
                        <select name="category_id" id="category_id"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md border">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $bookshelf->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Container untuk tombol aksi --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        {{-- Tombol batal untuk kembali ke halaman sebelumnya --}}
                        <a href="{{ route('bookshelves.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        {{-- Tombol submit untuk menyimpan perubahan --}}
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
