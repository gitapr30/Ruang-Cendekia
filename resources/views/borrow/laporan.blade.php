@extends('layouts.main')

@section('contentAdmin')
<!-- Menambahkan library Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Judul Halaman -->
<h2 class="text-xl font-bold text-gray-800 mt-10 mb-3 ml-5">Laporan Peminjaman</h2>

<!-- Section Filter dan Pencarian -->
<div class="bg-gray-100 p-4 rounded-lg shadow-sm mb-6">
    <div class="flex flex-col md:flex-row md:space-x-2 items-center gap-2">
        <!-- Input pencarian -->
        <input type="text" id="search" placeholder="Cari peminjam atau buku..."
            class="border px-3 py-2 rounded-lg w-full md:w-1/3 shadow-sm focus:ring focus:ring-blue-300">

        <!-- Filter tahun dan kuartal -->
        <div class="flex space-x-2">
            <!-- Dropdown filter tahun -->
            <select id="yearFilter" class="border px-3 py-2 rounded-lg w-24 shadow-sm focus:ring focus:ring-blue-300">
                @for ($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>

            <!-- Dropdown filter kuartal -->
            <select id="quarterFilter" class="border px-3 py-2 rounded-lg w-36 shadow-sm focus:ring focus:ring-blue-300">
                <option value="">Semua Periode</option>
                <option value="1">Januari - Maret</option>
                <option value="2">April - Juni</option>
                <option value="3">Juli - September</option>
                <option value="4">Oktober - Desember</option>
            </select>

            <!-- Tombol reset filter -->
            <button id="resetFilter" class="p-2 bg-blue-500 text-white rounded-lg shadow-sm hover:bg-blue-600">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>
</div>

<!-- Judul laporan yang akan diupdate secara dinamis -->
<p id="reportTitle" class="mt-4 text-lg font-semibold text-gray-700 mb-6 ml-5"></p>

<!-- Tabel daftar peminjaman -->
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5 shadow-lg rounded-lg overflow-hidden">
    <thead class="bg-blue-500 text-white">
        <tr>
            <th class="px-4 py-2 text-sm font-medium">No</th>
            <th class="px-4 py-2 text-sm font-medium">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium">Buku</th>
            <th class="px-4 py-2 text-sm font-medium">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium">Denda</th>
        </tr>
    </thead>
    <tbody id="borrowTable">
        <!-- Loop data peminjaman dari database -->
        @foreach ($borrows as $borrow)
        <tr class="border-b hover:bg-gray-100">
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $borrow->user->name }}</td>
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $borrow->book->title }}</td>
            <!-- Format tanggal pinjam -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center" data-date="{{ $borrow->tanggal_pinjam }}">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <!-- Format tanggal kembali -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <!-- Format nominal denda -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                Rp {{ number_format((int) $borrow->denda, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <!-- Footer tabel untuk menampilkan total denda -->
    <tfoot>
        <tr class="bg-gray-200">
            <td colspan="5" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Total Denda:</td>
            <td class="px-4 py-2 text-sm font-semibold text-gray-700 text-center" id="totalDenda">Rp 0</td>
        </tr>
    </tfoot>
</table>

