<head>
<meta charset="UTF-8">
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<title>KAsistream - Donate & Support Streamers</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

/* ===== VARIABLES ===== */
:root {
    --primary: #2563eb;
    --secondary: #8b5cf6;
    --dark: #050816;
    --card: #0f172a;
    --text: #ffffff;
}

/* ===== RESET ===== */
*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ===== BASE ===== */
html {
    overflow-x: hidden;
    touch-action: pan-x pan-y;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #1e3a8a 0%, #050816 60%);
    color: white;
    overflow-x: hidden;
}

/* ===== NAVBAR ===== */
.navbar {
    background: rgba(5, 8, 22, .85) !important;
    backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, .08);
    position: relative;
    z-index: 1000;
}

.navbar-brand {
    color: white !important;
    font-size: 30px;
    font-weight: 800;
}

.nav-link {
    color: #cbd5e1 !important;
    margin: 0 10px;
    transition: .3s;
}

.nav-link:hover {
    color: white !important;
}

.btn-login {
    border: 1px solid #8b5cf6;
    color: white;
    border-radius: 12px;
    padding: 10px 20px;
}

.btn-login:hover {
    background: #8b5cf6;
    color: white;
}

.btn-register {
    background: linear-gradient(90deg, #2563eb, #8b5cf6);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 10px 22px;
}

.btn-register:hover {
    color: white;
    box-shadow: 0 0 20px rgba(139, 92, 246, .5);
}

/* ===== HERO ===== */
.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    padding-top: 90px;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    background: rgba(139, 92, 246, .08);
    top: -150px;
    right: -150px;
    pointer-events: none;
    z-index: 0;
}

/* FIX #1 — Tombol hero tidak tertimpa pseudo-element */
.hero .container,
.hero .row,
.hero .col-lg-6 {
    position: relative;
    z-index: 5;
}

.hero-btn {
    position: relative;
    z-index: 20;
}

.btn-start,
.btn-guest {
    position: relative;
    z-index: 30;
    pointer-events: auto;
}

.hero-title {
    font-size: 60px;
    font-weight: 900;
    line-height: 1.05;
    letter-spacing: -2px;
    color: white;
    margin-bottom: 25px;
}

