@extends('layouts.main')

@section('contentAdmin')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Card Utama -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-books mr-2"></i>Tambah Rak Buku Baru
                </h2>
            </div>

            <!-- Body Card -->
            <div class="p-6">
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

                <form action="{{ route('bookshelves.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Input Nama Rak -->
                    <div>
                        <label for="rak" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-hashtag mr-1 text-blue-600"></i>Nomor Rak
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="rak" id="rak"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md border"
                                   placeholder="Contoh: A1, B2, dll" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor identifikasi rak</p>
                    </div>

                    <!-- Input Baris -->
                    <div>
                        <label for="baris" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group mr-1 text-blue-600"></i>Baris Rak
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="baris" id="baris"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md border"
                                   placeholder="Contoh: 1, 2, 3, dll" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-sort-numeric-up text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor baris rak</p>
                    </div>

                    <!-- Input Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag mr-1 text-blue-600"></i>Kategori Buku
                        </label>
                        <select name="category_id" id="category_id"
                                class="mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih kategori buku yang akan disimpan di rak ini</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('bookshelves.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Simpan Rak
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
        <!-- Card Utama -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-books mr-2"></i>Tambah Rak Buku Baru
                </h2>
            </div>

            <!-- Body Card -->
            <div class="p-6">
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

                <form action="{{ route('bookshelves.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Input Nama Rak -->
                    <div>
                        <label for="rak" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-hashtag mr-1 text-blue-600"></i>Nomor Rak
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="rak" id="rak"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md border"
                                   placeholder="Contoh: A1, B2, dll" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor identifikasi rak</p>
                    </div>

                    <!-- Input Baris -->
                    <div>
                        <label for="baris" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group mr-1 text-blue-600"></i>Baris Rak
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="baris" id="baris"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md border"
                                   placeholder="Contoh: 1, 2, 3, dll" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-sort-numeric-up text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor baris rak</p>
                    </div>

                    <!-- Input Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag mr-1 text-blue-600"></i>Kategori Buku
                        </label>
                        <select name="category_id" id="category_id"
                                class="mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih kategori buku yang akan disimpan di rak ini</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('bookshelves.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Simpan Rak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

