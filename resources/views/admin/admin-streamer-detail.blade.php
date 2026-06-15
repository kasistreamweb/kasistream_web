<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detail Streamer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>

body{
    background:#f4f6f9;
}

.content-card{
    background:white;
    border-radius:15px;
    padding:25px;
    margin-bottom:20px;
}

/* PROFILE HERO */
.profile-hero{
    display:flex;
    align-items:center;
    gap:25px;
    flex-wrap:wrap;
}

.profile-img{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #e0e7ff;
    flex-shrink:0;
    box-shadow:0 4px 15px rgba(99,102,241,.2);
}

.profile-info h2{
    font-size:24px;
    font-weight:700;
    color:#111827;
    margin-bottom:10px;
}

.profile-info .info-row{
    display:flex;
    flex-wrap:wrap;
    gap:8px 20px;
    font-size:14px;
    color:#374151;
}

.info-item{
    display:flex;
    align-items:center;
    gap:6px;
}

.info-item strong{
    color:#6b7280;
    font-weight:500;
}

/* STAT CARD */
.stat-card{
    background:white;
    border-radius:14px;
    padding:20px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
    height:100%;
    border-top:4px solid #6366f1;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 20px rgba(0,0,0,.1);
}

.stat-card h6{
    color:#6b7280;
    font-size:13px;
    font-weight:500;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:8px;
}

.stat-card h3{
    font-weight:700;
    font-size:20px;
    color:#111827;
    margin:0;
    word-break:break-word;
}

/* TABLE */
.table th{
    font-weight:600;
    font-size:13px;
    color:#6b7280;
    text-transform:uppercase;
    letter-spacing:.4px;
    background:#f8fafc;
    border-bottom:2px solid #e5e7eb;
}

.table td{
    vertical-align:middle;
    font-size:14px;
    color:#374151;
}

.table tbody tr:hover{
    background:#f8fafc;
}

.section-title{
    font-size:17px;
    font-weight:700;
    color:#111827;
    margin-bottom:16px;
    display:flex;
    align-items:center;
    gap:8px;
}

@media(max-width:768px){

    .profile-hero{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .profile-img{
        width:80px;
        height:80px;
    }

    .profile-info h2{
        font-size:18px;
    }

    .stat-card h3{
        font-size:16px;
    }

    .table{
        font-size:12px;
    }

    .content-card{
        padding:15px;
    }
}

</style>

</head>
<body>

<div class="container-fluid">
<div class="row">

@include('admin.layouts.sidebar')

<div class="col-md-10 p-4">

    {{-- PROFILE HERO --}}
    <div class="content-card shadow-sm">
        <div class="profile-hero">

            @if($streamer->foto)
                <img src="{{ asset('uploads/profile/'.$streamer->foto) }}" class="profile-img">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" class="profile-img">
            @endif

            <div class="profile-info">
                <h2>
                    {{ $streamer->name }}
                    @if($streamer->is_streamer)
                        <span class="badge bg-success ms-2" style="font-size:12px;">Streamer Aktif</span>
                    @else
                        <span class="badge bg-danger ms-2" style="font-size:12px;">Non Streamer</span>
                    @endif
                </h2>

                <div class="info-row">
                    <div class="info-item">
                        <i class="fa-solid fa-envelope" style="color:#6366f1;"></i>
                        <strong>Email:</strong> {{ $streamer->email }}
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-gamepad" style="color:#6366f1;"></i>
                        <strong>Game:</strong> {{ $streamer->game ?? '-' }}
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-user-tag" style="color:#6366f1;"></i>
                        <strong>Role:</strong> {{ $streamer->role }}
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-wallet" style="color:#10b981;"></i>
                        <strong>Saldo:</strong> Rp {{ number_format($streamer->balance ?? 0) }}
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-gift" style="color:#f59e0b;"></i>
                        <strong>Total Donasi:</strong> Rp {{ number_format($streamer->total_donasi ?? 0) }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="stat-card shadow-sm">
                <h6>Followers</h6>
                <h3>{{ number_format($streamer->followers) }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card shadow-sm">
                <h6>Total Donasi</h6>
                <h3>Rp {{ number_format($totalDonasi) }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card shadow-sm">
                <h6>Total Transaksi</h6>
                <h3>{{ number_format($totalTransaksi) }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card shadow-sm">
                <h6>Total Withdraw</h6>
                <h3>Rp {{ number_format($totalWithdraw) }}</h3>
            </div>
        </div>

    </div>

    {{-- RIWAYAT DONASI --}}
    <div class="content-card shadow-sm">
        <div class="section-title">
            <i class="fa-solid fa-gift" style="color:#6366f1;"></i>
            Riwayat Donasi Masuk
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Donatur</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donasiMasuk as $item)
                        <tr>
                            <td>{{ optional($item->user)->name ?? $item->guest_name }}</td>
                            <td>Rp {{ number_format($item->nominal) }}</td>
                            <td>{{ $item->payment_method }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'success' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Belum ada riwayat donasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RIWAYAT WITHDRAW --}}
    <div class="content-card shadow-sm">
        <div class="section-title">
            <i class="fa-solid fa-money-bill-transfer" style="color:#6366f1;"></i>
            Riwayat Withdraw
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Rekening</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawHistory as $item)
                        <tr>
                            <td>Rp {{ number_format($item->nominal) }}</td>
                            <td>{{ $item->bank }}</td>
                            <td>{{ $item->rekening }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'approved' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Belum ada riwayat withdraw.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
</div>

</body>
</html>