.hero-title span {
    background: linear-gradient(90deg, #60a5fa, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    color: #cbd5e1;
    font-size: 20px;
    margin-top: 20px;
}

.btn-start {
    background: linear-gradient(90deg, #2563eb, #8b5cf6);
    border: none;
    color: white;
    padding: 14px 28px;
    border-radius: 15px;
    font-weight: 600;
}

.btn-start:hover {
    color: white;
    box-shadow: 0 0 25px rgba(139, 92, 246, .5);
}

.btn-guest {
    border: 1px solid rgba(255, 255, 255, .15);
    color: white;
    padding: 14px 28px;
    border-radius: 15px;
    margin-left: 10px;
}

.btn-guest:hover {
    background: white;
    color: #111827;
}

.hero-logo {
    position: relative;
    width: 130%;
    max-width: 600px;
    left: 60px;
    top: -10px;
    animation: floatLogo 5s ease-in-out infinite;
}

@keyframes floatLogo {
    0%   { transform: translateY(0px); }
    50%  { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}

/* ===== HERO STATS ===== */
.hero-stats {
    margin-top: 50px;
    padding: 25px;
    border-radius: 20px;
    background: rgba(255, 255, 255, .04);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(10px);
}

.hero-stats h3 {
    font-weight: 800;
    margin-bottom: 5px;
}

.hero-stats small {
    color: #94a3b8;
}

/* ===== SECTION TITLE ===== */
.section-title {
    text-align: center;
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 50px;
}

/* ===== STREAMER CARD ===== */
.streamer-card {
    background: rgba(15, 23, 42, .9);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 20px;
    transition: .3s;
    overflow: hidden;
}

.streamer-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 0 30px rgba(139, 92, 246, .2);
}

.streamer-avatar {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #8b5cf6;
}

.btn-support {
    background: linear-gradient(90deg, #2563eb, #8b5cf6);
    border: none;
    border-radius: 12px;
    color: white;
}

/* ===== STATISTICS ===== */
.stat-card {
    background: rgba(15, 23, 42, .9);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 20px;
    padding: 30px;
    text-align: center;
}

.stat-card i {
    font-size: 40px;
    margin-bottom: 15px;
    color: #8b5cf6;
}

/* ===== FOOTER ===== */
footer {
    margin-top: 120px;
    padding: 50px 0;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, .08);
    color: #cbd5e1;
}

/* ============================================
   MOBILE ONLY — max-width: 768px
   ============================================ */
@media (max-width: 768px) {

    /* Safe area notch / home bar */
    body {
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }

    /* ----- NAVBAR ----- */
    .navbar {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .navbar .container {
        padding-left: 16px;
        padding-right: 16px;
    }

    .navbar-brand {
        font-size: 20px;
        letter-spacing: -0.5px;
    }

    .navbar-collapse {
        background: rgba(5, 8, 22, .97);
        border-radius: 0 0 16px 16px;
        padding: 12px 16px 20px;
        margin-top: 10px;
        border: 1px solid rgba(255, 255, 255, .08);
        border-top: none;
    }

    .navbar-nav {
        gap: 4px;
        margin-bottom: 16px !important;
    }

    .nav-link {
        padding: 10px 12px !important;
        border-radius: 10px;
        margin: 0 !important;
        font-size: 15px;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, .06);
    }

    .navbar .d-flex {
        flex-direction: row;
        gap: 8px;
    }

    .navbar .d-flex .btn-login,
    .navbar .d-flex .btn-register {
        flex: 1;
        text-align: center;
        padding: 10px 12px;
        font-size: 14px;
    }

    /* ----- HERO ----- */
    /* FIX #2 — padding hero diperkecil */
    .hero {
        min-height: auto;
        padding-top: 40px;
        padding-bottom: 20px;
    }

    .hero .row {
        flex-direction: column;
    }

    .hero .col-lg-6:first-child {
        text-align: center;
        order: 1;
    }

    .hero .col-lg-6:last-child {
        order: 2;
        margin-top: 30px;
    }

    .hero-title {
        font-size: 30px;
        line-height: 1.2;
        letter-spacing: -1px;
        margin-bottom: 14px;
    }

    .hero-subtitle {
        font-size: 14px;
        line-height: 1.75;
        margin-top: 12px;
        padding: 0 4px;
    }

    /* FIX #3 — lingkaran background diperkecil */
    .hero::before {
        width: 350px;
        height: 350px;
        top: -100px;
        right: -100px;
    }

    /* FIX #4 — logo diperkecil */
    .hero-logo {
        width: 70%;
        max-width: 220px;
        left: 0;
        top: 0;
        margin: 0 auto;
        display: block;
    }

    .hero-btn {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 22px;
    }

    .btn-start,
    .btn-guest {
        width: 100%;
        margin: 0 !important;
        padding: 13px 20px;
        font-size: 15px;
        border-radius: 13px;
        text-align: center;
    }

    /* ----- HERO STATS ----- */
    .hero-stats {
        margin-top: 28px !important;
        padding: 16px 12px;
        border-radius: 16px;
        text-align: center;
    }

    /* FIX #5 — stats lebih rapi */
    .hero-stats .row {
        align-items: center;
    }

    .hero-stats h3 {
        font-size: 18px;
        margin-bottom: 3px;
    }

    .hero-stats small {
        font-size: 11px;
    }

    /* ----- SECTION TITLE ----- */
    .section-title {
        font-size: 26px;
        margin-bottom: 24px;
        letter-spacing: -0.5px;
    }

    /* ----- STREAMER ----- */
    #streamer {
        padding-top: 40px !important;
        padding-bottom: 20px !important;
    }

    #streamer .row {
        --bs-gutter-x: 10px;
    }

    #streamer .col-lg-4,
    #streamer .col-md-6 {
        width: 50% !important;
        padding-left: 5px;
        padding-right: 5px;
    }

    #streamer .mb-4 {
        margin-bottom: 10px !important;
    }

    .streamer-card {
        border-radius: 14px;
    }

    .streamer-card .card-body {
        padding: 14px 10px !important;
    }

    .streamer-avatar {
        width: 64px;
        height: 64px;
        border-width: 2px;
        margin-bottom: 10px !important;
    }

    .streamer-card h4 {
        font-size: 13px;
        margin-bottom: 4px;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .streamer-card p {
        font-size: 11px;
        margin-bottom: 6px !important;
    }

    .streamer-card .badge {
        font-size: 10px;
        padding: 4px 8px;
    }

    .streamer-card .mb-3 {
        margin-bottom: 8px !important;
    }

    .btn-support {
        font-size: 12px;
        padding: 8px 6px;
        border-radius: 10px;
    }

    /* ----- STATISTIK ----- */
    #statistik {
        padding-top: 40px !important;
        padding-bottom: 20px !important;
    }

    #statistik .col-md-3 {
        width: 50% !important;
        margin-bottom: 10px !important;
    }

    .stat-card {
        padding: 18px 10px;
        border-radius: 16px;
    }

    .stat-card i {
        font-size: 26px;
        margin-bottom: 10px;
    }

    .stat-card h3 {
        font-size: 18px;
    }

    .stat-card p {
        font-size: 12px;
        margin-bottom: 0;
    }

    /* ----- CTA ----- */
    .container > .text-center.p-5 {
        padding: 28px 20px !important;
        border-radius: 18px !important;
    }

    .container > .text-center.p-5 h2 {
        font-size: 20px;
        margin-bottom: 10px !important;
    }

    .container > .text-center.p-5 p {
        font-size: 13px;
        margin-bottom: 20px !important;
    }

    .cta-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .cta-buttons .btn {
        width: 100%;
        max-width: 280px;
        padding: 12px 20px;
        font-size: 15px;
    }

    /* ----- FOOTER ----- */
    footer {
        margin-top: 40px;
        padding: 30px 0 24px;
    }

    footer h3 {
        font-size: 20px;
        margin-bottom: 10px !important;
    }

    footer p {
        font-size: 13px;
    }

    footer .mt-4 {
        margin-top: 16px !important;
    }

    footer .fa-lg {
        font-size: 22px;
    }

    footer .me-3 {
        margin-right: 16px !important;
    }

    footer hr {
        margin: 16px 0 !important;
    }

    footer small {
        font-size: 12px;
    }
}
</style>

