@extends('layouts.main')

@section('content')
    {{-- Section untuk menampilkan halaman login dengan background gambar --}}
    <section class="bg-cover bg-center bg-no-repeat h-screen"
        style="background-image: url('{{ asset('../bg-perpus.jpg') }}');">
        {{-- Container utama untuk form login --}}
        <div class="flex flex-col items-center justify-center min-h-screen px-6 py-8 mx-auto">
            <!-- <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-black">
                <img src="{{ asset('/assets/my-logo.png') }}" width="24" alt="logo">
                <span class="font-bold text-xl tracking-wide ml-3 text-white">RuangCendekia</span>
            </a> -->

            {{-- Card untuk form login --}}
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 dark:bg-white dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    {{-- Judul form --}}
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-black md:text-2xl dark:text-black">
                        Masuk ke Akun Anda
                    </h1>
                    <!-- @if(session('errorMessage'))
                        <div class="bg-red-500 text-white p-3 rounded mb-3">
                            {{ session('errorMessage') }}
                        </div>
                    @endif -->

                    {{-- Form login --}}
                    <form class="space-y-4 md:space-y-6" method="POST" action="/login">
                        @csrf
                        {{-- Input untuk NIS/NIP --}}
                        <div>
                            <label for="nip_nisn"
                                class="block mb-2 text-sm font-medium text-black dark:text-black">NIS/NIP</label>
                            <input type="text" name="nip_nisn" id="nip_nisn"
                                class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="Nomor Anggota" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        {{-- Input untuk email --}}
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-black dark:text-black">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="nama@gmail.com" required value="{{ old('email') }}">
                        </div>

                        {{-- Input untuk password dengan toggle visibility --}}
                        <div class="mb-4 relative">
                            <label for="password" class="block mb-2 text-sm font-medium text-black dark:text-black">Kata
                                Sandi</label>
                            <div class="input-group flex items-center">
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                    class="form-control bg-white border text-black text-base rounded-lg focus:ring-primary-600 focus:border-primary-600 p-3 w-full">
                                {{-- Tombol untuk menampilkan/menyembunyikan password --}}
                                <button type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm text-sky-600"
                                    onclick="togglePassword()" style="margin-top: 10px;">
                                    Show
                                </button>
                            </div>
                        </div>

                        {{-- Tombol submit --}}
                        <button type="submit"
                            class="w-full text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Masuk
                        </button>
                        <!-- <p class="text-sm font-light text-black">
                            Lupa Kata Sandi? <a href="" class="font-medium text-sky-600 hover:underline">Ganti Kata
                                Sandi</a>
                        </p> -->
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Script untuk toggle visibility password --}}
    <script>
        /**
         * Fungsi untuk menampilkan/menyembunyikan password
         */
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = passwordInput.nextElementSibling;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
@endsection
