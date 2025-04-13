@extends('layouts.main')

@section('content')
{{-- Main container for user profile page --}}
<div class="container mx-auto px-4 mt-8">
    {{-- Profile update form with file upload capability --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        {{-- CSRF protection and method spoofing for PUT request --}}
        @csrf
        @method('PUT')

        {{-- Responsive flex container for profile cards --}}
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Left card (Profile picture, email, password) --}}
            <div class="bg-white p-6 rounded-lg shadow-lg w-full md:w-1/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Profil Pengguna</h3>

                {{-- Profile picture section with upload functionality --}}
                <div class="flex flex-col items-center mb-6 relative">
                    {{-- Background circle for profile picture --}}
                    <div class="w-32 h-32 bg-gray-100 rounded-full absolute -z-10"></div>

                    {{-- Clickable label that triggers file input --}}
                    <label for="imageInput" class="cursor-pointer relative">
                        {{-- Profile image display --}}
                        <img id="profileImage" src="{{ asset($user->image ?? 'path/default-image.jpg') }}"
                            alt="Profile Image"
                            class="w-24 h-24 rounded-full object-cover z-10">

                        {{-- Edit icon overlay --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-white absolute bottom-0 right-0 mb-1 mr-1 bg-gray-600 p-1 rounded-full"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232a3 3 0 114.242 4.242l-9.396 9.395a2 2 0 01-.728.514l-4.22 1.409a1 1 0 01-1.232-1.231l1.41-4.222a2 2 0 01.513-.728L15.232 5.232z" />
                        </svg>
                    </label>
                    {{-- Hidden file input for profile picture upload --}}
                    <input type="file" id="imageInput" name="image" class="hidden">
                </div>

                {{-- Email input field with validation --}}
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- New password field (optional) --}}
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Password confirmation field --}}
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                    @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Right card (Personal information) --}}
            <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Data Diri</h3>
                {{-- Grid layout for personal information fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Name input field --}}
                    <div>
                        <label class="text-gray-700 font-medium">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Username input field --}}
                    <div>
                        <label class="text-gray-700 font-medium">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone number input field --}}
                    <div>
                        <label class="text-gray-700 font-medium">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                        @error('no_telp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Submit button --}}
                <div class="mt-8">
                    <button type="submit" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- JavaScript for profile picture preview --}}
<script>
    // Event listener for profile picture upload
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // FileReader to preview the selected image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update profile image source with the uploaded file
                document.getElementById('profileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection

{{-- Admin-specific profile section (same structure as main content) --}}
@section('contentAdmin')
<div class="container mx-auto px-4 mt-8">
    {{-- [Same structure as above section] --}}
</div>
<script>
    {{-- [Same JavaScript as above] --}}
</script>
@endsection

{{-- Librarian-specific profile section (same structure as main content) --}}
@section('contentPustakawan')
<div class="container mx-auto px-4 mt-8">
    {{-- [Same structure as above section] --}}
</div>
<script>
    {{-- [Same JavaScript as above] --}}
</script>
@endsection
