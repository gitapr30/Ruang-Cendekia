@extends('layouts.main')

@section('content')
<section class="bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('../bg-perpus.jpg') }}');">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-black">
            <img src="{{ asset('/assets/my-logo.png') }}" width="24" alt="logo">
            <span class="font-bold text-xl tracking-wide ml-3 text-white">iLibrary</span>
        </a>
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 dark:bg-white dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-black md:text-2xl dark:text-black">
                    Masuk ke Akun Anda
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST" action="/login">
                    @csrf
                    <div>
                        <label for="nip_nisn" class="block mb-2 text-sm font-medium text-black dark:text-black">NIS</label>
                        <input type="text" name="nip_nisn" id="nip_nisn"
                            class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Nomor Induk Siswa Nasional" required>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-black dark:text-black">Your email</label>
                        <input type="email" name="email" id="email"
                            class="form-control bg-white border text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="name@gmail.com" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-4">
    <label for="password" class="block mb-2 text-sm font-medium text-black dark:text-black">Password</label>
    <div class="input-group">
        <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="••••••••"
            class="form-control bg-white border text-black text-base rounded-lg focus:ring-primary-600 focus:border-primary-600 p-3"
        >
        <button 
            type="button" 
            class="btn btn-outline-secondary"
            onclick="togglePassword()"
        >
            Show
        </button>
    </div>
</div>
                    <button type="submit"
                        class="w-full text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign
                        in</button>
                   <p class="text-sm font-light text-black">
                        Don’t have an account yet? <a href="/register"
                            class="font-medium text-sky-600 hover:underline">Sign up</a>
                    </p> 
                </form>
            </div>
        </div>
    </div>
</section>

<script>
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
