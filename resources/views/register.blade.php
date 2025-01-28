@extends('layouts.main')

@section('content')
<section class="bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('../bg-perpus.jpg') }}');">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-black">
            <img src="{{ asset('/assets/my-logo.png') }}" width="24" alt="logo">
            <span class="text-white font-bold text-xl tracking-wide ml-3">iLibrary</span>
        </a>
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-black md:text-2xl">
                    Sign Up
                </h1>
                <form class="space-y-4 md:space-y-6" action="/register" method="POST">
                    @csrf
                    <div>
                        <label for="nip_nisn" class="block mb-2 text-sm font-medium text-black">NIP/NISN</label>
                        <input type="text" name="nip_nisn" id="nip_nisn"
                            class="bg-gray-50 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter NIP or NISN" required="" value="{{ old('nip_nisn') }}">
                        @error('nip_nisn')
                        <p class="mt-1 text-left text-sm text-red-600 mb-0">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div>
                        <label for="fullName" class="block mb-2 text-sm font-medium text-black">Full Name</label>
                        <input type="text" name="name" id="fullName"
                            class="bg-gray-50 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Ferran Torrez" required="" value="{{ old('name') }}">
                        @error('name')
                        <p class="mt-1 text-left text-sm text-red-600 mb-0">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-black">Username</label>
                        <input type="text" name="username" id="username"
                            class="bg-gray-50 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="ferrantorrez" required="" value="{{ old('username') }}">
                        @error('username')
                        <p class="mt-1 text-left text-sm text-red-600 mb-0">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-black">Email</label>
                        <input type="email" name="email" id="email"
                            class="bg-gray-50 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="name@company.com" required="" value="{{ old('email') }}">
                        @error('email')
                        <p class="mt-1 text-left text-sm text-red-600 mb-0">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-black">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="bg-gray-50 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            required="">
                        @error('password')
                        <p class="mt-1 text-left text-sm text-red-600 mb-0">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Sign up
                    </button>
                    <p class="text-sm font-light text-black">
                        Already have an account? <a href="/login" class="font-medium text-sky-600 hover:underline">Sign In</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
