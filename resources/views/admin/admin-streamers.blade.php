<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Streamer - KAistream</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>

body{
    background:#f4f6f9;
}

.sidebar{
    min-height:100vh;
    background:linear-gradient(
        180deg,
        #0f172a,
        #111827
    );
}

.logo{
    color:white;
    font-size:24px;
    font-weight:bold;
}

.menu-item{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px 15px;
    border-radius:10px;
    margin-bottom:8px;
}

.menu-item:hover{
    background:rgba(255,255,255,.1);
    color:white;
}

.active-menu{
    background:rgba(255,255,255,.15);
}

.content-card{
    background:white;
    border-radius:15px;
    padding:20px;
}

.profile-img{
    width:50px;
    height:50px;
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

        <h3 class="mb-4">
            Data Streamer
        </h3>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <div class="table-responsive">
        <table class="table table-hover streamer-table">

            <thead>

                <tr>

                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Total Donasi</th>
                    <th>Total Transaksi</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @foreach($streamers as $streamer)

                    <tr>

                        <td>

                            @if($streamer->foto)

                                <img
                                    src="{{ asset('uploads/profile/'.$streamer->foto) }}"
                                    class="profile-img"
                                >

                            @endif

                        </td>

                        <td>
                            {{ $streamer->name }}
                        </td>

                        <td>
                            {{ $streamer->email }}
                        </td>

                        <td>

                            Rp {{ number_format(
                                \App\Models\Donasi::where(
                                    'streamer_id',
                                    $streamer->id
                                )->sum('nominal')
                            ) }}

                        </td>

                        <td>

                            {{ \App\Models\Donasi::where(
                                'streamer_id',
                                $streamer->id
                            )->count() }}

                        </td>

                        <td>

    <div class="action-buttons">

        <a
            href="/admin-streamers/{{ $streamer->id }}"
            class="btn btn-info btn-sm"
        >
            Detail
        </a>

        <form
            action="/admin-streamers/remove/{{ $streamer->id }}"
            method="POST"
        >
            @csrf

            <button
                class="btn btn-danger btn-sm"
            >
                Cabut Status
            </button>

        </form>

    </div>

</td>

                    </tr>

                @endforeach

            </tbody>

        </table>
        </div>

        {{ $streamers->links() }}

    </div>

</div>

</div>

</div>

</body>
</html>