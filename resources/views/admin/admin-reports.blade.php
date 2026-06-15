<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>

body{
    background:#f4f6f9;
}

.stat-card{
    border:none;
    border-radius:15px;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-3px);
}

.content-card{
    background:white;
    border-radius:15px;
    padding:20px;
}

.table th{
    font-weight:600;
}

h3{
    font-weight:700;
}

.btn-success{
    border-radius:10px;
}

.btn-secondary{
    border-radius:10px;
}

.content-card{
    background:#fff;
    border-radius:15px;
    padding:20px;
}


@media print {

    body *{
        visibility:hidden;
    }

    #reportArea,
    #reportArea *{
        visibility:visible;
    }

    #reportArea{
        position:absolute;
        left:0;
        top:0;
        width:100%;
    }

    .btn,
    form,
    .sidebar{
        display:none !important;
    }
}

</style>

</head>
<body>

<div class="container-fluid">

<div class="row">

@include('admin.layouts.sidebar')

<div class="col-md-10 p-4">

    <div id="reportArea">

    <h2 class="mb-4">
        Laporan Sistem
    </h2>

    <!-- FILTER -->

    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-4">

            <input
                type="date"
                name="dari"
                class="form-control"
                value="{{ request('dari') }}"
            >

        </div>

        <div class="col-md-4">

            <input
                type="date"
                name="sampai"
                class="form-control"
                value="{{ request('sampai') }}"
            >

        </div>

        <div class="col-md-2">

            <button
                type="submit"
                class="btn btn-primary w-100"
            >

                <i class="fa-solid fa-filter"></i>
                Filter

            </button>

        </div>

        <div class="col-md-2">

            <a
                href="/admin-reports"
                class="btn btn-secondary w-100"
            >

                Reset

            </a>

        </div>

    </form>

    <!-- CARD STATISTIK -->

    <div class="row mb-4">

        <div class="col-6 col-md-3 mb-3">

            <div class="card stat-card shadow h-100">

                <div class="card-body">

                    <h6>Total User</h6>

                    <h3>{{ $totalUser }}</h3>

                </div>

            </div>

        </div>

        <div class="col-6 col-md-3 mb-3">

            <div class="card stat-card shadow h-100">

                <div class="card-body">

                    <h6>Total Streamer</h6>

                    <h3>{{ $totalStreamer }}</h3>

                </div>

            </div>

        </div>

        <div class="col-6 col-md-3 mb-3">

            <div class="card stat-card shadow h-100">

                <div class="card-body">

                    <h6>Total Donasi</h6>

                    <h3>
                        Rp {{ number_format($totalDonasi) }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-6 col-md-3 mb-3">

            <div class="card stat-card shadow h-100">

                <div class="card-body">

                    <h6>Total Withdraw</h6>

                    <h3>
                        Rp {{ number_format($totalWithdraw) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <!-- GATEWAY -->

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card stat-card shadow h-100">

                <div class="card-body">

                    <h6>Pendapatan Platform</h6>

                    <h3>
                        Rp {{ number_format($totalPendapatanPlatform) }}
                    </h3>

                    <small class="text-muted">

                        {{ number_format(($totalPendapatanPlatform / max($totalDonasi,1)) * 100,1) }}%
                        dari total donasi

                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-8">

            <div class="content-card shadow h-100">

                <h4 class="mb-4">

                    Statistik Gateway

                </h4>

                <div class="row text-center">

                    <div class="col-md-4">

                        <h2 class="text-success">

                            {{ $gatewaySuccess }}

                        </h2>

                        <small>Success</small>

                    </div>

                    <div class="col-md-4">

                        <h2 class="text-warning">

                            {{ $gatewayPending }}

                        </h2>

                        <small>Pending</small>

                    </div>

                    <div class="col-md-4">

                        <h2 class="text-danger">

                            {{ $gatewayFailed }}

                        </h2>

                        <small>Failed</small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- RINGKASAN DAN TOP -->

<!-- BARIS ATAS -->
<div class="row">

    <!-- RINGKASAN PLATFORM -->
    <div class="col-md-6">

        <div class="content-card shadow">

            <h4 class="mb-3">
                Ringkasan Platform
            </h4>

            <table class="table">

                <tr>
                    <th>Total Transaksi</th>
                    <td>{{ $totalTransaksi }}</td>
                </tr>

                <tr>
                    <th>Total Donasi Masuk</th>
                    <td>Rp {{ number_format($totalDonasi) }}</td>
                </tr>

                <tr>
                    <th>Total Withdraw</th>
                    <td>Rp {{ number_format($totalWithdraw) }}</td>
                </tr>

                <tr>
                    <th>Saldo Platform</th>
                    <td>
                        Rp {{ number_format($totalDonasi - $totalWithdraw) }}
                    </td>
                </tr>

            </table>

        </div>

    </div>

    <!-- TOP STREAMER -->
    <div class="col-md-6">

        <div class="content-card shadow">

            <h4 class="mb-3">
                Top Streamer
            </h4>

            <div class="table-responsive">
    <table class="table table-hover report-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Game</th>
                        <th>Total Donasi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($topStreamer as $streamer)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $streamer->name }}</td>

                            <td>{{ $streamer->game }}</td>

                            <td>
                                Rp {{ number_format($streamer->total_donasi) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>
            </div>

        </div>

    </div>

</div>

<!-- BARIS BAWAH -->
<div class="row mt-4">

    <!-- EXPORT LAPORAN -->
    <div class="col-md-6">

        <div class="content-card shadow h-100">

            <h4 class="mb-4">
                Export Laporan
            </h4>

            <div class="d-grid gap-3 mb-4">

                <a href="{{ route('admin.reports.excel') }}"
                class="btn btn-success">

                    <i class="fa-solid fa-file-excel me-2"></i>
                    Export Excel

                </a>

                    <a href="{{ route('admin.reports.print', request()->all()) }}"
   target="_blank"
   class="btn btn-secondary">

    <i class="fa-solid fa-print me-2"></i>
    Print Laporan

</a>

            </div>

            <hr>

            <p>
                <strong>Periode :</strong><br>
                {{ request('dari') ?: '-' }}
                s/d
                {{ request('sampai') ?: '-' }}
            </p>

            <p>
                Total Transaksi :
                <strong>{{ $totalTransaksi }}</strong>
            </p>

            <p>
                Total Donasi :
                <strong>
                    Rp {{ number_format($totalDonasi) }}
                </strong>
            </p>

            <p class="mb-0">
                Pendapatan Platform :
                <strong>
                    Rp {{ number_format($totalPendapatanPlatform) }}
                </strong>
            </p>

        </div>

    </div>

    <!-- TOP DONATUR -->
    <div class="col-md-6">

        <div class="content-card shadow h-100">

            <h4 class="mb-3">
                Top Donatur
            </h4>

            <table class="table table-hover">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Total Donasi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($topDonatur as $donatur)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $donatur->name }}</td>

                            <td>
                                Rp {{ number_format($donatur->total_donasi_user) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div> 

</div>

</div>

</div>

</div>

</body>
</html>