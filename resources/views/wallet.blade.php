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
                                Kelola saldo dan riwayat transaksi Anda.
                            @endif
                        </p>
                    </div>          
                </div>
            </div>

            <!-- SALDO -->
            <div class="row">
                <!-- Card Utama - Full width untuk user biasa -->
                @if(Auth::user()->is_streamer)
                    <div class="col-lg-7 mb-4">
                @else
                    <div class="col-lg-12 mb-4">
                @endif
                    <!-- Card Utama - tetap pakai wallet-card sebagai base -->
                    @if(Auth::user()->is_streamer)
                        <div class="wallet-card h-100">
                    @else
                        <div class="wallet-card user-wallet-card h-100">
                    @endif
                        <div>
                            <!-- Label Saldo - SAMA UNTUK STREAMER DAN USER -->
                            <span class="wallet-label">
                                Saldo Wallet
                            </span>

                            <!-- Jumlah Saldo - PAKAI wallet_balance UNTUK USER -->
                            <div class="wallet-amount">
                                @if(Auth::user()->is_streamer)
                                    Rp {{ number_format(Auth::user()->balance) }}
                                @else
                                    Rp {{ number_format(Auth::user()->wallet_balance ?? 0) }}
                                @endif
                            </div>

                            <!-- Wallet Meta - Status Saldo -->
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
                                        <i class="fa-solid fa-circle"></i>
                                        Saldo siap digunakan
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="fa-solid fa-wallet wallet-icon"></i>
                    </div>
                </div>

                <!-- Kolom Kanan - HANYA UNTUK STREAMER -->
                @if(Auth::user()->is_streamer)
                    <div class="col-lg-5 wallet-side-column">
                        <div class="info-card mb-4">
                            <span>Saldo Tersedia</span>
                            <h3>
                                Rp {{ number_format(Auth::user()->balance) }}
                            </h3>
                        </div>

                        <!-- Card Menunggu Verifikasi - Hanya untuk Streamer -->
                        <div class="info-card verification-card">
                            <span>
                                Menunggu Verifikasi
                            </span>
                            <h3>
                                Rp {{ number_format($withdrawPending) }}
                            </h3>
                        </div>
                    </div>
                @endif
            </div>
            <!-- END SALDO ROW -->

            <!-- TOP UP ROW - KHUSUS USER BIASA -->
            @if(!Auth::user()->is_streamer)
                <div class="row mb-4">
                    <div class="col-12">
                        <button
                            class="btn topup-wallet-btn"
                            onclick="alert('Fitur Top Up akan segera hadir')"
                        >
                            <i class="fa-solid fa-plus me-2"></i>
                            Top Up Saldo
                        </button>
                    </div>
                </div>
            @endif

            <!-- ACTION ROW - HANYA UNTUK STREAMER -->
            @if(Auth::user()->is_streamer)
                <div class="row action-row">
                    <div class="col-md-6 mb-4">
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
                    </div>

                    <div class="col-md-6 mb-4">
                        <a href="/wallet-history" class="action-link">
                            <div class="action-card">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Riwayat Transaksi</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <!-- TRANSAKSI -->
            <!-- Transaction Card - tetap pakai transaction-card sebagai base -->
            @if(Auth::user()->is_streamer)
                <div class="transaction-card">
            @else
                <div class="transaction-card user-transaction-card">
            @endif
                <h4>
                    <i class="fa-solid fa-receipt"></i>
                    @if(Auth::user()->is_streamer)
                        Transaksi Terakhir
                    @else
                        Riwayat Transaksi
                    @endif
                </h4>

                @forelse($transaksi as $item)
                    <!-- Transaction Item - tetap pakai transaction-item sebagai base -->
                    <div class="transaction-item
                        @if(!Auth::user()->is_streamer)
                            user-transaction-item
                        @endif
                    ">
                        <div>
                            <div class="transaction-title
                                @if(!Auth::user()->is_streamer)
                                    user-transaction-title
                                @endif
                            ">
                                {{ $item['keterangan'] }}
                            </div>
                            <small class="@if(!Auth::user()->is_streamer) user-transaction-date @endif">
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="transaction-amount
                                @if(!Auth::user()->is_streamer)
                                    user-transaction-amount
                                @endif
                            ">
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
                                <!-- Untuk user biasa, status transaksi -->
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
                    <!-- Empty State - tetap pakai empty-state sebagai base -->
                    <div class="empty-state
                        @if(!Auth::user()->is_streamer)
                            user-empty
                        @endif
                    ">
                        <i class="fa-solid fa-wallet"></i>
                        <h5>
                            @if(Auth::user()->is_streamer)
                                Belum Ada Transaksi
                            @else
                                Belum Ada Transaksi
                            @endif
                        </h5>
                        <p>
                            @if(Auth::user()->is_streamer)
                                Riwayat transaksi akan muncul di sini.
                            @else
                                Transaksi akan muncul di sini setelah Anda melakukan top up atau donasi.
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