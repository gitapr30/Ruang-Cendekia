@extends('layouts.main') <!-- Ensure layout structure matches your project -->

@section('contentAdmin')
<div class="p-8 space-y-8 bg-white min-h-screen">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">📊 Dashboard Statistik</h1>

    <!-- Statistik Peminjaman Per Bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📅 Jumlah Peminjaman Per Bulan</h2>
        <div id="borrowChart" class="w-full h-80"></div>
        <button id="exportBorrow" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Export</button>
    </div>

    <!-- Statistik User Terdaftar Per Bulan -->
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-5xl mx-auto transition-all hover:shadow-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">👤 Jumlah User Terdaftar Per Bulan</h2>
        <div id="registeredUserChart" class="w-full h-80"></div>
        <button id="exportRegistered" class="mt-4 px-4 py-2 bg-green-500 text-white rounded">Export</button>
    </div>
</div>

<!-- ApexCharts Script
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPDF/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const commonChartOptions = {
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                }
            },
            xaxis: {
                categories: @json($data['months']),
                title: { text: 'Bulan', style: { fontSize: '14px', fontWeight: 'bold' } },
                labels: { style: { fontSize: '12px' } }
            },
            yaxis: { min: 0 },
            fill: { opacity: 0.9 },
            tooltip: { theme: 'dark' }
        }; -->

        // Borrow Chart
        // var borrowChart = new ApexCharts(document.querySelector("#borrowChart"), {
        //     ...commonChartOptions,
        //     series: [{ name: 'Peminjaman', data: @json($data['borrowCounts']) }],
        //     colors: ['#1E90FF']
        // });
        // borrowChart.render();

        // // Registered User Chart
        // var registeredUserChart = new ApexCharts(document.querySelector("#registeredUserChart"), {
        //     ...commonChartOptions,
        //     series: [{ name: 'User Terdaftar', data: @json($data['registeredUserCounts']) }],
        //     colors: ['#32CD32']
        // });
        // registeredUserChart.render();

        function exportToImage(chartId, fileName) {
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = fileName;
                link.click();
            });
        }

        function exportToPDF(chartId, fileName) {
            const { jsPDF } = window.jspdf;
            let pdf = new jsPDF();
            html2canvas(document.querySelector(chartId)).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                pdf.addImage(imgData, 'PNG', 10, 10, 180, 120);
                pdf.save(fileName);
            });
        }

        document.getElementById('exportBorrow').addEventListener('click', () => {
            exportToImage('#borrowChart', 'borrow_chart.png');
            exportToPDF('#borrowChart', 'borrow_chart.pdf');
        });

        document.getElementById('exportRegistered').addEventListener('click', () => {
            exportToImage('#registeredUserChart', 'registered_user_chart.png');
            exportToPDF('#registeredUserChart', 'registered_user_chart.pdf');
        });
    });
</script>
@endsection
