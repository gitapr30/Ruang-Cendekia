{{-- blue-50/[0.1] --}}
{{-- <div class="md:w-1/ absolute bg-white h-screen">
    <p>halo</p>
</div> --}}
{{-- md:hidden --}}

<div class="flex justify-center">
    <div class="space-y-2 m-auto p-2 lg:hidden" onclick="openSide()">
        <div class="w-5 h-0.5 bg-gray-600"></div>
        <div class="w-5 h-0.5 bg-gray-600"></div>
        <div class="w-5 h-0.5 bg-gray-600"></div>
    </div>
</div>
<div id="sidebar"
    class="w-0 lg:w-1/6 transition-all duration-300 absolute lg:static z-10 translate-x-[-400rem] lg:translate-x-[0rem] h-screen bg-white"
    style="background-color:rgb(8, 3, 53);">
    <div class="p-4 py-6 flex items-center justify-center">
        <div class="space-y-2 mr-4 lg:hidden" onclick="closeSide()">
            <div class="w-5 h-0.5 bg-gray-600"></div>
            <div class="w-5 h-0.5 bg-gray-600"></div>
            <div class="w-5 h-0.5 bg-gray-600"></div>
        </div>
        <div class="flex items-center">
            <img src="{{ asset('/assets/my-logo.png') }}" alt="My Logo" width="18">
            <span class="font-bold text-xl tracking-wide ml-3 text-[#303030]" style="color: white;">Ruang
                Cendekia</span>
        </div>
    </div>
    @can('isUser')
        <p class="m-0 px-5 text-base font-medium text-slate-400 mb-2" >MENU</p>
        <ul class="list-none list-inside px-5 mb-3">
            <li>
                <a href="{{ route('books.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 {{ Request::is('books*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                    @if (Request::is('books*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M3 6a3 3 0 013-3h2.25a3 3 0 013 3v2.25a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm9.75 0a3 3 0 013-3H18a3 3 0 013 3v2.25a3 3 0 01-3 3h-2.25a3 3 0 01-3-3V6zM3 15.75a3 3 0 013-3h2.25a3 3 0 013 3V18a3 3 0 01-3 3H6a3 3 0 01-3-3v-2.25zm9.75 0a3 3 0 013-3H18a3 3 0 013 3V18a3 3 0 01-3 3h-2.25a3 3 0 01-3-3v-2.25z"
                                clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium {{ Request::is('books*') ? 'text-white' : 'text-white' }} ">buku</p>
                </a>
            </li>
            <!-- <li>
                        <a href="{{ route('borrow.index') }}" class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('borrow*') ? 'shadow-lg shadow-blue-200 rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                            @if (Request::is('borrow*'))
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 fill-white">
                                    <path fill-rule="evenodd"
                                        d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 stroke-gray-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                                </svg>
                            @endif
                            <p
                                class="ml-2 font-medium text-gray-700  {{ Request::is('borrow*') ? 'text-white' : 'text-gray-700' }} ">
                                Peminjaman</p>
                        </a>
                    </li> -->
            <!-- <li>
                        <a href="{{ route('category.index') }}"
                            class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('category*') ? 'shadow-lg shadow-blue-200 rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                            @if (Request::is('category*'))
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-6 h-6 fill-white">
                                    <path
                                        d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 stroke-gray-700 ">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                                </svg>
                            @endif
                            <p class="ml-2 font-medium {{ Request::is('category*') ? 'text-white' : 'text-gray-700' }} ">Kategori
                            </p>
                        </a>
                    </li> -->
            <!-- Wishlist Menu with PNG Icon -->
            <li>
                <a href="{{ route('wishlist.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('wishlist*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('wishlist*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p
                        class="ml-2 font-medium text-gray-700  {{ Request::is('wishlist*') ? 'text-white' : 'text-white' }} ">
                        wishlist</p>
                </a>
            </li>

            <!-- History Peminjaman Menu with Custom Icon -->
            <li>
                <a href="{{ route('history.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('history*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('history*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium text-gray-700  {{ Request::is('history*') ? 'text-white' : 'text-white' }} ">
                        riwayat</p>
                </a>
            </li>
        </ul>
    @endcan
    @can('isAdmin')
        <p class="m-0 px-5 text-base font-medium text-slate-400 mb-2">ADMIN</p>
        <ul class="list-none list-inside px-5 mb-3">
            <li>
                <a href="{{ route('dashboard.index') }}"
                class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('dashboard*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                <img src="{{ asset('chart.svg') }}" alt="Dashboard" class="w-6 h-6 filter invert">
                <p class="ml-2 font-medium text-white">Dashboard</p>
            </a>
            </li> 
            <li>
                <a href="{{ route('books.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('books') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
        <img src="{{ asset('books.svg') }}" alt="Books" class="w-6 h-6 filter invert">
        <p class="ml-2 font-medium text-white">Buku</p>
    </a>
            </li>  
            <li>
                <a href="{{ route('category.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('category*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="white" fill="none" 
            class="w-6 h-6">
            <path
                d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
        </svg>
        <p class="ml-2 font-medium text-white">Kategori</p>
    </a>
            </li> 
            <li>
                <a href="{{ route('getwishlist.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('getwishlist*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
        <img src="{{ asset('wishlist.svg') }}" alt="Wishlist" class="w-6 h-6 filter invert">
        <p class="ml-2 font-medium text-white">Wishlist</p>
    </a>
            </li>
            <li>
                <a href="{{ route('bookshelves.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('bookshelves*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
        <img src="{{ asset('bookshelves.svg') }}" alt="Bookshelves" class="w-6 h-6 filter invert">
        <p class="ml-2 font-medium text-white">Rak</p>
    </a>
            </li>
            <li>
                <a href="{{ route('user.index') }}"
                class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('user*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                <img src="{{ asset('user_sidebar.svg') }}" alt="User" class="w-6 h-6 filter invert">
                <p class="ml-2 font-medium text-white">Pengguna</p>
            </a>
            </li>
            <li>
                <a href="{{ route('reviews.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('review*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
        <img src="{{ asset('pen.svg') }}" alt="Review" class="w-6 h-6 filter brightness-0 invert">
        <p class="ml-2 font-medium text-white">Ulasan</p>
    </a>
            </li>
            <li>
                <a href="{{ route('borrow.laporan') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('laporan*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
        <img src="{{ asset('laporan.svg') }}" alt="Laporan" class="w-6 h-6 filter invert">
        <p class="ml-2 font-medium text-white">Laporan Peminjaman</p>
    </a>
            </li>
            <li>
                <a href="{{ route('change.index') }}"
        class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('change*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
        <img src="{{ asset('change.svg') }}" alt="Change" class="w-6 h-6 filter invert">
        <p class="ml-2 font-medium {{ Request::is('change*') ? 'text-white' : 'text-white' }} ">Ubah</p>
    </a>
            </li>
        </ul>
    @endcan
    {{-- <p class="m-0 px-5 text-base font-medium text-slate-400 mb-2">SETTINGS</p>
    <ul class="list-none list-inside px-5">
        <li>
            <a href="" class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 {{ Request::is('/') ? 'text-white' : 'text-gray-700' }} ">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="ml-2 font-medium {{ Request::is('/') ? 'text-white' : 'text-gray-700' }} ">My Account</p>
            </a>
        </li>
    </ul> --}}
    @can('isPustakawan')
        <p class="m-0 px-5 text-base font-medium text-slate-400 mb-2">PUSTAKAWAN</p>
        <ul class="list-none list-inside px-5 mb-3">
        <li>
                <a href="{{ route('dashboard.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('dashboard*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('dashboard*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p
                        class="ml-2 font-medium text-white  {{ Request::is('dashboard*') ? 'text-white' : 'text-gray-700' }} ">
                        dashboard</p>
                </a>
            </li>
            <li>
                <a href="{{ route('books.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('books') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                    @if (Request::is('books'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path
                                d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium text-white {{ Request::is('books') ? 'text-white' : 'text-gray-700' }} ">buku</p>
                </a>
            </li>
            <li class="relative">
                <button
                    class="w-full flex items-center p-3 rounded-lg mb-1 transition-all ease-in-out duration-300 focus:outline-none text-white"
                    onclick="toggleDropdown()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25" />
                    </svg>
                    <p class="ml-2 font-medium text-white">peminjaman</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-auto transition-transform duration-300"
                        id="dropdownArrow" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 15.25a.75.75 0 01-.53-.22l-6-6a.75.75 0 111.06-1.06L12 13.44l5.47-5.47a.75.75 0 111.06 1.06l-6 6a.75.75 0 01-.53.22z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <ul id="dropdownMenu" class="hidden mt-1 pl-4 space-y-1">
                    @php
                        $statuses = [
                            'menunggu konfirmasi' => 'borrow.konfirmasi',
                            'dipinjam' => 'borrow.dipinjam',
                            'dikembalikan' => 'borrow.kembali',
                            'didenda' => 'borrow.denda'
                        ];
                    @endphp

                    @foreach ($statuses as $status => $route)
                        <li>
                            <a href="{{ route($route) }}"
                                class="block p-2 rounded-md transition-all ease-in-out duration-300
                                                    {{ request()->routeIs($route) ? 'bg-blue-500 text-white shadow-lg' : 'text-white' }}">
                                {{ ucfirst($status) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <script>
                function toggleDropdown() {
                    const menu = document.getElementById("dropdownMenu");
                    const arrow = document.getElementById("dropdownArrow");
                    menu.classList.toggle("hidden");
                    arrow.classList.toggle("rotate-180");
                }
            </script>
            <li>
                <a href="{{ route('category.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('category*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                    @if (Request::is('category*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path
                                d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium {{ Request::is('category*') ? 'text-white' : 'text-white' }} ">kategori
                    </p>
                </a>
            </li>
            <li>
                <a href="{{ route('getwishlist.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('getwishlist*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('getwishlist*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p
                        class="ml-2 font-medium text-white  {{ Request::is('getwishlist*') ? 'text-white' : 'text-white' }} ">
                        wishlist</p>
                </a>
            </li>
            <li>
                <a href="{{ route('bookshelves.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('bookshelves*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                    @if (Request::is('bookshelves*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path
                                d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium text-white {{ Request::is('bookshelves*') ? 'text-white' : 'text-gray-700' }} ">rak</p>
                </a>
            </li>
            <li>
                <a href="{{ route('user.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('user*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('user*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium text-white  {{ Request::is('user*') ? 'text-white' : 'text-gray-700' }} ">
                        pengguna</p>
                </a>
            </li>
            <li>
                <a href="{{ route('reviews.index') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg mb-1 {{ Request::is('review*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }}">
                    @if (Request::is('review*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path fill-rule="evenodd"
                                d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                    <p
                        class="ml-2 font-medium text-white  {{ Request::is('review*') ? 'text-white' : 'text-gray-700' }} ">
                        ulasan</p>
                </a>
            </li>
            <li>
                <a href="{{ route('borrow.laporan') }}"
                    class="transition-all ease-in-out duration-300 flex items-center p-3 rounded-lg {{ Request::is('laporan*') ? 'rounded-lg bg-gradient-to-br from-blue-400 to-blue-500' : '' }} mb-1">
                    @if (Request::is('laporan*'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 fill-white">
                            <path
                                d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                        </svg>
                    @endif
                    <p class="ml-2 font-medium {{ Request::is('laporan*') ? 'text-white' : 'text-white' }} ">laporan
                        peminjaman
                    </p>
                </a>
            </li>
        </ul>
    @endcan

    <form id="logout-form" action="/logout" method="POST">
        @csrf
        <button type="button" onclick="confirmLogout()" class="absolute bottom-4 px-5 left-0 right-0">
            <div
                class="group p-3 rounded-lg flex items-center transition-all duration-300 bg-transparent hover:bg-gradient-to-br from-blue-400 to-blue-500 hover:bg-size-200 hover:bg-pos-0 hover:bg-pos-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 text-white transition-all duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <p class="ml-2 font-medium text-white transition-all duration-300">Keluar</p>
            </div>
        </button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: "Yakin ingin keluar?",
                text: "Anda akan keluar dari akun ini.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Keluar",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</div>
