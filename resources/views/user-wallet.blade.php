<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet User - KAistream</title>

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
                            Wallet User
                        </h2>
                        <p>
                            Kelola saldo dan riwayat transaksi Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- SALDO -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="wallet-card h-100">
                        <div>
                            <span class="wallet-label">
                                Saldo Wallet
                            </span>
                            <div class="wallet-amount">
                                Rp {{ number_format(Auth::user()->wallet_balance ?? 0) }}
                            </div>
                            <div class="wallet-meta">
                                <span class="withdraw-status success">
                                    <i class="fa-solid fa-circle"></i>
                                    Saldo siap digunakan
                                </span>
                            </div>
                        </div>
                        <i class="fa-solid fa-wallet wallet-icon"></i>
                    </div>
                </div>
            </div>

            <!-- TOP UP BUTTON -->
            <div class="row mb-4">
                <div class="col-12">
                    <button
                        class="btn topup-wallet-btn"
                        onclick="alert('Fitur Top Up Segera Hadir')"
                    >
                        <i class="fa-solid fa-plus me-2"></i>
                        Top Up Saldo
                    </button>
                </div>
            </div>

            <!-- TRANSAKSI -->
            <div class="transaction-card user-transaction-card">
                <h4>
                    <i class="fa-solid fa-receipt"></i>
                    Riwayat Transaksi
                </h4>

                @forelse($transaksi as $item)
                    <div class="transaction-item user-transaction-item">
                        <div>
                            <div class="transaction-title user-transaction-title">
                                {{ $item['keterangan'] }}
                            </div>
                            <small class="user-transaction-date">
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="transaction-amount user-transaction-amount">
                                Rp {{ number_format($item['nominal']) }}
                            </div>
                            @if($item['status'] == 'pending')
                                <span class="status-badge pending">Menunggu</span>
                            @elseif($item['status'] == 'approved' || $item['status'] == 'success')
                                <span class="status-badge success">Berhasil</span>
                            @elseif($item['status'] == 'rejected' || $item['status'] == 'failed')
                                <span class="status-badge danger">Gagal</span>
                            @else
                                <span class="status-badge info">Selesai</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state user-empty">
                        <i class="fa-solid fa-wallet"></i>
                        <h5>Belum Ada Transaksi</h5>
                        <p>Transaksi akan muncul di sini setelah Anda melakukan top up atau donasi.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

</body>
</html>