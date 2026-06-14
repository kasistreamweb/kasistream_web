@php

$fiturTotal = array_sum($fitur ?? []);

$adminFee = 1500;

$grandTotal =
    $nominal +
    $fiturTotal +
    $adminFee;

$walletCukup =
    auth()->check()
    &&
    auth()->user()->balance >= $grandTotal;

@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pilih Metode Pembayaran</title>

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
        href="{{ asset('css/payment-method.css') }}">

</head>

<body>


<div class="container-fluid">

    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4 content-area">

            <div class="welcome-section">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="header-logo"
                >

                <h1>
                    💳 Pilih Metode Pembayaran
                </h1>

                <p>
                    Pilih metode pembayaran untuk melanjutkan donasi.
                </p>

            </div>

            <div class="payment-card">

                <form
                    action="/payment-detail"
                    method="POST"
                >

                    @csrf

<input
    type="hidden"
    name="streamer_id"
    value="{{ $streamer_id }}"
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

@foreach($fitur ?? [] as $item)

<input
    type="hidden"
    name="fitur[]"
    value="{{ $item }}"
>

@endforeach

<div class="payment-list">

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="Wallet"
                                {{ $walletCukup ? 'checked' : 'disabled' }}
                            >

                            <div>

                                <h5>💰 Wallet KAsistream</h5>

                                @if(!$walletCukup)

                                    <small class="text-danger">
                                        Saldo wallet tidak mencukupi
                                    </small>

                                @else

                                    <small>
                                        Gunakan saldo wallet
                                    </small>

                                @endif

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                        <label class="payment-item">

                        <input
                            type="radio"
                            name="metode"
                            value="QRIS"
                        >

                        <div>

                            <h5>📱 QRIS</h5>

                            <small>
                                Scan QR menggunakan DANA, OVO, GoPay, ShopeePay, Mobile Banking
                            </small>

                        </div>

                        <div class="payment-arrow">

                            <i class="fa-solid fa-chevron-right"></i>

                        </div>

                    </label>

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="DANA"
                            >

                            <div>

                                <h5>🟦 DANA</h5>

                                <small>
                                    Bayar dengan DANA
                                </small>

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="OVO"
                            >

                            <div>

                                <h5>🟣 OVO</h5>

                                <small>
                                    Bayar dengan OVO
                                </small>

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="GoPay"
                            >

                            <div>

                                <h5>🟢 GoPay</h5>

                                <small>
                                    Bayar dengan GoPay
                                </small>

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="ShopeePay"
                            >

                            <div>

                                <h5>🟠 ShopeePay</h5>

                                <small>
                                    Bayar dengan ShopeePay
                                </small>

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                        <label class="payment-item">

                            <input
                                type="radio"
                                name="metode"
                                value="Transfer Bank"
                            >

                            <div>

                                <h5>🏦 Transfer Bank</h5>

                                <small>
                                    Virtual Account
                                </small>

                            </div>

                            <div class="payment-arrow">

                                <i class="fa-solid fa-chevron-right"></i>

                            </div>

                        </label>

                    </div>

                    <div class="text-end mt-4">

                        <button
                            type="submit"
                            class="btn lanjut-btn"
                        >
                            Lanjutkan →
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>

