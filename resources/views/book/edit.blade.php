@extends('layouts.main') {{-- Menggunakan layout utama --}}

@section('contentAdmin') {{-- Section untuk konten admin/edit buku --}}
    {{-- Form untuk mengupdate data buku --}}
    <form action="{{ route('books.update', $book->slug) }}" method="POST" class="p-4" enctype="multipart/form-data">
        @method('put') {{-- Method spoofing untuk PUT request --}}
        @csrf {{-- CSRF token untuk keamanan form --}}
        <div class="bg-white rounded-xl overflow-hidden">
            {{-- Header form edit --}}
            <div class="p-3 bg-blue-500">
                {{-- Tombol kembali ke halaman daftar buku --}}
                <a href="/books" class="text-sm font-medium text-blue-500 flex items-center">
                    <i data-feather="arrow-left" class="w-5 h-5 text-white"></i>
                    <span class="ml-2 text-white">Edit Buku</span>
                </a>
            </div>
            <div class="w-full p-3">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Input untuk judul buku --}}
                    <div>
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul</label>
                        <input type="text" name="title" id="judul"
                            class="bg-gray-50 border-2
                            @if($errors->has('slug'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->title }}">
                        @error('slug')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk penulis buku --}}
                    <div>
                        <label for="penulis" class="block mb-2 text-sm font-medium text-gray-900">Penulis</label>
                        <input type="text" name="penulis" id="penulis"
                            class="bg-gray-50 border-2
                            @if($errors->has('penulis'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->penulis }}">
                        @error('penulis')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk penerbit buku --}}
                    <div>
                        <label for="penerbit" class="block mb-2 text-sm font-medium text-gray-900">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit"
                            class="bg-gray-50 border-2
                            @if($errors->has('penerbit'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->penerbit }}">
                        @error('penerbit')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk stok buku --}}
                    <div>
                        <label for="Jumlah Buku" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Buku</label>
                        <input type="text" name="stok" id="Jumlah Buku"
                            class="bg-gray-50 border-2
                            @if($errors->has('stok'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->stok }}">
                        @error('stok')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dropdown untuk kategori buku --}}
                    <div>
                        <label for="Category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                        <select name="category_id" id="category"
                            class="w-full bg-gray-50 border-2
                            @if($errors->has('category_id'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5">
                            @foreach ($categories as $category) {{-- Loop semua kategori --}}
                                <option value="{{ $category->id }}"
                                    @if ($category->id == $book->category_id) @selected(true) @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk jumlah halaman --}}
                    <div>
                        <label for="Halaman" class="block mb-2 text-sm font-medium text-gray-900">Halaman</label>
                        <input type="text" name="halaman" id="Halaman"
                            class="bg-gray-50 border-2
                            @if($errors->has('halaman'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="111" required value="{{ $book->halaman }}">
                        @error('halaman')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk tahun terbit --}}
                    <div>
                        <label for="thn_terbit" class="block mb-2 text-sm font-medium text-gray-900">Tahun Terbit</label>
                        <input type="date" name="thn_terbit" id="thn_terbit"
                            class="bg-gray-50 border-2
                            @if($errors->has('thn_terbit'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->thn_terbit }}">
                        @error('thn_terbit')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Input untuk upload gambar buku --}}
                        <label class="block mt-3">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file"
                            class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-gray-50 file:text-blue-500
                              hover:file:bg-violet-100"
                            onchange="showPreview(event)" {{-- Event untuk preview gambar --}}
                            name="image" />
                        @error('image')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                        {{-- Menampilkan gambar saat ini jika ada --}}
                        @if(isset($book->image) && $book->image)
                            <img id="file-ip-1-preview" class="rounded-lg mt-3" src="{{ asset($book->image) }}" width="150">
                        @endif
                    </label>
                    </div>

                    <div class="w-full">
                        {{-- Input untuk kode buku --}}
                        <div>
                            <label for="kodebuku" class="block mb-2 text-sm font-medium text-gray-900">Kode Buku</label>
                            <input type="text" name="kode_buku" id="kodebuku"
                                class="mb-2 bg-gray-50 border-2
                                @if($errors->has('kode_buku'))
                                    dark:border-rose-500
                                @else
                                    dark:border-gray-300
                                @endif
                                text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                                placeholder="049472872" required value="{{ $book->kode_buku }}">
                            @error('kode_buku')
                                <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Textarea untuk deskripsi buku --}}
                        <div>
                            <label for="desc" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                            <textarea name="description" id="desc" cols="30" rows="10"
                                class="bg-gray-50 border-2
                                @if($errors->has('description'))
                                    dark:border-rose-500
                                @else
                                    dark:border-gray-300
                                @endif
                                text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5">{{ $book->description }}</textarea>
                            @error('description')
                                <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Tombol submit untuk update data --}}
                <button class="w-full bg-blue-600 mt-3 rounded-lg text-white font-medium p-3 text-sm">Submit</button>
            </div>
        </div>
    </form>
@endsection

