<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pembayaran Berhasil - KAsistream</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<link
        rel="stylesheet"
        href="{{ asset('css/kasistream.css') }}">

    <link
        rel="stylesheet"
        href="{{ asset('css/payment-success.css') }}">

</head>
<body>

<div class="success-wrapper">

    <div class="success-card">

        <div class="text-center">

            <div class="success-icon">

                <i class="fa-solid fa-check"></i>

            </div>

            <h1 class="success-title">

                Pembayaran Berhasil!

            </h1>

            <p class="success-subtitle">

                Terima kasih atas dukunganmu 🎉<br>
                Donasimu sangat berarti bagi streamer.

            </p>

        </div>

        <div class="detail-box">

            <h4>

                Detail Transaksi

            </h4>

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
        Rp {{ number_format($donasi->fitur_total) }}
    </strong>
</div>

<div class="detail-row">
    <span>Biaya Admin</span>
    <strong>
        Rp {{ number_format($donasi->admin_fee) }}
    </strong>
</div>

<div class="detail-row">
    <span>Total Pembayaran</span>
    <strong class="total-payment">
        Rp {{ number_format($donasi->grand_total) }}
    </strong>
</div>

        <div class="detail-row">

            <span>Metode Pembayaran</span>

            <strong>

                Wallet

            </strong>

        </div>

        <div class="detail-row">

            <span>Waktu Pembayaran</span>

            <strong>

                {{ $donasi->created_at->format('d M Y H:i') }}

            </strong>

        </div>
        </div>

        <div class="row mt-4">

    <div class="col-md-6 mb-3">

        <a
            href="/streamers"
            class="btn btn-home text-white w-100"
        >

            <i class="fa-solid fa-gift me-2"></i>

            Donasi Lagi

        </a>

    </div>

    <div class="col-md-6 mb-3">

        @auth

            <a
                href="/riwayat-donasi"
                class="btn btn-history text-white w-100"
            >

                <i class="fa-solid fa-clock-rotate-left me-2"></i>

                Lihat Riwayat Donasi

            </a>

        @else

            <a
                href="/register"
                class="btn btn-history text-white w-100"
            >

                <i class="fa-solid fa-user-plus me-2"></i>

                Buat Akun & Simpan Riwayat

            </a>

        @endauth

    </div>

</div>
    </div>

</div>

</body>
</html>