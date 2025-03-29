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

    @if (session()->has('successMessage'))
        <div class="alert alert-success bg-green-600 text-white" id="hilangkan">
            <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-green-800">
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
        <div class="bg-red-600" id="hilangkan">
            <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-red-800">
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
            <div class="{{ auth()->user()->role != 'admin' && Request::is('books') ? 'w-full' : 'lg:w-5/6' }} bg-slate-50/[0.1] h-screen overflow-y-auto">
                @if (Request::is('category') || Request::is('books') || Request::is('borrow'))
                    <div class="flex p-2 items-center">
                        <div class="lg:flex p-3 px-4 rounded-lg bg-white shadow-sm text-sm items-center ml-2 text-gray-700 hidden">
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

                            <button type="submit"
                                class="transition-all duration-500 bg-gradient-to-br from-blue-400 to-blue-500 px-4 rounded-lg ml-2 font-medium text-sm text-white shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:shadow-none shadow-blue-100">
                                Cari
                            </button>

                            <!-- Notifikasi Button -->
                            <div class="relative ml-4">
                                <button id="notifButton" class="relative p-2 text-gray-600 hover:text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span id="notifBadge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full hidden">0</span>
                                </button>

                                <!-- Notifikasi Popup -->
                                <div id="notifPopup" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50">
                                    <div class="p-3 bg-blue-500 text-white flex justify-between items-center">
                                        <span class="font-semibold">Notifikasi</span>
                                        <button id="markAllRead" class="text-xs">Tandai semua sudah dibaca</button>
                                    </div>
                                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                        <div id="notifList" class="p-4 text-center text-gray-500">
                                            Memuat notifikasi...
                                        </div>
                                    </div>
                                    {{-- <div class="p-2 bg-gray-100 text-center">
                                        <a href="{{ route('borrow.history') }}" class="text-sm text-blue-500 hover:underline">Lihat semua</a>
                                    </div> --}}
                                </div>
                            </div>

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
                @if (Request::is(route('books.index', '*')))
                    <div class="lg:w-[30rem] w-[0rem] bg-white h-screen">
                    </div>
                @endif
            @endcan
            @can('isPustakawan')
                @if (Request::is(route('books.index', '*')))
                    <div class="lg:w-[30rem] w-[0rem] bg-white h-screen">
                    </div>
                @endif
            @endcan
        </div>
    @endif

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();

        function showPreview(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        const clsNotif = document.querySelector('#btn-notif');
        const notif = document.querySelector('#hilangkan');

        if (clsNotif) {
            clsNotif.onclick = function() {
                notif.classList.add("hidden");
            }
        }

        function openSide() {
            const side = document.querySelector('#sidebar');
            side.classList.add('translate-x-[0rem]')
            side.classList.add('w-full')
            side.classList.remove('w-0')
            side.classList.remove('translate-x-[-400rem]')
        }

        function closeSide() {
            const side = document.querySelector('#sidebar');
            side.classList.add('translate-x-[-400rem]')
            side.classList.remove('translate-x-[0rem]')
        }

        // Notification System
        // Notification System
document.addEventListener("DOMContentLoaded", function() {
    // Notification button and popup
    const notifButton = document.getElementById('notifButton');
    const notifPopup = document.getElementById('notifPopup');
    const notifBadge = document.getElementById('notifBadge');
    const notifList = document.getElementById('notifList');
    const markAllRead = document.getElementById('markAllRead');

    // Toggle popup visibility
    notifButton.addEventListener('click', function(e) {
        e.stopPropagation();
        notifPopup.classList.toggle('hidden');
        if (!notifPopup.classList.contains('hidden')) {
            fetchNotifications();
        }
    });

    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (!notifPopup.contains(e.target) && e.target !== notifButton) {
            notifPopup.classList.add('hidden');
        }
    });

    // Mark all as read
    if (markAllRead) {
        markAllRead.addEventListener('click', function(e) {
            e.preventDefault();
            // Here you would typically make an AJAX call to mark notifications as read
            notifBadge.classList.add('hidden');

            // Prevent default action and stop propagation
            e.stopPropagation();
            return false;
        });
    }

    // Format date to Indonesian format
    function formatIndonesianDate(dateString) {
        const date = new Date(dateString);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // Fetch notifications from server
    function fetchNotifications() {
        fetch('/borrow/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications.length > 0) {
                    // Update badge count
                    notifBadge.textContent = data.notifications.length;
                    notifBadge.classList.remove('hidden');

                    // Sort notifications by urgency (overdue first, then by days left)
                    data.notifications.sort((a, b) => {
                        if (a.type === 'overdue' && b.type !== 'overdue') return -1;
                        if (a.type !== 'overdue' && b.type === 'overdue') return 1;
                        return (a.days_left || 0) - (b.days_left || 0);
                    });

                    // Populate notifications
                    let html = '';
                    data.notifications.forEach(notif => {
                        let bgColor, textColor, icon;

                        if (notif.type === 'overdue') {
                            bgColor = 'bg-red-50';
                            textColor = 'text-red-800';
                            icon = `<svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>`;
                        } else {
                            bgColor = 'bg-blue-50';
                            textColor = 'text-blue-800';
                            icon = `<svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>`;
                        }

                        html += `
                            <div class="p-3 ${bgColor} ${textColor} border-b border-gray-200">
                                <div class="flex items-start">
                                    ${icon}
                                    <div>
                                        <p class="text-sm font-medium">${notif.message}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Jatuh tempo: ${formatIndonesianDate(notif.return_date)}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    notifList.innerHTML = html;
                } else {
                    notifList.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="mt-2">Tidak ada notifikasi baru</p>
                        </div>
                    `;
                    notifBadge.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                notifList.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="mt-2">Gagal memuat notifikasi</p>
                    </div>
                `;
            });
    }

    // Check for notifications every 5 minutes
    setInterval(fetchNotifications, 300000);

    // Initial fetch
    fetchNotifications();

    // Close success/error messages
    document.getElementById("btn-notif-succes")?.addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("hilangkan").classList.add("hidden");
    });

    document.getElementById("btn-notif-error")?.addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("hilangkan").classList.add("hidden");
    });

    // Prevent form submission from reloading page
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Only prevent default if it's not a search form
            if (!this.action.includes('search')) {
                e.preventDefault();
            }
        });
    });
});
    </script>

    @yield('scripts')
</body>
</html>