@extends('layouts.main') {{-- Menggunakan layout utama --}}

@section('contentPustakawan') {{-- Section untuk konten admin/edit buku --}}
    {{-- Form untuk mengupdate data buku --}}
    <form action="{{ route('books.update', $book->slug) }}" method="POST" class="p-4" enctype="multipart/form-data">
        @method('put') {{-- Method spoofing untuk PUT request --}}
        @csrf {{-- CSRF token untuk keamanan form --}}
        <div class="bg-white rounded-xl overflow-hidden">
            {{-- Header form edit --}}
            <div class="p-3 bg-blue-500">
                {{-- Tombol kembali ke halaman daftar buku --}}
                <a href="/books" class="text-sm font-medium text-blue-500 flex items-center">
                    <i data-feather="arrow-left" class="w-5 h-5 text-white"></i>
                    <span class="ml-2 text-white">Edit Buku</span>
                </a>
            </div>
            <div class="w-full p-3">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Input untuk judul buku --}}
                    <div>
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul</label>
                        <input type="text" name="title" id="judul"
                            class="bg-gray-50 border-2
                            @if($errors->has('slug'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->title }}">
                        @error('slug')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk penulis buku --}}
                    <div>
                        <label for="penulis" class="block mb-2 text-sm font-medium text-gray-900">Penulis</label>
                        <input type="text" name="penulis" id="penulis"
                            class="bg-gray-50 border-2
                            @if($errors->has('penulis'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->penulis }}">
                        @error('penulis')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk penerbit buku --}}
                    <div>
                        <label for="penerbit" class="block mb-2 text-sm font-medium text-gray-900">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit"
                            class="bg-gray-50 border-2
                            @if($errors->has('penerbit'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->penerbit }}">
                        @error('penerbit')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk stok buku --}}
                    <div>
                        <label for="Jumlah Buku" class="block mb-2 text-sm font-medium text-gray-900">Jumlah Buku</label>
                        <input type="text" name="stok" id="Jumlah Buku"
                            class="bg-gray-50 border-2
                            @if($errors->has('stok'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->stok }}">
                        @error('stok')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dropdown untuk kategori buku --}}
                    <div>
                        <label for="Category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                        <select name="category_id" id="category"
                            class="w-full bg-gray-50 border-2
                            @if($errors->has('category_id'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5">
                            @foreach ($categories as $category) {{-- Loop semua kategori --}}
                                <option value="{{ $category->id }}"
                                    @if ($category->id == $book->category_id) @selected(true) @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk jumlah halaman --}}
                    <div>
                        <label for="Halaman" class="block mb-2 text-sm font-medium text-gray-900">Halaman</label>
                        <input type="text" name="halaman" id="Halaman"
                            class="bg-gray-50 border-2
                            @if($errors->has('halaman'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="111" required value="{{ $book->halaman }}">
                        @error('halaman')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk tahun terbit --}}
                    <div>
                        <label for="thn_terbit" class="block mb-2 text-sm font-medium text-gray-900">Tahun Terbit</label>
                        <input type="date" name="thn_terbit" id="thn_terbit"
                            class="bg-gray-50 border-2
                            @if($errors->has('thn_terbit'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $book->thn_terbit }}">
                        @error('thn_terbit')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Input untuk upload gambar buku --}}
                        <label class="block mt-3">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file"
                            class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-gray-50 file:text-blue-500
                              hover:file:bg-violet-100"
                            onchange="showPreview(event)" {{-- Event untuk preview gambar --}}
                            name="image" />
                        @error('image')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                        {{-- Menampilkan gambar saat ini jika ada --}}
                        @if(isset($book->image) && $book->image)
                            <img id="file-ip-1-preview" class="rounded-lg mt-3" src="{{ asset($book->image) }}" width="150">
                        @endif
                    </label>
                    </div>

                    <div class="w-full">
                        {{-- Input untuk kode buku --}}
                        <div>
                            <label for="kodebuku" class="block mb-2 text-sm font-medium text-gray-900">Kode Buku</label>
                            <input type="text" name="kode_buku" id="kodebuku"
                                class="mb-2 bg-gray-50 border-2
                                @if($errors->has('kode_buku'))
                                    dark:border-rose-500
                                @else
                                    dark:border-gray-300
                                @endif
                                text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                                placeholder="049472872" required value="{{ $book->kode_buku }}">
                            @error('kode_buku')
                                <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Textarea untuk deskripsi buku --}}
                        <div>
                            <label for="desc" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                            <textarea name="description" id="desc" cols="30" rows="10"
                                class="bg-gray-50 border-2
                                @if($errors->has('description'))
                                    dark:border-rose-500
                                @else
                                    dark:border-gray-300
                                @endif
                                text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5">{{ $book->description }}</textarea>
                            @error('description')
                                <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Tombol submit untuk update data --}}
                <button class="w-full bg-blue-600 mt-3 rounded-lg text-white font-medium p-3 text-sm">Submit</button>
            </div>
        </div>
    </form>
@endsection
