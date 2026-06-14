<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pilih detail Pembayaran</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <link
        rel="stylesheet"
        href="{{ asset('css/kasistream.css') }}">

    <link
        rel="stylesheet"
        href="{{ asset('css/payment-detail.css') }}">

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
                >

                <h1>
                    📋 Detail Pembayaran
                </h1>

                <p>
                    Periksa kembali detail donasi sebelum melanjutkan pembayaran.
                </p>

            </div>

            <div class="row">

                {{-- KIRI --}}
                <div class="col-lg-6 mb-4">

                    <div class="detail-card">

                        <div class="streamer-info">

                            @if($streamer && $streamer->foto)

                                <img
                                    src="{{ asset('uploads/profile/'.$streamer->foto) }}"
                                    class="streamer-img"
                                >

                            @endif

                            <div>

                                <div class="small text-muted">
                                    Kepada Streamer
                                </div>

                                <h2>
                                    {{ $streamer->name ?? '-' }}
                                </h2>

                                <div class="game-text">
                                    🎮 {{ $streamer->game }}
                                </div>

                            </div>

                        </div>

                        <hr>

                        <h4 class="section-title">
    Rincian Donasi
</h4>

<div class="detail-row">

    <span>Nominal Donasi</span>

    <strong>
        Rp {{ number_format($nominal) }}
    </strong>

</div>

@if(count($fitur ?? []))

<div class="detail-row">

    <span>Fitur Tambahan</span>

    <span>
        Rp {{ number_format($fiturTotal) }}
    </span>

</div>

@foreach($fitur as $item)

<div class="detail-row">

    <span>

        @if($item == 5000)
            🎤 Voice Note
        @elseif($item == 10000)
            🎥 Video Pendek
        @elseif($item == 15000)
            ✨ Highlight Donasi
        @elseif($item == 20000)
            📌 Pin Pesan
        @endif

    </span>

    <span class="text-success">
        ✓
    </span>

</div>

@endforeach

@endif

<div class="detail-row">

    <span>Pesan</span>

    <strong>
        {{ $pesan ?: '-' }}
    </strong>

</div>

<div class="detail-row">

    <span>Metode Pembayaran</span>

    <strong>
        {{ $metode }}
    </strong>

</div>

                    </div>

                </div>

                {{-- KANAN --}}
                <div class="col-lg-6">

                    <div class="detail-card">

                        <h3 class="section-title">
                            Ringkasan Pembayaran
                        </h3>

                        <div class="detail-row">

                            <span>Subtotal</span>

                            <strong>
                                Rp {{ number_format($nominal) }}
                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Biaya Admin</span>

                            <strong>
                                Rp 1.500
                            </strong>

                        </div>

                        <hr>

                        <div class="total-box">

                            <div>
                                Total Pembayaran
                            </div>

                            <h1>
                                Rp {{ number_format($grandTotal) }}
                            </h1>

                        </div>

<form
    action="/konfirmasi-pembayaran"
    method="POST"
>

    @csrf

    <input
        type="hidden"
        name="streamer_id"
        value="{{ $streamer->id }}"
    >

    <input
        type="hidden"
        name="nominal"
        value="{{ $nominal }}"
    >

    <input
        type="hidden"
        name="pesan"
        value="{{ $pesan }}"
    >

    <input
    type="hidden"
    name="guest_name"
    value="{{ $guest_name ?? '' }}"
>

<input
    type="hidden"
    name="guest_phone"
    value="{{ $guest_phone ?? '' }}"
>

    <input
        type="hidden"
        name="metode"
        value="{{ $metode }}"
    >

    <input
        type="hidden"
        name="admin_fee"
        value="{{ $adminFee }}"
    >

    <input
        type="hidden"
        name="grand_total"
        value="{{ $grandTotal }}"
    >

    @foreach($fitur ?? [] as $item)

        <input
            type="hidden"
            name="fitur[]"
            value="{{ $item }}"
        >

    @endforeach

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

    <div class="payment-action">

    <button class="bayar-btn w-100">

        🔒 Konfirmasi & Bayar

    </button>

</div>

</form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>