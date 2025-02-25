@extends('layouts.main')

@section('content')
<div class="p-6 mb-12 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
    <!-- Bagian Kiri: New Release dan Library Collection -->
    <div class="col-span-2 space-y-8">
        <!-- New Release Books -->
        <div>
            <h1 class="text-xl font-semibold text-gray-800 mb-4">Buku terbaru</h1>
            @if ($newBooks->isEmpty())
            <p class="text-sm text-gray-600">Belum ada rilis baru</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
                @foreach ($newBooks as $book)
                <a href="#"
                    onclick="showBookDetails(event,
        `{{ addslashes($book->title) }}`,
        `{{ asset($book->image) }}`,
        `{{ $book->release_date }}`,
        `{{ addslashes($book->penulis) }}`,
        `{{ addslashes($book->description) }}`,
        `{{ route('books.show', ['slug' => $book->slug]) }}`
    )"
                    class="group flex flex-col items-center space-y-3 bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 relative">

                    <div class="bg-blue-600 px-3 py-1 text-white rounded-md text-xs absolute top-2 left-2">Baru</div>
                    <img src="{{ asset($book->image) }}" alt="books" class="w-36 h-52 object-cover rounded-lg">
                    <h1 class="font-bold text-sm text-gray-700 text-center truncate group-hover:whitespace-normal">
                        {{ $book->title }}
                    </h1>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            <h1 class="text-xl font-semibold text-gray-800 mb-4">Koleksi perpustakaan</h1>
            @if ($books->isEmpty())
            <p class="text-sm text-gray-600">Tidak terdapat buku</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
    @foreach ($books as $book)
    @php
        $dipinjam = false;
    @endphp
    @if ($book->borrow->isNotEmpty())
        @foreach ($book->borrow as $borrow)
            @if ($borrow->user_id == auth()->user()->id && $borrow->status == 'meminjam')
                @php
                    $dipinjam = true;
                @endphp
            @endif
        @endforeach
    @endif

    <div class="relative">
        @if ($dipinjam)
            <div class="bg-zinc-800 p-2 text-white rounded-md text-xs absolute top-2 left-2">Dipinjam</div>
        @elseif ($book->stok == 0)
            <div class="bg-zinc-800 p-2 text-white rounded-md text-xs absolute top-2 left-2">Tidak Tersedia</div>
        @else
            <div class="bg-green-600 p-2 text-white rounded-md text-xs absolute top-2 left-2">Tersedia</div>
        @endif

        <a href="#"
            onclick="showBookDetails(event,
            `{{ addslashes($book->title) }}`,
            `{{ asset($book->image) }}`,
            `{{ $book->release_date }}`,
            `{{ addslashes($book->penulis) }}`,
            `{{ addslashes($book->description) }}`,
            `{{ route('books.show', ['slug' => $book->slug]) }}`
        )"
            class="group flex flex-col items-center space-y-3 bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">

            <img src="{{ asset($book->image) }}" alt="books" class="w-36 h-52 object-cover rounded-lg">
            <h1 class="font-bold text-sm text-gray-700 text-center truncate group-hover:whitespace-normal">
                {{ $book->title }}
            </h1>
        </a>
    </div>
    @endforeach
</div>
            @endif
        </div>

    </div>

    <!-- Bagian Kanan: Detail Buku -->
    <div class="col-span-1 hidden md:block">
        <div id="book-details" class="hidden p-6 border border-gray-300 rounded-lg shadow-md bg-white relative">
            <!-- Close Button (Fixed: No Page Refresh) -->
            <button id="close-book-details" type="button" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                &times;
            </button>
            <h1 class="text-lg font-bold text-gray-800 mb-4">Detail Buku</h1>
            <div class="flex flex-col items-center space-y-3">
                <img id="book-image" src="" alt="Book Image" class="w-40 h-56 object-cover rounded-lg mb-3">
                <h2 id="book-title" class="text-md font-bold text-gray-800 text-center"></h2>
                <p id="book-penulis" class="text-gray-600 mt-2 text-sm"></p>
                <p id="book-release" class="text-gray-600 mt-2 text-sm"></p>
                <div class="relative w-full mt-3">
                    <p id="book-description" class="text-gray-600 text-sm overflow-hidden"></p>
                    <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
                </div>
                <a id="book-detail-link" href="#" class="mt-4 px-4 py-2 bg-[#DFF2FE] text-blue-600 text-sm font-semibold rounded-lg hover:bg-[#C4E7F8] transition">
    Lihat Detail
