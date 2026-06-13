<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>KAsistream - Donate & Support Streamers</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

:root{

    --primary:#2563eb;
    --secondary:#8b5cf6;
    --dark:#050816;
    --card:#0f172a;
    --text:#ffffff;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Segoe UI',sans-serif;

    background:
    radial-gradient(
        circle at top,
        #1e3a8a 0%,
        #050816 60%
    );

    color:white;

    overflow-x:hidden;
}

/* NAVBAR */

.navbar{

    background:
    rgba(5,8,22,.85) !important;

    backdrop-filter:blur(15px);

    border-bottom:
    1px solid rgba(255,255,255,.08);
}

.navbar-brand{

    color:white !important;

    font-size:30px;

    font-weight:800;
}

.nav-link{

    color:#cbd5e1 !important;

    margin:0 10px;

    transition:.3s;
}

.nav-link:hover{

    color:white !important;
}

.btn-login{

    border:1px solid #8b5cf6;

    color:white;

    border-radius:12px;

    padding:10px 20px;
}

.btn-login:hover{

    background:#8b5cf6;

    color:white;
}

.btn-register{

    background:
    linear-gradient(
        90deg,
        #2563eb,
        #8b5cf6
    );

    color:white;

    border:none;

    border-radius:12px;

    padding:10px 22px;
}

.btn-register:hover{

    color:white;

    box-shadow:
    0 0 20px rgba(139,92,246,.5);
}

/* HERO */

.hero{

    min-height:100vh;

    display:flex;

    align-items:center;

    position:relative;

    padding-top:100px;
}

.hero::before{

    content:'';

    position:absolute;

    width:600px;
    height:600px;

    border-radius:50%;

    background:
    rgba(139,92,246,.08);

    top:-150px;
    right:-150px;
}

.hero-title{

    font-size:60px;

    font-weight:900;

    line-height:1.05;

    letter-spacing:-2px;

    color:white;

    margin-bottom:25px;
}

.hero-title span{

    background:
    linear-gradient(
        90deg,
        #60a5fa,
        #c084fc
    );

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.hero-subtitle{

    color:#cbd5e1;

    font-size:20px;

    margin-top:20px;
}

.hero-btn{

    margin-top:35px;
}

.btn-start{

    background:
    linear-gradient(
        90deg,
        #2563eb,
        #8b5cf6
    );

    border:none;

    color:white;

    padding:14px 28px;

    border-radius:15px;

    font-weight:600;
}

.btn-start:hover{

    color:white;

    box-shadow:
    0 0 25px rgba(139,92,246,.5);
}

.btn-guest{

    border:1px solid rgba(255,255,255,.15);

    color:white;

    padding:14px 28px;

    border-radius:15px;

    margin-left:10px;
}

.btn-guest:hover{

    background:white;

    color:#111827;
}

.hero-logo{

    position: relative;

    width:130%;

    max-width:600px;

    left:60px;      /* geser kanan-kiri */
    top:-10px;       /* geser atas-bawah */

    animation:
    floatLogo 5s ease-in-out infinite;
}

@keyframes floatLogo{

    0%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-15px);
    }

    100%{
        transform:translateY(0px);
    }
}

/* SECTION */

.section-title{

    text-align:center;

    font-size:42px;

    font-weight:800;

    margin-bottom:50px;
}

/* STREAMER CARD */

.streamer-card{

    background:
    rgba(15,23,42,.9);

    border:
    1px solid rgba(255,255,255,.08);

    border-radius:20px;

    transition:.3s;

    overflow:hidden;
}

.streamer-card:hover{

    transform:
    translateY(-8px);

    box-shadow:
    0 0 30px rgba(139,92,246,.2);
}

.streamer-avatar{

    width:120px;
    height:120px;

    object-fit:cover;

    border-radius:50%;

    border:
    3px solid #8b5cf6;
}

.btn-support{

    background:
    linear-gradient(
        90deg,
        #2563eb,
        #8b5cf6
    );

    border:none;

    border-radius:12px;

    color:white;
}

/* STATISTICS */

.stat-card{

    background:
    rgba(15,23,42,.9);

    border:
    1px solid rgba(255,255,255,.08);

    border-radius:20px;

    padding:30px;

    text-align:center;
}

.stat-card i{

    font-size:40px;

    margin-bottom:15px;

    color:#8b5cf6;
}

/* FOOTER */

footer{

    margin-top:120px;

    padding:50px 0;

    text-align:center;

    border-top:
    1px solid rgba(255,255,255,.08);

    color:#cbd5e1;
}

@media(max-width:768px){

    .hero{

        text-align:center;

        padding-top:130px;

        padding-bottom:60px;
    }

    .hero-title{

        font-size:42px;

        line-height:1.2;
    }

    .hero-subtitle{

        font-size:18px;
    }

    .hero-logo{

        width:100%;

        max-width:400px;

        left:0;

        top:0;

        margin-top:40px;
    }

    .hero-btn{

        display:flex;

        flex-direction:column;

        gap:12px;
    }

    .btn-guest{

        margin-left:0;
    }

    .hero-stats{

        text-align:center;
    }
}

/* HERO STATS */

.hero-stats{

    margin-top:50px;

    padding:25px;

    border-radius:20px;

    background:
    rgba(255,255,255,.04);

    border:
    1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(10px);
}

.hero-stats h3{

    font-weight:800;

    margin-bottom:5px;
}

.hero-stats small{

    color:#94a3b8;
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

                                100%

                            </h3>

                            <small class="text-secondary">

                                Aman

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

                    128K+

                </h3>

                <p class="text-secondary">

                    Donasi Terkirim

                </p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="stat-card">

                <i class="fa-solid fa-star"></i>

                <h3 class="fw-bold">

                    4.9/5

                </h3>

                <p class="text-secondary">

                    Rating Platform

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

        <a
            href="/register"
            class="btn btn-light btn-lg me-2"
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