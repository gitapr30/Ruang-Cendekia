<!DOCTYPE html>
<html lang="idn" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>

<body class="w-full h-screen overflow-hidden">

    {{-- {{ Request::is("/login") ? }} --}}

        @if (session()->has('SuccessMessage'))
        <div class="alert alert-success bg-green-600 text-white" id="hilangkan">
            <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-green-800">
                            <!-- Heroicon name: outline/speakerphone -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </span>
                        <p class="ml-3 font-medium text-white truncate">
                            <span class="md:hidden"> {{ session('successMessage') }} </span>
                            <span class="hidden md:inline"> {{ session('successMessage') }} </span>
                        </p>
                    </div>
                    <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                        <button id="btn-notif-succes" type="button"
                            class="-mr-1 flex p-2 rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2 ">
                            <!-- Heroicon name: outline/x -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('errorMessage'))
        <div class="bg-red-600 " id="hilangkan">
            <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-red-800">
                            <!-- Heroicon name: outline/speakerphone -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </span>
                        <p class="ml-3 font-medium text-white truncate">
                            <span class="md:hidden"> {{ session('errorMessage') }} </span>
                            <span class="hidden md:inline"> {{ session('errorMessage') }} </span>
                        </p>
                    </div>
                    <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                        <button id="btn-notif-error" type="button"
                            class="-mr-1 flex p-2 rounded-md hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2 ">
                            <!-- Heroicon name: outline/x -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (Request::is('login') || Request::is('register'))
        @yield('content')
    @else
        <div class="flex">
            @include('layouts.sidebar')
            <!-- shadow-sm border-slate-300 focus:outline focus:outline-2 focus:outline-offset-1 focus:outline-sky-200 focus:border-sky-500 focus:ring-sky-500 rounded-full placeholder-slate-400 -->
            <div
                class="{{ auth()->user()->role != 'admin' && Request::is('books') ? 'w-full' : 'lg:w-5/6' }} bg-slate-50/[0.1] h-screen overflow-y-auto">
                @if (Request::is('category') || Request::is('books') || Request::is('borrow'))
                    <div class="flex p-2 items-center">
                        <div
                            class="lg:flex p-3 px-4 rounded-lg bg-white shadow-sm text-sm items-center ml-2 text-gray-700 hidden ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>
                            <span class="ml-2 font-medium">{{ date('d/m/Y') }}</span>
                        </div>
                        <form
                        @if (Request::is('books*'))
                        action="{{ route('books.index') }}"
                    @elseif (Request::is('borrow*'))
                        action="{{ route('borrow.index') }}"
                    @elseif (Request::is('category*'))
                        action="{{ route('category.index') }}"
                    @elseif (Request::is('wishlist*'))
                        action="{{ route('wishlist.index') }}"
                    @elseif (Request::is('history*'))
                        action="{{ route('history.index') }}"
                    @elseif (Request::is('users*'))
                        action="{{ route('user.index') }}"
                    @endif
                    method="get" class="w-full flex justify-end ml-3">

    <form action="{{ route('books.index') }}" method="GET">
    <label class="relative block w-full">
        <span class="sr-only">Cari</span>
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </span>
        <input
            class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-lg py-3 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm w-full"
            placeholder="Cari buku ..." type="text" name="search"
            value="{{ request('search') }}"/>
    </label>

  <!-- Tombol Search -->
<button type="submit"
    class="transition-all duration-500 bg-gradient-to-br from-blue-400 to-blue-500 px-4 rounded-lg ml-2 font-medium text-sm text-white shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:shadow-none shadow-blue-100">
    Cari
</button>
</form>

@can('isUser')
<div class="relative ml-4">
    <a href="{{ route('notification.index') }}" class="relative flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(isset($unreadCount) && $unreadCount > 0)
            <span id="notifCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @else
            <span id="notifCount" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
        @endif
    </a>
</div>
@endcan


<!-- Tombol Profile dengan Icon -->
<a href="{{ route('profile.index') }}"
    class="transition-all duration-500 bg-transparent px-4 py-2 rounded-lg ml-2 font-medium text-sm text-white shadow-lg focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:shadow-none shadow-gray-100 flex items-center">
    <img id="profileImage" src="{{ asset(auth()->user()->image ?? 'path/default-image.jpg') }}" class="w-5 h-5">
</a>
</form>
                    </div>
                @endif
                @can('isUser')
                    @yield('content')
                @endcan
                @can('isAdmin')
                    @yield('contentAdmin')
                @endcan
                @can('isPustakawan')
                    @yield('contentPustakawan')
                @endcan
            </div>
            @can('isUser')
                {{-- w-2/6  --}}
                @if (Request::is(route('books.index', '*')))
                    <div class="lg:w-[30rem] w-[0rem] bg-white h-screen">
                    </div>
                @endif
            @endcan
            @can('isPustakawan')
                {{-- w-2/6  --}}
                @if (Request::is(route('books.index', '*')))
                    <div class="lg:w-[30rem] w-[0rem] bg-white h-screen">
                    </div>
                @endif
            @endcan
        </div>
    @endif

    <script src="https://unpkg.com/feather-icons"></script>


</body>

</html>