</head>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container">

        <a class="navbar-brand" href="/">

            <i class="fa-solid fa-gamepad me-2"></i>

            KAsistream

        </a>

        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarKAI"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarKAI"
        >

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">

                    <a class="nav-link" href="#beranda">

                        Beranda

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#streamer">

                        Streamer

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#statistik">

                        Statistik

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#footer">

                        Tentang

                    </a>

                </li>

            </ul>

            <div class="d-flex">

                <a
                    href="/login"
                    class="btn btn-login me-2"
                >

                    Masuk

                </a>

                <a
                    href="/register"
                    class="btn btn-register"
                >

                    Daftar

                </a>

            </div>

        </div>

    </div>

</nav>

<section
    class="hero"
    id="beranda"
>

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <h1 class="hero-title">

                    Dukung

                    <span>
                        Streamer Favoritmu
                    </span>

                    Secara Langsung

                </h1>

                <p class="hero-subtitle">

                    KAsistream membantu penonton
                    memberikan dukungan kepada streamer
                    favorit melalui sistem donasi yang
                    cepat, aman, dan transparan.

                </p>

                <div class="hero-btn">

                    <a
                        href="/register"
                        class="btn btn-start"
                    >

                        <i class="fa-solid fa-rocket me-2"></i>

                        Mulai Sekarang

                    </a>

                    <a
                        href="/streamers"
                        class="btn btn-guest"
                    >

                        <i class="fa-solid fa-gift me-2"></i>

                        Donasi Tanpa Login

                    </a>

                </div>

                <div class="hero-stats mt-5">

                    <div class="row">

                        <div class="col-4">

                            <h3 class="fw-bold">

                                {{ $totalStreamer }}

                            </h3>

                            <small class="text-secondary">

                                Streamer

                            </small>

                        </div>

                        <div class="col-4">

                            <h3 class="fw-bold">

                                {{ $totalUser }}

                            </h3>

                            <small class="text-secondary">

                                User

                            </small>

                        </div>

                        <div class="col-4">

                            <h3 class="fw-bold">

                                Rp {{ number_format($totalDonasi) }}

                            </h3>

                            <small class="text-secondary">

                                Donasi

                            </small>

                        </div>
                    </div>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="hero-logo"
                    alt="KAsistream"
                >

            </div>

        </div>

    </div>

