<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register - KAsistream</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;

    background:
    radial-gradient(
        circle at top,
        #1e3a8a 0%,
        #050816 60%
    );

    display:flex;
    justify-content:center;
    align-items:center;

    padding:20px;
}

/* CARD */

.auth-card{

    width:100%;
    max-width:1100px;

    background:rgba(15,23,42,.95);

    border-radius:30px;

    overflow:hidden;

    display:flex;

    box-shadow:
        0 0 25px rgba(37,99,235,.25),
        0 0 50px rgba(139,92,246,.15);

    border:1px solid rgba(139,92,246,.2);

    animation:fadeIn .5s ease;
}

/* LEFT SIDE */

.brand-side{

    width:45%;

    position:relative;

    overflow:hidden;

    background:
    radial-gradient(
        circle at top left,
        #4f46e5,
        #2563eb 35%,
        #8b5cf6 100%
    );

    color:white;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;

    padding:50px;
}

.brand-side::before{

    content:'';

    position:absolute;

    width:400px;
    height:400px;

    border-radius:50%;

    background:
    rgba(255,255,255,.05);

    top:-150px;
    left:-100px;
}

.brand-side::after{

    content:'';

    position:absolute;

    width:300px;
    height:300px;

    border-radius:50%;

    background:
    rgba(255,255,255,.03);

    bottom:-120px;
    right:-100px;
}

.logo-wrapper{

    position:relative;

    z-index:2;

    margin-bottom:25px;

    animation:
    floatLogo 4s ease-in-out infinite;
}

.logo-wrapper::before{

    content:'';

    position:absolute;

    width:280px;
    height:280px;

    border-radius:50%;

    background:
    radial-gradient(
        rgba(255,255,255,.18),
        transparent 70%
    );

    top:50%;
    left:50%;

    transform:
    translate(-50%,-50%);
}

.logo-img{

    width:280px;

    transform:scale(1.4);

    border-radius:25px;

    position:relative;

    z-index:2;

    transition:.4s;

    filter:
    drop-shadow(
        0 0 25px rgba(37,99,235,.8)
    )
    drop-shadow(
        0 0 40px rgba(139,92,246,.6)
    );
}

.logo-img:hover{

    transform:
    scale(1.05)
    rotate(-2deg);
}

.brand-side h1{

    position:relative;

    z-index:2;

    font-size:48px;

    font-weight:800;

    margin-bottom:10px;

    background:
    linear-gradient(
        90deg,
        #ffffff,
        #ddd6fe
    );

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.brand-side p{

    position:relative;

    z-index:2;

    font-size:18px;

    text-align:center;
}

.gaming-badge{

    position:relative;

    z-index:2;

    margin-top:20px;

    padding:10px 22px;

    border-radius:50px;

    background:
    rgba(255,255,255,.15);

    backdrop-filter:blur(10px);

    border:
    1px solid rgba(255,255,255,.2);

    font-size:14px;

    font-weight:600;
}

/* RIGHT SIDE */

.form-side{

    width:55%;

    padding:50px;

    color:white;
}

.form-title{

    text-align:center;

    font-size:34px;

    font-weight:700;

    margin-bottom:30px;
}

.form-group{

    margin-bottom:18px;
}

.form-group label{

    display:block;

    margin-bottom:8px;

    color:#cbd5e1;

    font-weight:500;
}

.form-control{

    width:100%;

    height:52px;

    border-radius:12px;

    background:#111827;

    border:1px solid #374151;

    color:white;

    padding:0 15px;

    transition:.3s;
}

.form-control:focus{

    outline:none;

    border-color:#8b5cf6;

    box-shadow:
    0 0 12px rgba(139,92,246,.5);
}

.file-input{

    width:100%;

    padding:12px;

    background:#111827;

    color:white;

    border-radius:12px;

    border:1px solid #374151;
}

.preview{

    width:120px;
    height:120px;

    object-fit:cover;

    border-radius:50%;

    border:3px solid #8b5cf6;

    display:none;

    margin:auto;
    margin-bottom:15px;
}

.btn-auth{

    width:100%;

    height:55px;

    border:none;

    border-radius:12px;

    background:
    linear-gradient(
        90deg,
        #2563eb,
        #8b5cf6
    );

    color:white;

    font-size:16px;

    font-weight:700;

    cursor:pointer;

    transition:.3s;
}

.btn-auth:hover{

    transform:translateY(-2px);

    box-shadow:
    0 0 20px rgba(139,92,246,.6);
}

.auth-link{

    text-align:center;

    margin-top:20px;

    color:#cbd5e1;
}

.auth-link a{

    color:#8b5cf6;

    text-decoration:none;

    font-weight:600;
}

.alert-danger{

    background:#dc2626;

    color:white;

    padding:15px;

    border-radius:12px;

    margin-bottom:20px;
}

.alert-danger ul{

    padding-left:20px;
}

@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes floatLogo{

    0%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-12px);
    }

    100%{
        transform:translateY(0px);
    }
}

@media(max-width:768px){

    .auth-card{
        flex-direction:column;
    }

    .brand-side,
    .form-side{
        width:100%;
    }

    .logo-img{
        width:180px;
    }

    .brand-side h1{
        font-size:34px;
    }
}

</style>

</head>
<body>

<div class="auth-card">

    <div class="brand-side">

        <div class="logo-wrapper">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="KAsistream"
                class="logo-img"
            >

        </div>

        <h1>KAsistream</h1>

        <p>
            Platform Donasi & Dukungan untuk Streamer Indonesia
        </p>

        <div class="gaming-badge">

            🎮 Gaming Donation Platform

        </div>

    </div>

    <div class="form-side">

        <h2 class="form-title">

            Register

        </h2>

        @if ($errors->any())

            <div class="alert-danger">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="/register"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <img
                id="preview"
                class="preview"
            >

            <div class="form-group">

                <label>Nama</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Nomor Telepon</label>

                <input
                    type="text"
                    name="onopay_phone"
                    class="form-control"
                    placeholder="08xxxxxxxxxx"
                    required
                >

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Foto Profil</label>

                <input
                    type="file"
                    name="foto"
                    accept="image/*"
                    class="file-input"
                    onchange="previewImage(event)"
                >

            </div>

            <button
                type="submit"
                class="btn-auth">

                Daftar

            </button>

            <div class="auth-link">

                Sudah punya akun?

                <a href="/login">

                    Login

                </a>

            </div>

        </form>

    </div>

</div>

<script>

function previewImage(event){

    let preview =
        document.getElementById('preview');

    preview.src =
        URL.createObjectURL(
            event.target.files[0]
        );

    preview.style.display='block';
}

</script>

</body>
</html>

