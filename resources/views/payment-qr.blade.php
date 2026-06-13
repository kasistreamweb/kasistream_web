<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pembayaran QRIS - KAsistream</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/kasistream.css') }}">

<link rel="stylesheet" href="{{ asset('css/payment-qr.css') }}">

</head>

<body>

<div class="container-fluid">

    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4 content-area">

            <div class="welcome-section mb-4">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="header-logo"
                    alt="Logo"
                >

                <h1>
                    💳 Pembayaran QRIS
                </h1>

                <p>
                    Scan QR Code berikut untuk menyelesaikan pembayaran donasi.
                </p>

            </div>

            <div class="row">

                <!-- QR SECTION -->
                <div class="col-lg-6 mb-4">

                    <div class="qr-card">

                        <div class="status-badge">

                            <i class="fa-solid fa-clock me-2"></i>

                            MENUNGGU PEMBAYARAN

                        </div>

                        <div class="qr-wrapper">

                            @if($donasi->qr_image)

                            <img
                                src="{{ $donasi->qr_image }}"
                                class="img-fluid rounded shadow"
                                alt="QR OnoPay"
                            >

                            @endif

                        </div>

                        <div class="payment-apps">

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                DANA
                            </div>

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                OVO
                            </div>

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                GoPay
                            </div>

                            <div>
                                <i class="fa-solid fa-building-columns"></i>
                                M-Banking
                            </div>

                        </div>

                    </div>

                </div>

                <!-- DETAIL -->
                <div class="col-lg-6">

                    <div class="detail-card">

                        <h3 class="section-title">

                            Detail Transaksi

                        </h3>

                        <div class="detail-row">

                            <span>ID Transaksi</span>

                            <strong>

                                #TRX{{ $donasi->id }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Streamer</span>

                            <strong>

                                {{ $donasi->streamer->name ?? '-' }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Nominal Donasi</span>

                            <strong>

                                Rp {{ number_format($donasi->nominal) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Fitur Tambahan</span>

                            <strong>

                                Rp {{ number_format($donasi->fitur_total ?? 0) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Biaya Admin</span>

                            <strong>

                                Rp {{ number_format($donasi->admin_fee ?? 0) }}

                            </strong>

                        </div>

                        <hr>

                        <div class="detail-row total-row">

                            <span>Total Pembayaran</span>

                            <strong>

                                Rp {{ number_format($donasi->grand_total ?? $donasi->nominal) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Status</span>

                            <span class="badge bg-warning text-dark">

                                PENDING

                            </span>

                        </div>

                        <div class="detail-row">

                            <span>Metode</span>

                            <strong>

                                QRIS

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Dibuat</span>

                            <strong>

                                {{ $donasi->created_at->format('d M Y H:i') }}

                            </strong>

                        </div>

                        <div class="mt-4">

                            <form
                                action="{{ route('payment.onopay',$donasi->id) }}"
                                method="POST"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100 mb-2"
                                >
                                    <i class="fa-solid fa-wallet me-2"></i>
                                    Cek Pembayaran
                                </button>
                            </form>

                            <a
                                href="/riwayat-donasi"
                                class="btn btn-outline-light w-100"
                            >

                                Kembali

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>