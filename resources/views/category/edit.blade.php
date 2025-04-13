@extends('layouts.main')

@section('contentAdmin')
    {{-- Form untuk mengupdate data kategori (untuk admin) --}}
    <form action="{{ route('category.update', $category->slug) }}" method="POST" class="p-4" enctype="multipart/form-data">
        @method('PUT') {{-- Method spoofing untuk HTTP PUT --}}
        @csrf {{-- Token CSRF untuk keamanan --}}

        {{-- Container utama form --}}
        <div class="bg-white rounded-xl overflow-hidden">
            {{-- Header form dengan tombol kembali --}}
            <div class="p-3 bg-blue-500">
                <a href="{{ route('category.index') }}" class="text-sm font-medium text-blue-500 flex items-center">
                    <i data-feather="arrow-left" class="w-5 h-5 text-white"></i>
                    <span class="ml-2 text-white">Edit category</span>
                </a>
            </div>

            {{-- Body form --}}
            <div class="w-full p-3">
                <div class="grid grid-cols-1 gap-4">
                    {{-- Input field untuk nama kategori --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border-2
                            @if($errors->has('slug'))
                                    dark:border-rose-500 {{-- Border merah jika ada error --}}
                            @else
                                dark:border-gray-300 {{-- Border abu-abu normal --}}
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $category->name }}"> {{-- Menampilkan nilai lama --}}

                        {{-- Menampilkan pesan error jika validasi gagal --}}
                        @error('slug')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Input untuk upload gambar --}}
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file"
                            class="block w-full text-sm text-slate-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-gray-50 file:text-blue-500
                          hover:file:bg-violet-100
                        "
                            onchange="showPreview(event)" name="image" />
                        {{-- Preview gambar yang sudah ada --}}
                        <img id="file-ip-1-preview" class="rounded-lg mt-3" src="{{ asset('storage/'.$category->image) }}">
                    </label>
                </div>

                {{-- Tombol submit --}}
                <button type="submit" class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
                focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm mt-3">Submit</button>
            </div>
        </div>
    </form>
@endsection

@section('contentPustakawan')
    {{-- Form untuk mengupdate data kategori (untuk pustakawan) --}}
    {{-- Struktur sama persis dengan contentAdmin --}}
    <form action="{{ route('category.update', $category->slug) }}" method="POST" class="p-4" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="bg-white rounded-xl overflow-hidden">
            <div class="p-3 bg-blue-500">
                <a href="{{ route('category.index') }}" class="text-sm font-medium text-blue-500 flex items-center">
                    <i data-feather="arrow-left" class="w-5 h-5 text-white"></i>
                    <span class="ml-2 text-white">Edit category</span>
                </a>
            </div>
            <div class="w-full p-3">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border-2
                            @if($errors->has('slug'))
                                    dark:border-rose-500
                            @else
                                dark:border-gray-300
                            @endif
                            text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-offset-1 focus:ring-2 focus:ring-blue-500 focus:border-white block w-full p-2.5"
                            placeholder="Kisah Nyata" required value="{{ $category->name }}">
                        @error('slug')
                            <p class="mt-1 text-left text-sm text-red-600 mb-0">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file"
                            class="block w-full text-sm text-slate-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-gray-50 file:text-blue-500
                          hover:file:bg-violet-100
                        "
                            onchange="showPreview(event)" name="image" />
                        <img id="file-ip-1-preview" class="rounded-lg mt-3" src="{{ asset('storage/'.$category->image) }}">
                    </label>
                </div>
                <button type="submit" class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
                focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm mt-3">Submit</button>
            </div>
        </div>
    </form>
@endsection