</section>

<!-- TOP STREAMER -->

<!-- STREAMER POPULER -->

<section
    class="container py-5"
    id="streamer"
>

    <h2 class="section-title">

        Streamer Populer

    </h2>

    <div class="row">

        @forelse($streamers as $streamer)

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card streamer-card h-100">

                    <div class="card-body text-center p-4">

                        @if($streamer->foto)

                            <img
                                src="{{ asset('uploads/profile/'.$streamer->foto) }}"
                                class="streamer-avatar mb-3"
                            >

                        @else

                            <img
                                src="https://via.placeholder.com/120"
                                class="streamer-avatar mb-3"
                            >

                        @endif

                        <h4 class="fw-bold text-white">

                            {{ $streamer->name }}

                        </h4>

                        <p class="text-secondary">

                            🎮 Streamer

                        </p>

                        <div class="mb-3">

                            <span class="badge bg-primary">

                                👥 {{ number_format($streamer->followers ?? 0) }}

                            </span>

                        </div>

                        <a
                            href="/streamer/{{ $streamer->id }}"
                            class="btn btn-support w-100"
                        >

                            <i class="fa-solid fa-heart me-2"></i>

                            Lihat Profil

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-warning text-center">

                    Belum ada streamer terdaftar.

                </div>

            </div>

        @endforelse

    </div>

</section>

<!-- STATISTIK -->

<section
    class="container py-5"
    id="statistik"
>

    <h2 class="section-title">

        Statistik Platform

    </h2>

    <div class="row">

        <div class="col-md-3 mb-4">

            <div class="stat-card">

                <i class="fa-solid fa-tower-broadcast"></i>

                <h3 class="fw-bold">

                    {{ $totalStreamer }}

                </h3>

                <p class="text-secondary">

                    Streamer Aktif

                </p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="stat-card">

                <i class="fa-solid fa-users"></i>

                <h3 class="fw-bold">

                    {{ $totalUser }}

                </h3>

                <p class="text-secondary">

                    Pengguna

                </p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="stat-card">

                <i class="fa-solid fa-money-bill-wave"></i>

                <h3 class="fw-bold">

                    Rp {{ number_format($totalDonasi) }}

                </h3>

                <p class="text-secondary">

                    Total Donasi

                </p>
            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="stat-card">

                <i class="fa-solid fa-star"></i>

                <h3 class="fw-bold">

                    {{ number_format($totalFollower) }}

                </h3>

                <p class="text-secondary">

                    Total Followers

                </p>

            </div>

        </div>

    </div>

</section>

<!-- CTA -->

<section class="container py-5">

    <div
        class="text-center p-5 rounded-4"
        style="
            background:
            linear-gradient(
                135deg,
                #2563eb,
                #8b5cf6
            );
        "
    >

        <h2 class="fw-bold mb-3">

            Siap Mendukung Streamer Favoritmu?

        </h2>

        <p class="mb-4">

            Bergabung sekarang dan berikan dukungan
            langsung kepada kreator favoritmu.

        </p>

        <div class="cta-buttons">

    <a
        href="/register"
        class="btn btn-light btn-lg"
    >
        Daftar Sekarang
    </a>

    <a
        href="/streamers"
        class="btn btn-outline-light btn-lg"
    >
        Donasi Tanpa Login
    </a>

</div>

    </div>

</section>

<!-- FOOTER -->

<footer id="footer">

    <div class="container">

        <h3 class="fw-bold mb-3">

            🎮 KAsistream

        </h3>

        <p>

            Platform Donasi dan Dukungan Streamer Indonesia.

        </p>

        <div class="mt-4">

            <a href="#" class="text-light me-3">

                <i class="fab fa-facebook fa-lg"></i>

            </a>

            <a href="#" class="text-light me-3">

                <i class="fab fa-instagram fa-lg"></i>

            </a>

            <a href="#" class="text-light me-3">

                <i class="fab fa-youtube fa-lg"></i>

            </a>

            <a href="#" class="text-light">

                <i class="fab fa-discord fa-lg"></i>

            </a>

        </div>

        <hr class="my-4">

        <small>

            © 2026 KAsistream. All Rights Reserved.

        </small>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>