<script>
    // Fungsi untuk menghitung total denda dari data yang tampil
    function updateTotalDenda() {
        let rows = document.querySelectorAll('#borrowTable tr:not([hidden])');
        let total = 0;

        // Jumlahkan semua denda dari baris yang tampil
        rows.forEach(row => {
            let dendaText = row.querySelector('td:last-child').innerText.replace('Rp ', '').replace(/\./g, '');
            total += parseInt(dendaText) || 0;
        });

        // Format angka ke format mata uang Indonesia
        document.getElementById('totalDenda').innerText = "Rp " + total.toLocaleString('id-ID');
    }

    // Fungsi untuk mengupdate judul laporan berdasarkan filter
    function updateReportTitle() {
        let year = document.getElementById('yearFilter').value;
        let quarter = document.getElementById('quarterFilter').value;
        let quarterText = "";

        // Konversi nilai kuartal ke teks
        switch (quarter) {
            case "1": quarterText = "Januari - Maret"; break;
            case "2": quarterText = "April - Juni"; break;
            case "3": quarterText = "Juli - September"; break;
            case "4": quarterText = "Oktober - Desember"; break;
        }

        // Buat judul berdasarkan filter yang dipilih
        let title = quarterText ? `Laporan Peminjaman ${quarterText} - Tahun ${year}` : "Laporan Peminjaman Semua Periode";
        document.getElementById('reportTitle').innerText = title;
    }

    // Fungsi untuk memfilter tabel berdasarkan kriteria pencarian dan filter
    function updateTableFilter() {
        let searchValue = document.getElementById('search').value.toLowerCase();
        let selectedQuarter = document.getElementById('quarterFilter').value;
        let selectedYear = document.getElementById('yearFilter').value;
        let rows = document.querySelectorAll('#borrowTable tr');

        // Filter setiap baris tabel
        rows.forEach(row => {
            let dateText = row.querySelector('[data-date]').getAttribute('data-date');
            let date = new Date(dateText);
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            let user = row.children[1].innerText.toLowerCase();
            let book = row.children[2].innerText.toLowerCase();

            // Tentukan apakah baris harus ditampilkan berdasarkan filter
            let show = (!selectedYear || year == selectedYear) &&
                       (!selectedQuarter ||
                           (selectedQuarter == "1" && month >= 1 && month <= 3) ||
                           (selectedQuarter == "2" && month >= 4 && month <= 6) ||
                           (selectedQuarter == "3" && month >= 7 && month <= 9) ||
                           (selectedQuarter == "4" && month >= 10 && month <= 12)) &&
                       (user.includes(searchValue) || book.includes(searchValue));

            // Tampilkan/sembunyikan baris berdasarkan hasil filter
            row.hidden = !show;
        });

        // Update total denda dan judul setelah filter
        updateTotalDenda();
        updateReportTitle();
    }

    // Fungsi untuk mereset semua filter ke nilai default
    function resetFilter() {
        document.getElementById('search').value = "";
        document.getElementById('yearFilter').value = "";
        document.getElementById('quarterFilter').value = "";
        updateTableFilter();
    }

    // Event listener untuk elemen filter dan pencarian
    document.getElementById('search').addEventListener('input', updateTableFilter);
    document.getElementById('quarterFilter').addEventListener('change', updateTableFilter);
    document.getElementById('yearFilter').addEventListener('change', updateTableFilter);
    document.getElementById('resetFilter').addEventListener('click', resetFilter);

    // Jalankan fungsi saat halaman pertama kali dimuat
    window.addEventListener('load', updateTotalDenda);
    window.addEventListener('load', updateReportTitle);
</script>

@endsection

@extends('layouts.main')

@section('contentPustakawan')
<!-- Menambahkan library Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Judul Halaman -->
<h2 class="text-xl font-bold text-gray-800 mt-10 mb-3 ml-5">Laporan Peminjaman</h2>

