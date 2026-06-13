<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - KAistream</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link
    rel="stylesheet"
    href="{{ asset('css/kasistream.css') }}"
>
    <link
    rel="stylesheet"
    href="{{ asset('css/donasi.css') }}"
>

</head>
<body>

<div class="container-fluid">

    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4 content-area">

        <div class="welcome-section mb-4">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="KAsistream"
        class="header-logo"
    >

    <h1>
        🎁 Donasi Streamer
    </h1>

    <p>
        Berikan dukungan kepada streamer favorit melalui KAsistream.
    </p>

</div>

            <div class="donate-card">

                <form
    action="/payment-method"
    method="POST"
>

    @csrf

    <input
        type="hidden"
        name="streamer_id"
        value="{{ $streamer->id }}"
    >
@guest



<div class="mb-3">

    <label class="form-label">

        Nama Donatur

    </label>

    <input
        type="text"
        name="guest_name"
        class="form-control"
        required
        placeholder="Masukkan nama Anda"
    >

</div>

@endguest

        <div class="streamer-header">

    <div class="row align-items-center">

        <div class="col-lg-3 text-center">

            @if($streamer->foto)

                <img
                    src="{{ asset('uploads/profile/'.$streamer->foto) }}"
                    class="streamer-img"
                >

            @else

                <img
                    src="https://via.placeholder.com/180"
                    class="streamer-img"
                >

            @endif

        </div>

        <div class="col-lg-9">

            <h1 class="streamer-name">

                {{ $streamer->name }}

            </h1>

            <p class="game-text">

                🎮 {{ $streamer->game }}

            </p>

            <div class="hero-stats">

                <div class="hero-stat">

                    <h3>
                        {{ number_format($streamer->followers ?? 0) }}
                    </h3>

                    <span>
                        Followers
                    </span>

                </div>

                <div class="hero-stat">

                    <h3>
                        Rp {{ number_format($streamer->total_donasi ?? 0) }}
                    </h3>

                    <span>
                        Total Donasi
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>
        <div class="row g-0">

            <div class="col-md-6 section">

                <h3 class="mb-4">
                    Pilih Nominal
                </h3>

                <div class="row">

                    <div class="col-6">
                        <button
                            type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(10000,this)"
                        >
                            Rp 10.000
                        </button>
                    </div>

                    <div class="col-6">
                        <button type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(25000,this)">
                            Rp 25.000
                        </button>
                    </div>

                    <div class="col-6">
                        <button type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(50000,this)">
                            Rp 50.000
                        </button>
                    </div>

                    <div class="col-6">
                        <button type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(100000,this)">
                            Rp 100.000
                        </button>
                    </div>

                    <div class="col-6">
                        <button type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(250000,this)">
                            Rp 250.000
                        </button>
                    </div>

                    <div class="col-6">
                        <button type="button"
                            class="btn nominal-btn w-100"
                            onclick="setNominal(500000,this)">
                            Rp 500.000
                        </button>
                    </div>

                </div>

                <h4 class="mt-4">
                    Nominal Lainnya
                </h4>

                <input
                    type="number"
                    name="nominal"
                    id="nominal"
                    class="form-control form-control-lg mt-3"
                    placeholder="Rp"
                    required
                    oninput="updateTotal()"
                >

@auth

<div class="alert alert-info mt-3">

    Saldo Wallet Anda :

    <strong>

        Rp {{ number_format(auth()->user()->balance) }}

    </strong>

</div>

@endauth

            </div>

            <div class="col-md-6 section">

                <h3>
                    Pesan untuk Streamer
                </h3>

                <textarea
                    name="pesan"
                    rows="6"
                    maxlength="150"
                    class="form-control"
                    placeholder="Tulis pesan dukungan kamu..."
                    onkeyup="countChar(this)"
                ></textarea>

                <div
                    class="text-end mt-2 text-muted"
                    id="counter"
                >
                    0/150
                </div>

                <!-- FITUR TAMBAHAN -->

<div class="mt-4">

    <h4 class="mb-3">
        Fitur Tambahan
    </h4>

    <div class="form-check mb-2">

        <input
            class="form-check-input extra-feature"
            type="checkbox"
            name="fitur[]"
            value="5000"
            onchange="updateTotal()"
        >

        <label class="form-check-label">

            🎤 Voice Note (+ Rp 5.000)

        </label>

    </div>

    <div class="form-check mb-2">

        <input
            class="form-check-input extra-feature"
            type="checkbox"
            name="fitur[]"
            value="10000"
            onchange="updateTotal()"
        >

        <label class="form-check-label">

            🎥 Video Pendek (+ Rp 10.000)

        </label>

    </div>

    <div class="form-check mb-2">

        <input
            class="form-check-input extra-feature"
            type="checkbox"
            name="fitur[]"
            value="15000"
            onchange="updateTotal()"
        >

        <label class="form-check-label">

            ✨ Highlight Donasi (+ Rp 15.000)

        </label>

    </div>

    <div class="form-check">

        <input
            class="form-check-input extra-feature"
            type="checkbox"
            name="fitur[]"
            value="20000"
            onchange="updateTotal()"
        >

        <label class="form-check-label">

            📌 Pin Pesan (+ Rp 20.000)

        </label>

    </div>

</div>

<hr>

<div class="hero-stat mt-4">

    <span>Total Pembayaran</span>

    <h3 id="totalText">
        Rp 0
    </h3>

</div>

</div>

                <button
                    type="submit"
                    class="btn lanjut-btn w-100 mt-4"
                    id="submitBtn"
                >
                    Lanjutkan
                </button>

            </div>

        </div>

    </div>

</form>

            </div>

        </div>

    </div>

</div>

@auth

<script>

const userBalance =
    {{ auth()->user()->balance ?? 0 }};

const adminFee = 1500;

</script>

@endauth

<script>

function updateTotal()
{
    let nominal =
        parseInt(
            document.getElementById('nominal').value
        ) || 0;

    let extra = 0;

    document
        .querySelectorAll('.extra-feature:checked')
        .forEach(function(item)
        {
            extra += parseInt(item.value);
        });

    let total =
        nominal +
        extra +
        adminFee;

    document.getElementById('totalText').innerHTML =
        'Rp ' +
        total.toLocaleString('id-ID');

    @auth

let btn =
    document.getElementById('submitBtn');

btn.disabled = false;

btn.innerHTML = 'Lanjutkan';

@endauth
}

function countChar(el)
{
    document.getElementById('counter').innerHTML =
        el.value.length + '/150';
}


function setNominal(value, btn)
{
    document.getElementById('nominal').value = value;

    document
        .querySelectorAll('.nominal-btn')
        .forEach(function(el)
        {
            el.classList.remove('active');
        });

    btn.classList.add('active');

    updateTotal();
}

</script>

</body>
</html>