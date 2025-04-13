@extends('layouts.main') <!-- Layout utama yang digunakan untuk tampilan -->

@section('contentAdmin')
<!-- Dashboard Statistik untuk Admin -->
<div class="p-8 space-y-8 bg-gray-100 min-h-screen">
    <!-- Judul utama dashboard -->
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">📊 Dashboard Statistik</h1>

    <!-- Card statistik peminjaman per bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📅 Jumlah Peminjaman Per Bulan</h2>
        <!-- Container untuk chart peminjaman -->
        <div id="borrowChart" class="w-full h-96"></div>
        <!-- Tombol ekspor data peminjaman -->
        <button id="exportBorrow" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md">📥 Ekspor</button>
    </div>

    <!-- Card statistik user terdaftar per bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">👤 Jumlah User Terdaftar Per Bulan</h2>
        <!-- Container untuk chart user terdaftar -->
        <div id="registeredUserChart" class="w-full h-96"></div>
        <!-- Tombol ekspor data user terdaftar -->
        <button id="exportRegistered" class="mt-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md">📥 Ekspor</button>
    </div>
</div>

<!-- Library untuk chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Library untuk konversi HTML ke gambar -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
<!-- Library untuk membuat PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPDF/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Script dijalankan setelah DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Konfigurasi dasar untuk semua chart
        const commonChartOptions = {
            chart: {
                type: 'line',
                height: 400,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 6,
                strokeWidth: 2,
                hover: { size: 8 }
            },
            xaxis: {
                categories: @json($data['months']), // Data bulan dari controller
                title: { text: 'Bulan', style: { fontSize: '14px', fontWeight: 'bold' } },
                labels: { style: { fontSize: '12px' } }
            },
            yaxis: { min: 0 },
            tooltip: { theme: 'dark' },
            grid: { borderColor: '#ddd', strokeDashArray: 5 }
        };

        // Inisialisasi chart peminjaman
        var borrowChart = new ApexCharts(document.querySelector("#borrowChart"), {
            ...commonChartOptions,
            series: [{ name: 'Peminjaman', data: @json($data['borrowCounts']) }], // Data peminjaman dari controller
            colors: ['#1E90FF'] // Warna biru untuk chart peminjaman
        });
        borrowChart.render(); // Render chart peminjaman

        // Inisialisasi chart user terdaftar
        var registeredUserChart = new ApexCharts(document.querySelector("#registeredUserChart"), {
            ...commonChartOptions,
            series: [{ name: 'User Terdaftar', data: @json($data['registeredUserCounts']) }], // Data user dari controller
            colors: ['#32CD32'] // Warna hijau untuk chart user
        });
        registeredUserChart.render(); // Render chart user

        // Fungsi untuk ekspor chart ke gambar PNG
        function exportToImage(chartId, fileName) {
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = fileName;
                link.click();
            });
        }

        // Fungsi untuk ekspor chart ke PDF
        function exportToPDF(chartId, fileName) {
            const { jsPDF } = window.jspdf;
            let pdf = new jsPDF();
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                pdf.addImage(imgData, 'PNG', 10, 10, 180, 120);
                pdf.save(fileName);
            });
        }

        // Event listener untuk tombol ekspor peminjaman
        document.getElementById('exportBorrow').addEventListener('click', () => {
            exportToImage('#borrowChart', 'borrow_chart.png');
            exportToPDF('#borrowChart', 'borrow_chart.pdf');
        });

        // Event listener untuk tombol ekspor user terdaftar
        document.getElementById('exportRegistered').addEventListener('click', () => {
            exportToImage('#registeredUserChart', 'registered_user_chart.png');
            exportToPDF('#registeredUserChart', 'registered_user_chart.pdf');
        });
    });
</script>
@endsection


@section('contentPustakawan')
<!-- Dashboard Statistik untuk Pustakawan -->
<div class="p-8 space-y-8 bg-gray-100 min-h-screen">
    <!-- Judul utama dashboard -->
    <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Dashboard Statistik</h1>

    <!-- Card statistik peminjaman per bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📅 Jumlah Peminjaman Per Bulan</h2>
        <!-- Container untuk chart peminjaman -->
        <div id="borrowChart" class="w-full h-96"></div>
        <!-- Tombol ekspor data peminjaman -->
        <button id="exportBorrow" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md">📥 Ekspor</button>
    </div>

    <!-- Card statistik user terdaftar per bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">👤 Jumlah User Terdaftar Per Bulan</h2>
        <!-- Container untuk chart user terdaftar -->
        <div id="registeredUserChart" class="w-full h-96"></div>
        <!-- Tombol ekspor data user terdaftar -->
        <button id="exportRegistered" class="mt-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md">📥 Ekspor</button>
    </div>
</div>

<!-- Library untuk chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Library untuk konversi HTML ke gambar -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
<!-- Library untuk membuat PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPDF/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Script dijalankan setelah DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Konfigurasi dasar untuk semua chart
        const commonChartOptions = {
            chart: {
                type: 'line',
                height: 400,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 6,
                strokeWidth: 2,
                hover: { size: 8 }
            },
            xaxis: {
                categories: @json($data['months']), // Data bulan dari controller
                title: { text: 'Bulan', style: { fontSize: '14px', fontWeight: 'bold' } },
                labels: { style: { fontSize: '12px' } }
            },
            yaxis: { min: 0 },
            tooltip: { theme: 'dark' },
            grid: { borderColor: '#ddd', strokeDashArray: 5 }
        };

        // Inisialisasi chart peminjaman
        var borrowChart = new ApexCharts(document.querySelector("#borrowChart"), {
            ...commonChartOptions,
            series: [{ name: 'Peminjaman', data: @json($data['borrowCounts']) }], // Data peminjaman dari controller
            colors: ['#1E90FF'] // Warna biru untuk chart peminjaman
        });
        borrowChart.render(); // Render chart peminjaman

        // Inisialisasi chart user terdaftar
        var registeredUserChart = new ApexCharts(document.querySelector("#registeredUserChart"), {
            ...commonChartOptions,
            series: [{ name: 'User Terdaftar', data: @json($data['registeredUserCounts']) }], // Data user dari controller
            colors: ['#32CD32'] // Warna hijau untuk chart user
        });
        registeredUserChart.render(); // Render chart user

        // Fungsi untuk ekspor chart ke gambar PNG
        function exportToImage(chartId, fileName) {
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = fileName;
                link.click();
            });
        }

        // Fungsi untuk ekspor chart ke PDF
        function exportToPDF(chartId, fileName) {
            const { jsPDF } = window.jspdf;
            let pdf = new jsPDF();
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                pdf.addImage(imgData, 'PNG', 10, 10, 180, 120);
                pdf.save(fileName);
            });
        }

        // Event listener untuk tombol ekspor peminjaman
        document.getElementById('exportBorrow').addEventListener('click', () => {
            exportToImage('#borrowChart', 'borrow_chart.png');
            exportToPDF('#borrowChart', 'borrow_chart.pdf');
        });

        // Event listener untuk tombol ekspor user terdaftar
        document.getElementById('exportRegistered').addEventListener('click', () => {
            exportToImage('#registeredUserChart', 'registered_user_chart.png');
            exportToPDF('#registeredUserChart', 'registered_user_chart.pdf');
        });
    });
</script>
@endsection
