<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

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
    padding:20px;
}

.stat-card{
    background:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    height:100%;
}

.stat-card h3{
    margin:10px 0 0;
    font-weight:bold;
}

.rank-card{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-bottom:15px;
}

.rank-badge{

    width:50px;
    height:50px;

    border-radius:50%;

    display:flex;
    align-items:center;
    justify-content:center;

    font-weight:bold;
    font-size:20px;

    color:white;
}

.gold{
    background:#f59e0b;
}

.silver{
    background:#94a3b8;
}

.bronze{
    background:#b45309;
}

.profile{

    width:60px;
    height:60px;

    border-radius:50%;

    object-fit:cover;
}

</style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        @include('admin.layouts.sidebar')

        <div class="col-md-10 p-4">

            <div class="content-card shadow-sm">

                <h2 class="mb-3">

                    🏆 Ranking Streamer

                </h2>

                <!-- Statistik -->

                <div class="row mb-4">

                    <div class="col-6 col-md-3 mb-3">

                        <div class="stat-card">

                            <small>Total Streamer</small>

                            <h3>

                                {{ number_format($streamers->total()) }}

                            </h3>

                        </div>

                    </div>

                    <div class="col-6 col-md-3 mb-3">

                        <div class="stat-card">

                            <small>Total Followers</small>

                            <h3>

                                {{ number_format($streamers->sum('followers')) }}

                            </h3>

                        </div>

                    </div>

                    <div class="col-6 col-md-3 mb-3">

                        <div class="stat-card">

                            <small>Total Donasi</small>

                            <h3>

                                Rp {{ number_format($streamers->sum('total_donasi')) }}

                            </h3>

                        </div>

                    </div>

                   <div class="col-6 col-md-3 mb-3">

                        <div class="stat-card">

                            <small>Total Saldo</small>

                            <h3>

                                Rp {{ number_format($streamers->sum('balance')) }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- Ranking -->

                @foreach($streamers as $index => $streamer)

                    @php

                        $rank =
                            ($streamers->currentPage() - 1)
                            * $streamers->perPage()
                            + $index + 1;

                    @endphp

                    <div class="rank-card shadow-sm">

    <div class="row align-items-center rank-row">

                            <div class="col-md-1">

                                @if($rank == 1)

                                    <div class="rank-badge gold">
                                        1
                                    </div>

                                @elseif($rank == 2)

                                    <div class="rank-badge silver">
                                        2
                                    </div>

                                @elseif($rank == 3)

                                    <div class="rank-badge bronze">
                                        3
                                    </div>

                                @else

                                    <h5>
                                        #{{ $rank }}
                                    </h5>

                                @endif

                            </div>

                            <div class="col-md-1">

                                <img
                                    src="{{ $streamer->foto
                                        ? asset('uploads/profile/'.$streamer->foto)
                                        : 'https://via.placeholder.com/60' }}"
                                    class="profile"
                                >

                            </div>

                            <div class="col-md-3">

                                <strong>

                                    {{ $streamer->name }}

                                </strong>

                                <br>

                                <small>

                                    {{ $streamer->game ?? '-' }}

                                </small>

                            </div>

                            <div class="col-md-2">

                                👥

                                {{ number_format($streamer->followers) }}

                            </div>

                            <div class="col-md-2">

                                💰

                                Rp {{ number_format($streamer->total_donasi) }}

                            </div>

                            <div class="col-md-2">

                                🏦

                                Rp {{ number_format($streamer->balance) }}

                            </div>

                            <div class="col-md-1">

                                <a
                                    href="/admin-streamers/{{ $streamer->id }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    Detail
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

                <div class="mt-4">

                    {{ $streamers->links() }}

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>