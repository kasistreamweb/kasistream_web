<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ranking Streamer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>

body{
    background:#f4f6f9;
}

.sidebar{
    min-height:100vh;
}

.content-card{
    background:white;
    border-radius:15px;
    padding:25px;
}

/* STAT CARD */
.stat-card{
    background:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
    height:100%;
    border-left:4px solid #6366f1;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 20px rgba(0,0,0,.1);
}

.stat-card small{
    color:#6b7280;
    font-size:13px;
    font-weight:500;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.stat-card h3{
    margin:8px 0 0;
    font-weight:700;
    font-size:22px;
    color:#111827;
}

/* RANK CARD */
.rank-card{
    background:white;
    border-radius:14px;
    padding:16px 20px;
    margin-bottom:12px;
    border:1px solid #f1f5f9;
    transition:.3s;
    box-shadow:0 1px 6px rgba(0,0,0,.05);
}

.rank-card:hover{
    box-shadow:0 4px 16px rgba(0,0,0,.1);
    border-color:#e0e7ff;
    transform:translateY(-2px);
}

/* RANK BADGE */
.rank-badge{
    width:44px;
    height:44px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:18px;
    color:white;
    flex-shrink:0;
}

.gold{
    background:linear-gradient(135deg,#f59e0b,#d97706);
    box-shadow:0 0 12px rgba(245,158,11,.4);
}

.silver{
    background:linear-gradient(135deg,#94a3b8,#64748b);
    box-shadow:0 0 12px rgba(148,163,184,.4);
}

.bronze{
    background:linear-gradient(135deg,#b45309,#92400e);
    box-shadow:0 0 12px rgba(180,83,9,.4);
}

.rank-number{
    width:44px;
    height:44px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f3f4f6;
    color:#6b7280;
    font-weight:700;
    font-size:15px;
    flex-shrink:0;
}

/* PROFILE */
.profile{
    width:48px;
    height:48px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #e0e7ff;
    flex-shrink:0;
}

/* STREAMER INFO */
.streamer-name{
    font-weight:700;
    font-size:15px;
    color:#111827;
    margin-bottom:2px;
}

.streamer-game{
    font-size:12px;
    color:#6b7280;
}

/* STAT BADGE */
.stat-badge{
    display:inline-flex;
    align-items:center;
    gap:5px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:6px 12px;
    font-size:13px;
    color:#374151;
    font-weight:500;
    white-space:nowrap;
}

</style>

</head>
<body>

<div class="container-fluid">
<div class="row">

    @include('admin.layouts.sidebar')

    <div class="col-md-10 p-4">
        <div class="content-card shadow-sm">

            <h2 class="mb-4">🏆 Ranking Streamer</h2>

            {{-- STATISTIK --}}
            <div class="row g-3 mb-4">

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <small>Total Streamer</small>
                        <h3>{{ number_format($streamers->total()) }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <small>Total Followers</small>
                        <h3>{{ number_format($streamers->sum('followers')) }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <small>Total Donasi</small>
                        <h3>Rp {{ number_format($streamers->sum('total_donasi')) }}</h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <small>Total Saldo</small>
                        <h3>Rp {{ number_format($streamers->sum('balance')) }}</h3>
                    </div>
                </div>

            </div>

            {{-- RANKING LIST --}}
            @foreach($streamers as $index => $streamer)

                @php
                    $rank = ($streamers->currentPage() - 1) * $streamers->perPage() + $index + 1;
                @endphp

                <div class="rank-card">
                    <div class="d-flex align-items-center gap-3 flex-wrap">

                        {{-- BADGE --}}
                        @if($rank == 1)
                            <div class="rank-badge gold">1</div>
                        @elseif($rank == 2)
                            <div class="rank-badge silver">2</div>
                        @elseif($rank == 3)
                            <div class="rank-badge bronze">3</div>
                        @else
                            <div class="rank-number">#{{ $rank }}</div>
                        @endif

                        {{-- FOTO --}}
                        <img
                            src="{{ $streamer->foto
                                ? asset('uploads/profile/'.$streamer->foto)
                                : 'https://via.placeholder.com/60' }}"
                            class="profile"
                        >

                        {{-- NAMA & GAME --}}
                        <div style="min-width:120px; flex:1;">
                            <div class="streamer-name">{{ $streamer->name }}</div>
                            <div class="streamer-game">🎮 {{ $streamer->game ?? '-' }}</div>
                        </div>

                        {{-- STATS --}}
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="stat-badge">
                                👥 {{ number_format($streamer->followers) }} Followers
                            </div>
                            <div class="stat-badge">
                                💰 Rp {{ number_format($streamer->total_donasi) }}
                            </div>
                            <div class="stat-badge">
                                🏦 Rp {{ number_format($streamer->balance) }}
                            </div>
                        </div>

                        {{-- TOMBOL --}}

                        href="/admin-streamers/{{ $streamer->id }}"
                        class="btn btn-primary btn-sm ms-auto"
                        style="white-space:nowrap;"
                    >
                        <i class="fa-solid fa-eye me-1"></i>
                        Detail
                    </a>

                    </div>
                </div>

            @endforeach

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $streamers->links() }}
            </div>

        </div>
    </div>

</div>
</div>

</body>
</html>