</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showBookDetails(event, title, image, releaseDate, penulis, description, detailUrl) {
        event.preventDefault(); // Prevents default link behavior

        // Update book details
        document.getElementById('book-title').innerText = title;
        document.getElementById('book-image').src = image || '/default-book.jpg';
        // document.getElementById('book-release').innerText = "Tanggal Rilis: " + releaseDate;
        document.getElementById('book-penulis').innerText = "Penulis: " + penulis;
        document.getElementById('book-description').innerText = description.length > 150 ? description.substring(0, 150) + "..." : description;
        document.getElementById('book-detail-link').href = detailUrl;

        // Show book details section
        document.getElementById('book-details').classList.remove('hidden');
    }

    // Close button (Fix: Prevent page refresh)
    document.getElementById('close-book-details').addEventListener('click', function(event) {
        event.preventDefault(); // Prevents any default action
        document.getElementById('book-details').classList.add('hidden');
    });
</script>

<!-- Tabs for Categories -->
<div class="mt-20 mb-12">
    <h1 class="text-lg font-semibold text-gray-800 mb-3" style="margin-left: 25px; font-size: 21px;">Kategori</h1>
    <ul class="flex space-x-6 overflow-x-auto px-4">
        <!-- Tab All -->
        <li>
            <button type="button" class="p-3 px-6 cursor-pointer transition duration-300 rounded-full focus:outline-none bg-blue-500 text-white font-semibold"
                onclick="showCategory(event, 'all', this)" style="margin-left: 10px;">
                Semua
            </button>
        </li>
        @foreach ($categories as $category)
        <li>
            <button type="button" class="p-3 px-6 cursor-pointer transition duration-300 rounded-full focus:outline-none bg-gray-200 text-gray-700"
                onclick="showCategory(event, '{{ $category->id }}', this)">
                {{ $category->name }}
            </button>
        </li>
        @endforeach
    </ul>