<!-- Section Filter dan Pencarian -->
<div class="bg-gray-100 p-4 rounded-lg shadow-sm mb-6">
    <div class="flex flex-col md:flex-row md:space-x-2 items-center gap-2">
        <!-- Input pencarian -->
        <input type="text" id="search" placeholder="Cari peminjam atau buku..."
            class="border px-3 py-2 rounded-lg w-full md:w-1/3 shadow-sm focus:ring focus:ring-blue-300">

        <!-- Filter tahun dan kuartal -->
        <div class="flex space-x-2">
            <!-- Dropdown filter tahun -->
            <select id="yearFilter" class="border px-3 py-2 rounded-lg w-24 shadow-sm focus:ring focus:ring-blue-300">
                @for ($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>

            <!-- Dropdown filter kuartal -->
            <select id="quarterFilter" class="border px-3 py-2 rounded-lg w-36 shadow-sm focus:ring focus:ring-blue-300">
                <option value="">Semua Periode</option>
                <option value="1">Januari - Maret</option>
                <option value="2">April - Juni</option>
                <option value="3">Juli - September</option>
                <option value="4">Oktober - Desember</option>
            </select>

            <!-- Tombol reset filter -->
            <button id="resetFilter" class="p-2 bg-blue-500 text-white rounded-lg shadow-sm hover:bg-blue-600">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>
</div>

<!-- Judul laporan yang akan diupdate secara dinamis -->
<p id="reportTitle" class="mt-4 text-lg font-semibold text-gray-700 mb-6 ml-5"></p>

<!-- Tabel daftar peminjaman -->
<table class="min-w-full table-auto bg-white border-separate border-spacing-0.5 shadow-lg rounded-lg overflow-hidden">
    <thead class="bg-blue-500 text-white">
        <tr>
            <th class="px-4 py-2 text-sm font-medium">No</th>
            <th class="px-4 py-2 text-sm font-medium">Pengguna</th>
            <th class="px-4 py-2 text-sm font-medium">Buku</th>
            <th class="px-4 py-2 text-sm font-medium">Tanggal Pinjam</th>
            <th class="px-4 py-2 text-sm font-medium">Tanggal Kembali</th>
            <th class="px-4 py-2 text-sm font-medium">Denda</th>
        </tr>
    </thead>
    <tbody id="borrowTable">
        <!-- Loop data peminjaman dari database -->
        @foreach ($borrows as $borrow)
        <tr class="border-b hover:bg-gray-100">
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $borrow->user->name }}</td>
            <td class="px-4 py-2 text-sm text-gray-600 text-center">{{ $borrow->book->title }}</td>
            <!-- Format tanggal pinjam -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center" data-date="{{ $borrow->tanggal_pinjam }}">
                {{ \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <!-- Format tanggal kembali -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d-m-Y') }}
            </td>
            <!-- Format nominal denda -->
            <td class="px-4 py-2 text-sm text-gray-600 text-center">
                Rp {{ number_format((int) $borrow->denda, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <!-- Footer tabel untuk menampilkan total denda -->
    <tfoot>
        <tr class="bg-gray-200">
            <td colspan="5" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Total Denda:</td>
            <td class="px-4 py-2 text-sm font-semibold text-gray-700 text-center" id="totalDenda">Rp 0</td>
        </tr>
    </tfoot>
</table>

<script>
    // Fungsi untuk menghitung total denda dari data yang tampil
    function updateTotalDenda() {
        let rows = document.querySelectorAll('#borrowTable tr:not([hidden])');
        let total = 0;

        // Jumlahkan semua denda dari baris yang tampil
        rows.forEach(row => {
            let dendaText = row.querySelector('td:last-child').innerText.replace('Rp ', '').replace(/\./g, '');
            total += parseInt(dendaText) || 0;
        });

        // Format angka ke format mata uang Indonesia
        document.getElementById('totalDenda').innerText = "Rp " + total.toLocaleString('id-ID');
    }

    // Fungsi untuk mengupdate judul laporan berdasarkan filter
    function updateReportTitle() {
        let year = document.getElementById('yearFilter').value;
        let quarter = document.getElementById('quarterFilter').value;
        let quarterText = "";

        // Konversi nilai kuartal ke teks
        switch (quarter) {
            case "1": quarterText = "Januari - Maret"; break;
            case "2": quarterText = "April - Juni"; break;
            case "3": quarterText = "Juli - September"; break;
            case "4": quarterText = "Oktober - Desember"; break;
        }

        // Buat judul berdasarkan filter yang dipilih
        let title = quarterText ? `Laporan Peminjaman ${quarterText} - Tahun ${year}` : "Laporan Peminjaman Semua Periode";
        document.getElementById('reportTitle').innerText = title;
    }

    // Fungsi untuk memfilter tabel berdasarkan kriteria pencarian dan filter
    function updateTableFilter() {
        let searchValue = document.getElementById('search').value.toLowerCase();
        let selectedQuarter = document.getElementById('quarterFilter').value;
        let selectedYear = document.getElementById('yearFilter').value;
        let rows = document.querySelectorAll('#borrowTable tr');

        // Filter setiap baris tabel
        rows.forEach(row => {
            let dateText = row.querySelector('[data-date]').getAttribute('data-date');
            let date = new Date(dateText);
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            let user = row.children[1].innerText.toLowerCase();
            let book = row.children[2].innerText.toLowerCase();

            // Tentukan apakah baris harus ditampilkan berdasarkan filter
            let show = (!selectedYear || year == selectedYear) &&
                       (!selectedQuarter ||
                           (selectedQuarter == "1" && month >= 1 && month <= 3) ||
                           (selectedQuarter == "2" && month >= 4 && month <= 6) ||
                           (selectedQuarter == "3" && month >= 7 && month <= 9) ||
                           (selectedQuarter == "4" && month >= 10 && month <= 12)) &&
                       (user.includes(searchValue) || book.includes(searchValue));

            // Tampilkan/sembunyikan baris berdasarkan hasil filter
            row.hidden = !show;
        });

        // Update total denda dan judul setelah filter
        updateTotalDenda();
        updateReportTitle();
    }

    // Fungsi untuk mereset semua filter ke nilai default
    function resetFilter() {
        document.getElementById('search').value = "";
        document.getElementById('yearFilter').value = "";
        document.getElementById('quarterFilter').value = "";
        updateTableFilter();
    }

    // Event listener untuk elemen filter dan pencarian
    document.getElementById('search').addEventListener('input', updateTableFilter);
    document.getElementById('quarterFilter').addEventListener('change', updateTableFilter);
    document.getElementById('yearFilter').addEventListener('change', updateTableFilter);
    document.getElementById('resetFilter').addEventListener('click', resetFilter);

    // Jalankan fungsi saat halaman pertama kali dimuat
    window.addEventListener('load', updateTotalDenda);
    window.addEventListener('load', updateReportTitle);
</script>

@endsection
