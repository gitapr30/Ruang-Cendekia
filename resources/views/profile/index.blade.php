@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 mt-8">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Card Kiri (Foto Profil, Email, Password) -->
            <div class="bg-white p-6 rounded-lg shadow-lg w-full md:w-1/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Profil Pengguna</h3>

                <!-- Foto Profil -->
                <div class="flex flex-col items-center mb-6 relative">
                    <div class="w-32 h-32 bg-gray-100 rounded-full absolute -z-10"></div>

                    @if($user->image)
                    <label for="imageInput" class="cursor-pointer relative">
                        <img src="{{ asset('' . $user->image) }}" alt="Profile Image" class="w-24 h-24 rounded-full object-cover z-10">
                        <!-- Edit Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white absolute bottom-0 right-0 mb-1 mr-1 bg-gray-600 p-1 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 114.242 4.242l-9.396 9.395a2 2 0 01-.728.514l-4.22 1.409a1 1 0 01-1.232-1.231l1.41-4.222a2 2 0 01.513-.728L15.232 5.232z" />
                        </svg>
                    </label>
                    <input type="file" id="imageInput" name="image" class="hidden">
                    @else
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold z-10 relative">
                        No Image
                        <!-- Edit Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500 absolute bottom-0 right-0 mb-1 mr-1 bg-white p-1 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 114.242 4.242l-9.396 9.395a2 2 0 01-.728.514l-4.22 1.409a1 1 0 01-1.232-1.231l1.41-4.222a2 2 0 01.513-.728L15.232 5.232z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password Baru (Opsional) -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Card Kanan (Nama, Username, No. Telepon) -->
            <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Data Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-gray-700 font-medium">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('no_telp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('contentAdmin')
<div class="container mx-auto px-4 mt-8">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Card Kiri (Foto Profil, Email, Password) -->
            <div class="bg-white p-6 rounded-lg shadow-lg w-full md:w-1/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Profil Pengguna</h3>

                <!-- Foto Profil -->
                <div class="flex flex-col items-center mb-6 relative">
                    <div class="w-32 h-32 bg-gray-100 rounded-full absolute -z-10"></div>

                    @if($user->image)
                    <label for="imageInput" class="cursor-pointer relative">
                        <img src="{{ asset('' . $user->image) }}" alt="Profile Image" class="w-24 h-24 rounded-full object-cover z-10">
                        <!-- Edit Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white absolute bottom-0 right-0 mb-1 mr-1 bg-gray-600 p-1 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 114.242 4.242l-9.396 9.395a2 2 0 01-.728.514l-4.22 1.409a1 1 0 01-1.232-1.231l1.41-4.222a2 2 0 01.513-.728L15.232 5.232z" />
                        </svg>
                    </label>
                    <input type="file" id="imageInput" name="image" class="hidden">
                    @else
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold z-10 relative">
                        No Image
                        <!-- Edit Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500 absolute bottom-0 right-0 mb-1 mr-1 bg-white p-1 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 114.242 4.242l-9.396 9.395a2 2 0 01-.728.514l-4.22 1.409a1 1 0 01-1.232-1.231l1.41-4.222a2 2 0 01.513-.728L15.232 5.232z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password Baru (Opsional) -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Card Kanan (Nama, Username, No. Telepon) -->
            <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Data Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-gray-700 font-medium">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('no_telp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
