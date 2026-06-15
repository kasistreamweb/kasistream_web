<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Streamer</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/kasistream.css') }}">
<link rel="stylesheet" href="{{ asset('css/streamers.css') }}">

</head>
<body>

<div class="streamers-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar-wrapper">
        @include('layouts.sidebar')
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="content-wrapper">

        <div class="p-4">

            {{-- HEADER --}}
            <div class="page-title mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2">🎮 Jelajahi Streamer</h2>
                        <p>Temukan streamer favorit dan dukung creator terbaik di KAsistream.</p>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="card filter-card mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">

                            <div class="col-lg-5">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Cari streamer..."
                                    value="{{ request('search') }}"
                                >
                            </div>

                            <div class="col-lg-5">
                                <select name="game" class="form-select">
                                    <option value="">Semua Game</option>
                                    @foreach($games as $game)
                                        <option value="{{ $game }}" {{ request('game') == $game ? 'selected' : '' }}>
                                            {{ $game }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary w-100 h-100">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                                    Cari
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- TOTAL STREAMER --}}
            <div class="mb-4">
                <div class="stats-card text-center">
                    <i class="fa-solid fa-tower-broadcast"></i>
                    <h3>{{ $streamers->total() }}</h3>
                    <span>Total Streamer Terdaftar</span>
                </div>
            </div>

            {{-- STREAMER LIST --}}
            <div class="row">

                @forelse($streamers as $streamer)

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="streamer-card">

                            <div class="text-center">

                                @if($streamer->foto)
                                    <img
                                        src="{{ asset('uploads/profile/'.$streamer->foto) }}"
                                        class="streamer-img mb-3"
                                    >
                                @else
                                    <img
                                        src="https://via.placeholder.com/120"
                                        class="streamer-img mb-3"
                                    >
                                @endif

                                <h4 class="fw-bold">{{ $streamer->name }}</h4>
                                <p class="text-muted mb-3">Creator & Streamer</p>

                            </div>

                            <div class="stats text-center mb-4">
                                <div>🎮 {{ $streamer->game ?? 'Belum memilih game' }}</div>
                                <div>👥 {{ number_format($streamer->followers ?? 0) }} Followers</div>
                                <div>💰 Rp {{ number_format($streamer->total_donasi ?? 0) }}</div>
                            </div>

                            <a href="/streamer/{{ $streamer->id }}" class="btn btn-streamer w-100">
                                <i class="fa-solid fa-eye me-2"></i>
                                Lihat Profil
                            </a>

                        </div>
                    </div>

                @empty

                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Streamer tidak ditemukan.
                        </div>
                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $streamers->links() }}
            </div>

        </div>{{-- tutup .p-4 --}}

    </main>

</div>{{-- tutup .streamers-layout --}}

</body>
</html>