</div>
@foreach ($categories as $category)
<div id="category-{{ $category->id }}" class="category-content mt-6">
    <h2 class="text-gray-700 text-xl font-bold mb-4" style="margin-left: 21px;">{{ $category->name }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="margin-left: 21px;">
        @foreach ($category->books as $book)
        <a href="{{ route('books.show', parameters: $book->slug) }}" class="group transition rounded-md hover:scale-95 duration-300 relative flex space-x-4 p-4 bg-white shadow-md border border-gray-300 rounded-md">
            <img src="{{ asset($book->image) }}" alt="{{ $book->title }}" class="w-32 h-48 object-cover rounded shadow">
            <div class="flex-1 relative">
                <h1 class="font-bold text-lg text-gray-700 truncate group-hover:truncate-none peer">
                    {{ $book->title }}
                </h1>
                <div class="p-2 absolute bg-white shadow-lg border border-slate-300 rounded right-0 left-0 transition-all duration-300 z-[-10] peer-hover:z-10 opacity-0 translate-y-5 peer-hover:translate-y-0 peer-hover:opacity-100 hover:translate-y-0 hover:opacity-100 hover:z-10">
                    {{ $book->title }}
                </div>
                <div class="text-sm flex text-gray-700 items-center font-medium mt-2">
                    <i data-feather="edit-3" width="16px"></i>
                    <span class="ml-2">{{ $book->penulis }}</span>
                </div>
                <div class="text-sm flex text-gray-700 items-center font-medium mt-2">
                    <i data-feather="calendar" width="16px"></i>
                    <span class="ml-2">Maret 20, 2022</span>
                </div>
                <div class="text-sm flex text-gray-700 items-center font-medium mt-2">
                    <i data-feather="layers" width="16px"></i>
                    <span class="ml-2">200 Halaman</span>
                </div>

                <!-- Wishlist Button -->
                <form action="{{ route('wishlist.store', $book->slug) }}" method="POST" class="absolute bottom-2 right-2 flex justify-end items-end">
                    @csrf
                    <input type="hidden" name="suka" value="liked">
                    <button type="submit" class="bg-transparent p-2 rounded-full hover:bg-gray-200 transition">
                        <img src="{{ asset('love_wishlist.png') }}" alt="Wishlist" class="w-6 h-6">
                    </button>
                </form>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endforeach

<script>
    function showCategory(event, categoryId, element) {
        event.preventDefault(); // ✅ Prevents page refresh

        // Hide all category content
        document.querySelectorAll('.category-content').forEach(el => el.classList.add('hidden'));

        // Show selected category content
        if (categoryId === 'all') {
            document.getElementById('category-all').classList.remove('hidden');
        } else {
            document.getElementById('category-' + categoryId).classList.remove('hidden');
        }

        // Remove active styles from all buttons
        document.querySelectorAll('button').forEach(el => {
            el.classList.remove('bg-blue-500', 'text-white', 'font-semibold');
            el.classList.add('bg-gray-200', 'text-gray-700');
        });

        // Add active styles to the clicked button
        element.classList.add('bg-blue-500', 'text-white', 'font-semibold');
        element.classList.remove('bg-gray-200', 'text-gray-700');
    }

</script>

<footer class="mt-12 py-4 bg-gray-200 text-grey text-center" style="font-size: 14px;">
    <p>&copy; {{ date('Y') }} RuangCendekia. Hak Cipta Dilindungi Undang-Undang.</p>
</footer>

@endsection

@section('contentAdmin')
<div class="p-4">
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 mb-3">Data Buku</h1>
        <a href="{{ route('books.create') }}"
            class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
                    focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm">Tambah
            Buku</a>
    </div>
    <div class="mt-6">
        <div class="overflow-auto rounded-lg shadow block w-full ">
            <table class="table-auto w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Judul</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Kode Buku</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Penulis</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Penerbit</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Kategori</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Jumlah Buku</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($books->isEmpty())
                    <tr>
                        <td colspan="7">
                            <p class="text-sm p-5">Tidak terdapat buku</p>
                        </td>
                    </tr>
                    @endif
                    @foreach ($books as $book)
                    <tr>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->title }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->kode_buku }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->penulis }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->penerbit }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->category->name }}
                        </td>

                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->stok }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            <a href="{{ route('books.show', $book->slug) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-400 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.5 4.5M19.5 10L15 14.5" />
                                </svg>
                            </a>
                            {{-- url('/' . $book->slug . '/edit') --}}
                            <a href="{{ route('books.edit', $book->slug) }}" type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            {{-- {{ route('destroy', $book->id) }} --}}
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                @method('delete')
                                @csrf
                                <button
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                    id="deletePost"
                                    onclick="return confirm('Are you sure you want to delete this?');"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('contentPustakawan')
<div class="p-4">
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 mb-3">Data Buku</h1>
        <a href="{{ route('books.create') }}"
            class="transition-all duration-500 bg-blue-500 rounded-lg text-white font-medium px-5 py-2.5 focus:ring-2
                    focus:ring-blue-500 focus:ring-offset-2 text-center hover:bg-blue-600 text-sm">Tambah
            Buku</a>
    </div>
    <div class="mt-6">
        <div class="overflow-auto rounded-lg shadow block w-full ">
            <table class="table-auto w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                        <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Judul</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Kode Buku</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Penulis</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Penerbit</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Kategori</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Jumlah Buku</th>
                        <th class="w-20 p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($books->isEmpty())
                    <tr>
                        <td colspan="7">
                            <p class="text-sm p-5">Tidak terdapat buku</p>
                        </td>
                    </tr>
                    @endif
                    @foreach ($books as $book)
                    <tr>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->title }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->kode_buku }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->penulis }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->penerbit }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->category->name }}
                        </td>

                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $book->stok }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            <a href="{{ route('books.show', $book->slug) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-400 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.5 4.5M19.5 10L15 14.5" />
                                </svg>
                            </a>
                            {{-- url('/' . $book->slug . '/edit') --}}
                            <a href="{{ route('books.edit', $book->slug) }}" type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 no-underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            {{-- {{ route('destroy', $book->id) }} --}}
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                @method('delete')
                                @csrf
                                <button
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 no-underline"
                                    id="deletePost"
                                    onclick="return confirm('Are you sure you want to delete this?');"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
