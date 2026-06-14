<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $streamer->name }} - KAistream</title>

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
    href="{{ asset('css/streamers-detail.css') }}"
>


</head>
<body>

<div class="container-fluid">

    <div class="row">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- CONTENT --}}
        <div class="col-md-9 col-lg-10 p-4 content-area">

            @if(session('success'))

                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>

            @endif

            {{-- HERO STREAMER --}}
            <div class="streamer-hero">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="header-logo"
                    alt="KAsistream"
                >

                <div class="row align-items-center">

                    <div class="col-lg-3 text-center">

                        @if($streamer->foto)

                            <img
                                src="{{ asset('uploads/profile/' . $streamer->foto) }}"
                                class="profile-img"
                                alt="{{ $streamer->name }}"
                            >

                        @else

                            <img
                                src="https://via.placeholder.com/220"
                                class="profile-img"
                                alt="Streamer"
                            >

                        @endif

                    </div>

                    <div class="col-lg-9">

                        <h1 class="streamer-name">

                            {{ $streamer->name }}

                            <i class="fa-solid fa-circle-check verified"></i>

                        </h1>

                        <p class="game-text">

                            🎮 {{ $streamer->game ?? 'Belum memilih game' }}

                        </p>

                        <div class="hero-stats">

                            <div class="hero-stat">

                                <h3>
                                    {{ number_format($streamer->followers ?? 0) }}
                                </h3>

                                <span>Followers</span>

                            </div>

                            <div class="hero-stat">

                                <h3>
                                    Rp {{ number_format($streamer->total_donasi ?? 0) }}
                                </h3>

                                <span>Total Donasi</span>

                            </div>

                        </div>

                        <div class="mt-4 d-flex gap-3 flex-wrap">

                            <a
                                href="/donasi/{{ $streamer->id }}"
                                class="btn btn-donasi action-btn"
                            >
                                <i class="fa-solid fa-gift me-2"></i>
                                Donasi
                            </a>

                            @auth

                                @if($isFollowing)

                                    <form
                                        action="/unfollow/{{ $streamer->id }}"
                                        method="POST"
                                        class="m-0"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-success action-btn"
                                        >

                                            <i class="fa-solid fa-check me-2"></i>

                                            Mengikuti

                                        </button>

                                    </form>

                                @else

                                    <form
                                        action="/follow/{{ $streamer->id }}"
                                        method="POST"
                                        class="m-0"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-donasi action-btn"
                                        >

                                            <i class="fa-solid fa-user-plus me-2"></i>

                                            Ikuti

                                        </button>

                                    </form>

                                @endif

                            @endauth

                            @guest

                                <a
                                    href="/login"
                                    class="btn btn-donasi action-btn"
                                >

                                    Login Untuk Mengikuti

                                </a>

                            @endguest

                            <button
                                class="btn btn-dark share-btn"
                            >

                                <i class="fa-solid fa-share-nodes"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            {{-- TAB MENU --}}
            <div class="tab-menu">

                <ul class="nav nav-justified">

                    <li class="nav-item">

                        <button
                            class="nav-link active"
                            id="tentang-tab"
                            onclick="showTab('tentang')"
                        >

                            Tentang

                        </button>

                    </li>

                    <li class="nav-item">

                        <button
                            class="nav-link"
                            id="jadwal-tab"
                            onclick="showTab('jadwal')"
                        >

                            Jadwal Live

                        </button>

                    </li>

                    <li class="nav-item">

                        <button
                            class="nav-link"
                            id="sosial-tab"
                            onclick="showTab('sosial')"
                        >

                            Media Sosial

                        </button>

                    </li>

                    <li class="nav-item">

                        <button
                            class="nav-link"
                            id="donatur-tab"
                            onclick="showTab('donatur')"
                        >

                            Top Donatur

                        </button>

                    </li>

                </ul>

            </div>

            {{-- TENTANG --}}
            <div id="tentang-content" class="mt-4">

                <div class="content-card">

                    <div class="content-title">
                        Tentang Streamer
                    </div>

                    <p class="fs-5">

                        {{ $streamer->bio ?? 'Belum ada deskripsi streamer.' }}

                    </p>

                </div>

            </div>

            {{-- JADWAL --}}
            <div id="jadwal-content" class="mt-4" style="display:none;">

                <div class="content-card">

                    <div class="content-title">
                        Jadwal Live
                    </div>

                    @if($streamer->jadwal_Live)

                        <div class="fs-5">

                            {!! nl2br(e($streamer->jadwal_Live)) !!}

                        </div>

                    @else

                        <p class="fs-5 text-muted">

                            Streamer belum mengatur jadwal live.

                        </p>

                    @endif

                </div>

            </div>

            {{-- MEDIA SOSIAL --}}
            <div id="sosial-content" class="mt-4" style="display:none;">

                <div class="content-card">

                    <div class="content-title">
                        Media Sosial
                    </div>

                    <div class="d-flex gap-3 flex-wrap">

                        @if($streamer->youtube)

                            <a
                                href="{{ $streamer->youtube }}"
                                target="_blank"
                                class="btn btn-danger social-btn"
                            >

                                <i class="fab fa-youtube"></i>

                            </a>

                        @endif

                        @if($streamer->instagram)

                            <a
                                href="{{ $streamer->instagram }}"
                                target="_blank"
                                class="btn btn-dark social-btn"
                            >

                                <i class="fab fa-instagram"></i>

                            </a>

                        @endif

                        @if($streamer->tiktok)

                            <a
                                href="{{ $streamer->tiktok }}"
                                target="_blank"
                                class="btn btn-dark social-btn"
                            >

                                <i class="fab fa-tiktok"></i>

                            </a>

                        @endif

                        @if($streamer->discord)

                            <a
                                href="{{ $streamer->discord }}"
                                target="_blank"
                                class="btn btn-primary social-btn"
                            >

                                <i class="fab fa-discord"></i>

                            </a>

                        @endif

                    </div>

                </div>

            </div>

            {{-- TOP DONATUR --}}
            <div id="donatur-content" class="mt-4" style="display:none;">

                <div class="content-card">

                    <div class="content-title">
                        Top Donatur
                    </div>

<ol class="top-donatur">

    @forelse($topDonatur as $index => $donatur)

        <li>

            <span>

                @if($index == 0)
                    🥇
                @elseif($index == 1)
                    🥈
                @elseif($index == 2)
                    🥉
                @else
                    #{{ $index + 1 }}
                @endif

                {{ $donatur->user->name ?? $donatur->guest_name }}

            </span>

            <span>
                Rp {{ number_format($donatur->total_donasi, 0, ',', '.') }}
            </span>

        </li>

    @empty

        <li>
            <span>Belum ada donatur</span>
            <span>Rp 0</span>
        </li>

    @endforelse

</ol>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function showTab(tab)
{
    document.getElementById('tentang-content').style.display = 'none';
    document.getElementById('jadwal-content').style.display = 'none';
    document.getElementById('sosial-content').style.display = 'none';
    document.getElementById('donatur-content').style.display = 'none';

    document.getElementById('tentang-tab').classList.remove('active');
    document.getElementById('jadwal-tab').classList.remove('active');
    document.getElementById('sosial-tab').classList.remove('active');
    document.getElementById('donatur-tab').classList.remove('active');

    document.getElementById(tab + '-content').style.display = 'block';
    document.getElementById(tab + '-tab').classList.add('active');
}

</script>

</body>