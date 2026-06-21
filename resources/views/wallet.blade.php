<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Wallet - KAistream</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/kasistream.css') }}">
<link rel="stylesheet" href="{{ asset('css/wallet.css') }}">

</head>
<body>

<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-10 p-4">

            <!-- HEADER -->
            <div class="wallet-header mb-4">
                <div class="wallet-header-content">
                    <div>
                        <h2>
                            <i class="fa-solid fa-wallet"></i>
                            @if(Auth::user()->is_streamer)
                                Wallet Streamer
                            @else
                                Wallet User
                            @endif
                        </h2>

                        <p>
                            @if(Auth::user()->is_streamer)
                                Kelola saldo dan transaksi hasil donasi Anda.
                            @else
                                Lihat riwayat donasi dan streamer yang Anda dukung.
                            @endif
                        </p>
                    </div>          
                </div>
            </div>

            <!-- SALDO -->
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="wallet-card h-100">
                        <div>
                            <span class="wallet-label">
                                @if(Auth::user()->is_streamer)
                                    Saldo Wallet
                                @else
                                    Total Donasi Diberikan
                                @endif
                            </span>

                            <div class="wallet-amount">
                                @if(Auth::user()->is_streamer)
                                    Rp {{ number_format(Auth::user()->balance) }}
                                @else
                                    Rp {{ number_format($totalDonasi ?? 0) }}
                                @endif
                            </div>

                            <div class="wallet-meta">
                                @if(Auth::user()->is_streamer)
                                    @if(Auth::user()->balance >= 15000)
                                        <span class="withdraw-status success">
                                            <i class="fa-solid fa-circle"></i>
                                            Saldo dapat ditarik
                                        </span>
                                    @else
                                        <span class="withdraw-status danger">
                                            <i class="fa-solid fa-circle"></i>
                                            Saldo tidak dapat ditarik
                                        </span>
                                        <div class="withdraw-note">
                                            Minimal withdraw Rp 15.000
                                        </div>
                                    @endif
                                @else
                                    <span class="withdraw-status success">
                                        <i class="fa-solid fa-heart"></i>
                                        Mendukung {{ $totalStreamerDidukung ?? 0 }} streamer
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="fa-solid fa-wallet wallet-icon"></i>
                    </div>
                </div>

                <!-- PERBAIKAN SPACING -->
                <div class="col-lg-5 wallet-side-column">
                    <!-- HANYA UNTUK STREAMER -->
                    @if(Auth::user()->is_streamer)
                        <div class="info-card mb-4">
                            <span>Saldo Tersedia</span>
                            <h3>
                                Rp {{ number_format(Auth::user()->balance) }}
                            </h3>
                        </div>
                    @endif

                    <!-- Card Menunggu Verifikasi - Hanya untuk Streamer -->
                    @if(Auth::user()->is_streamer)
                        <div class="info-card verification-card">
                            <span>
                                Menunggu Verifikasi
                            </span>
                            <h3>
                                Rp {{ number_format($withdrawPending) }}
                            </h3>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ACTION -->
            <div class="row action-row">
                <div class="col-md-6 mb-4">
                    <!-- Tombol Tarik Dana - Hanya untuk Streamer -->
                    @if(Auth::user()->is_streamer)
                        @if(Auth::user()->balance >= 15000)
                            <a href="/withdraw" class="action-link">
                                <div class="action-card">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    <span>Tarik Dana</span>
                                </div>
                            </a>
                        @else
                            <div class="action-card withdraw-disabled" 
                                 title="Minimal saldo Rp 15.000 untuk melakukan penarikan">
                                <i class="fa-solid fa-lock"></i>
                                <span>Tarik Dana</span>
                                <small>Minimal Rp 15.000</small>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="col-md-6 mb-4">
                    <!-- Tombol Riwayat Transaksi / Riwayat Donasi -->
                    @if(Auth::user()->is_streamer)
                        <a href="/wallet-history" class="action-link">
                            <div class="action-card">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Riwayat Transaksi</span>
                            </div>
                        </a>
                    @else
                        <a href="/riwayat-donasi" class="action-link">
                            <div class="action-card">
                                <i class="fa-solid fa-heart"></i>
                                <span>Riwayat Donasi</span>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <!-- STATISTIK UNTUK USER BIASA (DIUBAH) -->
            @unless(Auth::user()->is_streamer)
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="user-stat-card">
                            <i class="fa-solid fa-heart"></i>
                            <h6>Total Donasi</h6>
                            <strong>
                                Rp {{ number_format($totalDonasi ?? 0) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="user-stat-card">
                            <i class="fa-solid fa-users"></i>
                            <h6>Streamer Didukung</h6>
                            <strong>
                                {{ $totalStreamerDidukung ?? 0 }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="user-stat-card">
                            <i class="fa-solid fa-clock"></i>
                            <h6>Total Aktivitas</h6>
                            <strong>
                                {{ count($transaksi) }}
                            </strong>
                        </div>
                    </div>
                </div>
            @endunless

            <!-- TRANSAKSI -->
            <div class="transaction-card">
                <h4>
                    <i class="fa-solid fa-receipt"></i>
                    @if(Auth::user()->is_streamer)
                        Transaksi Terakhir
                    @else
                        Donasi Terakhir
                    @endif
                </h4>

                @forelse($transaksi as $item)
                    <div class="transaction-item">
                        <div>
                            <div class="transaction-title">
                                {{ $item['keterangan'] }}
                            </div>
                            <small>
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="transaction-amount">
                                Rp {{ number_format($item['nominal']) }}
                            </div>
                            
                            @if(Auth::user()->is_streamer)
                                @if($item['status'] == 'pending')
                                    <span class="status-badge pending">Pending</span>
                                @elseif($item['status'] == 'approved')
                                    <span class="status-badge success">Approved</span>
                                @elseif($item['status'] == 'rejected')
                                    <span class="status-badge danger">Rejected</span>
                                @else
                                    <span class="status-badge info">Donasi</span>
                                @endif
                            @else
                                <!-- Untuk user biasa, status donasi -->
                                @if($item['status'] == 'pending')
                                    <span class="status-badge pending">Menunggu</span>
                                @elseif($item['status'] == 'approved' || $item['status'] == 'success')
                                    <span class="status-badge success">Berhasil</span>
                                @elseif($item['status'] == 'rejected' || $item['status'] == 'failed')
                                    <span class="status-badge danger">Gagal</span>
                                @else
                                    <span class="status-badge info">Selesai</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa-solid fa-wallet"></i>
                        <h5>
                            @if(Auth::user()->is_streamer)
                                Belum Ada Transaksi
                            @else
                                Belum Ada Donasi
                            @endif
                        </h5>
                        <p>
                            @if(Auth::user()->is_streamer)
                                Riwayat transaksi akan muncul di sini.
                            @else
                                Mulai donasi ke streamer favorit Anda!
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

</body>
</html>