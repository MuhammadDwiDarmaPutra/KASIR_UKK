@extends('main')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@if (Auth::user()->role == 'admin')
<div class="container mt-5">
    <h1 class="text-center mb-4">Selamat Datang, Administrator!</h1>

    <div class="row">
        <!-- Bar Chart -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h5 class="text-center">Jumlah Penjualan</h5>
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart + Legend -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h5 class="text-center">Persentase Penjualan Produk</h5>
                <canvas id="pieChart"></canvas>
                <div id="legend" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endif

@if (Auth::user()->role == 'kasir')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h4 class="mb-3">Selamat Datang, Petugas!</h4>
            <div class="card bg-light p-4">
                <h6 class="text-muted">Total Penjualan Hari Ini</h6>
                <h2 class="fw-bold">{{ $count }}</h2>
                <p class="text-muted">Jumlah Transaksi Member : {{ $member }}</p>
                <p class="text-muted">Jumlah Transaksi Non-Member : {{ $nonMember }} </p>
                <p class="text-muted">Jumlah total penjualan yang terjadi hari ini.</p>
                <p class="text-muted small">
                    @if ($updated && $updated->created_at)
                        Terakhir diperbarui: {{ $updated->created_at->format('d-m-Y H:i:s') }}
                    @else
                        Tidak ada Transaksi Hari ini
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if (Auth::user()->role == 'admin')
<script>
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Penjualan',
                data: {!! json_encode($dailyCounts) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'category', 
                    ticks: {
                        autoSkip: true,
                        maxRotation: 45,
                        minRotation: 45,
                        padding: 5
                    },
                    title: {
                        display: true,
                        text: 'Tanggal Penjualan',
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($productSales->keys()) !!},
            datasets: [{
                label: 'Distribusi Penjualan Produk',
                data: {!! json_encode($productSales->values()) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

@endif

@endsection