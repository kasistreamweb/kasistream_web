<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KAistream</title>

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
        href="{{ asset('css/user-dashboard.css') }}"
    >

</head>
<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        @include('layouts.sidebar')
        <!-- CONTENT -->
        <div class="col-md-9 col-lg-10 p-4 content-area">

        <!-- WELCOME -->

        <div class="welcome-section mb-4">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="KAsistream"
        class="header-logo"
    >

    <h1>
        Selamat Datang, {{ Auth::user()->name }}
    </h1>

    <p>
        Temukan streamer favorit dan dukung creator terbaik di KAsistream.
    </p>

</div>

    <!-- TOPBAR -->

<div class="topbar mb-4">

    <div class="d-flex align-items-center gap-3 flex-wrap">

        <div class="search-flex">

            <div class="search-wrapper">

                <i class="fa-solid fa-magnifying-glass search-icon"></i>

                <input
                    type="text"
                    class="search-box"
                    placeholder="Cari streamer, game, atau kategori..."
                >

            </div>

        </div>

        <a
            href="#top-streamer"
            class="trending-badge text-decoration-none"
        >
            🔥 Trending
        </a>

        <div class="notification-btn">

            <i class="fa-solid fa-bell"></i>

        </div>

        <div class="wallet-mini">

            <i class="fa-solid fa-wallet"></i>

            <span>

                Rp {{ number_format(Auth::user()->balance ?? 0) }}

            </span>

        </div>

        <div class="user-mini">

            @if(Auth::user()->foto)

                <img
                    src="{{ asset('uploads/profile/' . Auth::user()->foto) }}"
                    class="top-avatar"
                    alt="{{ Auth::user()->name }}"
                >

            @else

                <img
                    src="{{ asset('images/default-avatar.png') }}"
                    class="top-avatar"
                    alt="User"
                >

            @endif

            <span>

                {{ Auth::user()->name }}

            </span>

        </div>

    </div>

</div>
        <!-- STATISTIC -->

        <div class="row g-4 mb-5">

            <div class="col-lg-3 col-md-6">

                <div class="stats-card">

                    <i class="fa-solid fa-tower-broadcast"></i>

                    <h3>

                        {{ $streamers->count() }}

                    </h3>

                    <span>

                        Streamer Aktif

                    </span>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="stats-card">

                    <i class="fa-solid fa-star"></i>

                    <h3>

                        {{ $totalFollowing }}

                    </h3>

                    <span>

                        Mengikuti

                    </span>
                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="stats-card">

                    <i class="fa-solid fa-gift"></i>

                    <h3>

                        Rp {{ number_format(Auth::user()->total_donasi ?? 0) }}

                    </h3>

                    <span>

                        Total Donasi

                    </span>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="stats-card">

                    <i class="fa-solid fa-wallet"></i>

                    <h3>

                        Rp {{ number_format(Auth::user()->balance ?? 0) }}

                    </h3>

                    <span>

                        Saldo Wallet

                    </span>

                </div>

            </div>

        </div>

<!-- TOP STREAMER -->
<div
    id="top-streamer"
    class="d-flex justify-content-between align-items-center mb-3"
>

    <h4 class="section-title">
        🔥 Top Streamer
    </h4>

    <a
        href="/streamers"
        class="btn btn-sm btn-primary"
    >
        Lihat Semua
    </a>

</div>

<div class="row">

    @forelse($streamers as $index => $streamer)

        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

            <div class="card streamer-card shadow-sm">

                <div class="card-body text-center">

                @if($index == 0)

<div class="rank-badge rank-gold">

    🥇 Top #1

</div>

@elseif($index == 1)

<div class="rank-badge rank-silver">

    🥈 Top #2

</div>

@elseif($index == 2)

<div class="rank-badge rank-bronze">

    🥉 Top #3

</div>

@endif

                    @if($streamer->foto)

                        <img
                            src="{{ asset('uploads/profile/' . $streamer->foto) }}"
                            class="streamer-img mb-3"
                            alt="{{ $streamer->name }}"
                        >

                    @else

                        <img
                            src="https://via.placeholder.com/100"
                            class="streamer-img mb-3"
                            alt="No Image"
                        >

                    @endif

                    <h5>
                        {{ $streamer->name }}
                    </h5>

                    <p class="text-muted mb-2">
                        Creator & Streamer
                    </p>

                    <div class="small text-muted">
                        🎮 {{ $streamer->game ?? 'Belum memilih game' }}
                    </div>

                    <div class="streamer-stat">

    <i class="fa-solid fa-users"></i>

    {{ number_format($streamer->followers ?? 0) }}

    Followers

</div>

                    <div class="streamer-donation mb-3">

    💰 Rp {{ number_format($streamer->total_donasi ?? 0) }}

</div>

                    <div class="mt-3">

                        <a
                            href="{{ url('/streamer/' . $streamer->id) }}"
                            class="btn btn-primary"
                        >
                            Lihat
                        </a>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-warning">

                Belum ada akun streamer yang terdaftar.

            </div>

        </div>

    @endforelse

</div> <!-- END ROW TOP STREAMER -->

<!-- AKTIVITAS & KATEGORI -->
<div class="row mt-4">

    <!-- AKTIVITAS TERBARU -->
    <div class="col-lg-8 mb-4">

        <div class="activity-card">

            <h4 class="section-title mb-4">
                ⚡ Aktivitas Terbaru
            </h4>

            <div class="activity-list">

@forelse($recentDonations as $donasi)

    <div class="activity-item">

        <div class="activity-icon donation">

            <i class="fa-solid fa-gift"></i>

        </div>

        <div class="activity-content">

                <div class="activity-title">

                    Donasi ke
                    {{ $donasi->streamer->name ?? 'Streamer' }}

                </div>

                <div class="activity-amount">

                    Rp {{ number_format($donasi->nominal) }}

                </div>

                <small>

                    {{ $donasi->created_at->diffForHumans() }}

                </small>

            </div>

        </div>

    @empty

        <div class="text-center text-muted py-4">

            Belum ada aktivitas donasi.

        </div>

    @endforelse

    </div>

        </div>

    </div>

<!-- KATEGORI GAME -->
<div class="col-lg-4 mb-4">

    <div class="activity-card">

        <h4 class="section-title mb-4">
            🎮 Kategori Game
        </h4>

        <div class="d-flex flex-wrap gap-2">

            <button class="btn category-btn">
                FPS
            </button>

            <button class="btn category-btn">
                MOBA
            </button>

            <button class="btn category-btn">
                Battle Royale
            </button>

            <button class="btn category-btn">
                Horror
            </button>

            <button class="btn category-btn">
                RPG
            </button>

            <button class="btn category-btn">
                Lainnya
            </button>

        </div>

    </div>

</div>

</div> <!-- END ROW AKTIVITAS + KATEGORI -->

</div> <!-- END CONTENT AREA -->

</div> <!-- END ROW -->

</div> <!-- END CONTAINER-FLUID -->

</body>
</html>