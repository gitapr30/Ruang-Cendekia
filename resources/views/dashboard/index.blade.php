@extends('layouts.main') <!-- Make sure this layout matches your project structure -->

@section('contentAdmin')
<div class="p-8 space-y-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard Statistik</h1>

    <!-- Statistik Peminjaman Per Bulan -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Jumlah Peminjaman Per Bulan</h2>
        <div id="borrowChart" class="w-full h-72"></div> <!-- ApexCharts container -->
    </div>

    <!-- Statistik User Terdaftar Per Bulan -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Jumlah User Terdaftar Per Bulan</h2>
        <div id="registeredUserChart" class="w-full h-72"></div> <!-- ApexCharts container -->
    </div>
</div>

<!-- ApexCharts Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Borrow Chart
        var borrowOptions = {
            chart: {
                type: 'bar',
                height: 350,
            },
            series: [{
                name: 'Jumlah Peminjaman',
                data: @json($data['borrowCounts']) // Borrow count per month
            }],
            xaxis: {
                categories: @json($data['months']), // Label for months (e.g., January, February)
                title: {
                    text: 'Bulan',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Peminjaman',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                min: 0
            },
            fill: {
                opacity: 0.6,
                colors: ['#36A2EB']
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return `Jumlah Peminjaman: ${value}`;
                    }
                }
            }
        };

        var borrowChart = new ApexCharts(document.querySelector("#borrowChart"), borrowOptions);
        borrowChart.render();

        // Registered User Chart
        var registeredUserOptions = {
            chart: {
                type: 'bar',
                height: 350,
            },
            series: [{
                name: 'Jumlah User Terdaftar',
                data: @json($data['registeredUserCounts']) // Registered user count per month
            }],
            xaxis: {
                categories: @json($data['months']), // Label for months (e.g., January, February)
                title: {
                    text: 'Bulan',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah User Terdaftar',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                min: 0
            },
            fill: {
                opacity: 0.6,
                colors: ['#4CAF50'] // Adjust the color opacity
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return `Jumlah User Terdaftar: ${value}`;
                    }
                }
            }
        };

        var registeredUserChart = new ApexCharts(document.querySelector("#registeredUserChart"), registeredUserOptions);
        registeredUserChart.render();
    });
</script>
@endsection
