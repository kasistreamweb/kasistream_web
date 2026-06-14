<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Wallet Streamer - KAistream</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<link
    rel="stylesheet"
    href="{{ asset('css/kasistream.css') }}"
>

<link
    rel="stylesheet"
    href="{{ asset('css/wallet.css') }}"
>

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
                            Wallet Streamer
                        </h2>

                        <p>
                            Kelola saldo dan transaksi hasil donasi Anda.
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
                Saldo Wallet
            </span>

            <div class="wallet-amount">
                Rp {{ number_format(Auth::user()->balance) }}
            </div>

            <div class="wallet-meta">

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

            </div>

        </div>

        <i class="fa-solid fa-wallet wallet-icon"></i>

    </div>

</div>

                <!-- PERBAIKAN SPACING -->

                <div class="col-lg-5 wallet-side-column">

                    <div class="info-card mb-4">

                        <span>
                            Saldo Tersedia
                        </span>

                        <h3>
                            Rp {{ number_format(Auth::user()->balance) }}
                        </h3>

                    </div>

                    <div class="info-card verification-card">

                        <span>
                            Menunggu Verifikasi
                        </span>

                        <h3>
                            Rp {{ number_format($withdrawPending) }}
                        </h3>

                    </div>

                </div>

            </div>

            <!-- ACTION -->

            <div class="row action-row">

                <div class="col-md-6 mb-4">

                    @if(Auth::user()->balance >= 15000)

    <a
        href="/withdraw"
        class="action-link"
    >

        <div class="action-card">

            <i class="fa-solid fa-arrow-up-from-bracket"></i>

            <span>
                Tarik Dana
            </span>

        </div>

    </a>

@else

    <div
        class="action-card withdraw-disabled"
        title="Minimal saldo Rp 15.000 untuk melakukan penarikan"
    >

        <i class="fa-solid fa-lock"></i>

        <span>
            Tarik Dana
        </span>

        <small>
            Minimal Rp 15.000
        </small>

    </div>

@endif

                </div>

                <div class="col-md-6 mb-4">

                    <a
                        href="/wallet-history"
                        class="action-link"
                    >

                        <div class="action-card">

                            <i class="fa-solid fa-clock-rotate-left"></i>

                            <span>
                                Riwayat Transaksi
                            </span>

                        </div>

                    </a>

                </div>

            </div>

            <!-- TRANSAKSI -->

            <div class="transaction-card">

                <h4>

                    <i class="fa-solid fa-receipt"></i>

                    Transaksi Terakhir

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

                            @if($item['status'] == 'pending')

                                <span class="status-badge pending">
                                    Pending
                                </span>

                            @elseif($item['status'] == 'approved')

                                <span class="status-badge success">
                                    Approved
                                </span>

                            @elseif($item['status'] == 'rejected')

                                <span class="status-badge danger">
                                    Rejected
                                </span>

                            @else

                                <span class="status-badge info">
                                    Donasi
                                </span>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="empty-state">

                        <i class="fa-solid fa-wallet"></i>

                        <h5>
                            Belum Ada Transaksi
                        </h5>

                        <p>
                            Riwayat transaksi akan muncul di sini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

</body>
</html>