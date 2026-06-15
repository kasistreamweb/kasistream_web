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

.amount-mobile{
    font-size:clamp(14px, 3vw, 24px);
    word-break:break-all;
}

.section-gap{
    margin-bottom:24px;
}

/* FILTER CARD */
.filter-card{
    background:white;
    border-radius:15px;
    padding:24px;
    box-shadow:0 2px 12px rgba(0,0,0,.08);
    margin-bottom:24px;
}

.filter-card .filter-label{
    font-size:13px;
    font-weight:600;
    color:#6b7280;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:6px;
}

.filter-card .form-control{
    border-radius:10px;
    border:1px solid #e5e7eb;
    height:46px;
    font-size:14px;
    color:#111827;
}

.filter-card .form-control:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgba(99,102,241,.1);
}

.filter-divider{
    display:flex;
    align-items:center;
    justify-content:center;
    color:#9ca3af;
    font-size:13px;
    padding-top:24px;
}

.filter-info{
    background:#f8fafc;
    border-radius:10px;
    padding:12px 16px;
    font-size:13px;
    color:#6b7280;
    border:1px solid #e5e7eb;
    height:46px;
    display:flex;
    align-items:center;
    gap:8px;
}

.filter-info i{
    color:#6366f1;
}

.btn-filter{
    height:46px;
    border-radius:10px;
    font-weight:600;
    font-size:14px;
    background:linear-gradient(90deg,#4f46e5,#7c3aed);
    border:none;
    color:white;
    transition:.3s;
}

.btn-filter:hover{
    transform:translateY(-1px);
    box-shadow:0 4px 15px rgba(99,102,241,.35);
    color:white;
}

.btn-reset{
    height:46px;
    border-radius:10px;
    font-weight:600;
    font-size:14px;
    background:white;
    border:1px solid #e5e7eb;
    color:#6b7280;
    transition:.3s;
}

.btn-reset:hover{
    background:#f3f4f6;
    color:#111827;
}

@media print{
    body *{ visibility:hidden; }
    #reportArea, #reportArea *{ visibility:visible; }
    #reportArea{ position:absolute; left:0; top:0; width:100%; }
    .btn, form, .sidebar{ display:none !important; }
}

@media(max-width:768px){
    .content-card{ padding:15px; margin-bottom:16px; }
    .filter-card{ padding:16px; }
    .filter-divider{ padding-top:0; }
    .stat-card .card-body{ padding:12px; }
    h2{ font-size:20px; }
    h3{ font-size:18px; }
    h4{ font-size:16px; }
    .amount-mobile{ font-size:15px; }
    .table{ font-size:13px; }
}

</style>
</head>
<body>

<div class="container-fluid">
<div class="row">

@include('admin.layouts.sidebar')

<div class="col-md-10 p-4">
<div id="reportArea">

    <h2 class="mb-4">Laporan Sistem</h2>

    {{-- FILTER --}}
    <div class="filter-card">

        <form method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <div class="filter-label">Dari Tanggal</div>
                    <input
                        type="date"
                        name="dari"
                        class="form-control"
                        value="{{ request('dari') }}"
                    >
                </div>

                <div class="col-md-1 d-none d-md-block">
                    <div class="filter-divider">→</div>
                </div>

                <div class="col-md-4">
                    <div class="filter-label">Sampai Tanggal</div>
                    @if(request('dari') && request('sampai'))
                        <div class="filter-info">
                            <i class="fa-solid fa-calendar-check"></i>
                            {{ \Carbon\Carbon::parse(request('sampai'))->translatedFormat('d M Y') }}
                        </div>
                    @else
                        <div class="filter-info">
                            <i class="fa-solid fa-calendar"></i>
                            Pilih tanggal mulai terlebih dahulu
                        </div>
                    @endif
                    <input
                        type="hidden"
                        name="sampai"
                        value="{{ request('dari') ? request('dari', now()->format('Y-m-d')) : '' }}"
                    >
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>
                        Filter
                    </button>
                </div>

                <div class="col-md-1">
                    <a href="/admin-reports" class="btn btn-reset w-100">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>

            </div>
        </form>

    </div>

    {{-- CARD STATISTIK --}}
    <div class="row g-3 section-gap">
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow h-100">
                <div class="card-body">
                    <h6>Total User</h6>
                    <h3>{{ $totalUser }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow h-100">
                <div class="card-body">
                    <h6>Total Streamer</h6>
                    <h3>{{ $totalStreamer }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow h-100">
                <div class="card-body">
                    <h6>Total Donasi</h6>
                    <h3 class="amount-mobile">Rp {{ number_format($totalDonasi) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow h-100">
                <div class="card-body">
                    <h6>Total Withdraw</h6>
                    <h3 class="amount-mobile">Rp {{ number_format($totalWithdraw) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- PENDAPATAN PLATFORM & GATEWAY --}}
    <div class="row g-3 section-gap">
        <div class="col-md-4">
            <div class="card stat-card shadow h-100">
                <div class="card-body">
                    <h6>Pendapatan Platform</h6>
                    <h3 class="amount-mobile">Rp {{ number_format($totalPendapatanPlatform) }}</h3>
                    <small class="text-muted">
                        {{ number_format(($totalPendapatanPlatform / max($totalDonasi,1)) * 100,1) }}%
                        dari total donasi
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="content-card shadow h-100">
                <h4 class="mb-4">Statistik Gateway</h4>
                <div class="row text-center">
                    <div class="col-4">
                        <h2 class="text-success">{{ $gatewaySuccess }}</h2>
                        <small>Success</small>
                    </div>
                    <div class="col-4">
                        <h2 class="text-warning">{{ $gatewayPending }}</h2>
                        <small>Pending</small>
                    </div>
                    <div class="col-4">
                        <h2 class="text-danger">{{ $gatewayFailed }}</h2>
                        <small>Failed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RINGKASAN PLATFORM & TOP STREAMER --}}
    <div class="row g-3 section-gap">
        <div class="col-md-6">
            <div class="content-card shadow h-100">
                <h4 class="mb-3">Ringkasan Platform</h4>
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
                        <td>Rp {{ number_format($totalDonasi - $totalWithdraw) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-card shadow h-100">
                <h4 class="mb-3">Top Streamer</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                                    <td>Rp {{ number_format($streamer->total_donasi) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- EXPORT LAPORAN & TOP DONATUR --}}
    <div class="row g-3 section-gap">
        <div class="col-md-6">
            <div class="content-card shadow h-100">
                <h4 class="mb-4">Export Laporan</h4>

                <div class="d-grid gap-3 mb-4">
                    <a href="{{ route('admin.reports.excel') }}" class="btn btn-success">
                        <i class="fa-solid fa-file-excel me-2"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank" class="btn btn-secondary">
                        <i class="fa-solid fa-print me-2"></i>
                        Print Laporan
                    </a>
                </div>

                <hr>

                <p>
                    <strong>Periode :</strong><br>
                    {{ request('dari') ?: '-' }} s/d {{ request('sampai') ?: '-' }}
                </p>
                <p>Total Transaksi : <strong>{{ $totalTransaksi }}</strong></p>
                <p>Total Donasi : <strong>Rp {{ number_format($totalDonasi) }}</strong></p>
                <p class="mb-0">Pendapatan Platform : <strong>Rp {{ number_format($totalPendapatanPlatform) }}</strong></p>

            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card shadow h-100">
                <h4 class="mb-3">Top Donatur</h4>
                <div class="table-responsive">
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
                                    <td>Rp {{ number_format($donatur->total_donasi_user) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>{{-- tutup reportArea --}}
</div>{{-- tutup col-md-10 --}}
</div>{{-- tutup row --}}
</div>{{-- tutup container-fluid --}}

</body>
</html>