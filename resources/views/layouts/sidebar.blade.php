<div class="col-md-3 col-lg-2 sidebar p-3">

@auth

<div class="sidebar-user text-center mb-4">

    @if(Auth::user()->foto)

        <img
    src="{{ asset('uploads/profile/' . Auth::user()->foto) }}"
    class="sidebar-avatar"
    width="70"
    height="70"
    alt="Avatar"
>

    @else

        <img
    src="https://via.placeholder.com/70"
    class="sidebar-avatar"
    width="70"
    height="70"
    alt="Avatar"
>

    @endif

    <h6 class="mt-3 mb-1">

        {{ Auth::user()->name }}

    </h6>

    <small class="text-muted">

        {{ Auth::user()->is_streamer ? 'Streamer' : 'Member' }}

    </small>

</div>

@endauth

    

    {{-- MENU USER LOGIN --}}
    @auth

        <a href="/user-dashboard"
           class="menu-item {{ request()->is('user-dashboard') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-house me-2"></i>
            Beranda

        </a>

        <a href="/streamers"
           class="menu-item {{ request()->is('streamers') || request()->is('streamer/*') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-users me-2"></i>
            Streamer

        </a>

        <a href="/following"
           class="menu-item {{ request()->is('following') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-star me-2"></i>
            Mengikuti

        </a>

        <a href="/riwayat-donasi"
           class="menu-item {{ request()->is('riwayat-donasi') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-clock-rotate-left me-2"></i>
            Riwayat Donasi

        </a>

        <!-- MENU WALLET - TAMBAHKAN DISINI (SETELAH RIWAYAT DONASI) -->
        <a href="/wallet"
           class="menu-item {{ request()->is('wallet') || request()->is('wallet-history') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-wallet me-2"></i>
            Wallet

        </a>

        @if(auth()->user()->is_streamer)

            <hr class="sidebar-divider">

            <small class="sidebar-title">
                STREAMER CENTER
            </small>

            <hr class="sidebar-divider">

            <a href="/streamer-dashboard"
               class="menu-item {{ request()->is('streamer-dashboard') ? 'active-menu' : '' }}">

                <i class="fa-solid fa-tower-broadcast me-2"></i>
                Dashboard Streamer

            </a>

            <a href="/streamer-donations"
               class="menu-item {{ request()->is('streamer-donations') ? 'active-menu' : '' }}">

                <i class="fa-solid fa-money-bill-wave me-2"></i>
                Donasi Masuk

            </a>

            <a href="/streamer-statistics"
               class="menu-item {{ request()->is('streamer-statistics') ? 'active-menu' : '' }}">

                <i class="fa-solid fa-chart-column me-2"></i>
                Statistik

            </a>

            <!-- MENU WALLET DI STREAMER CENTER DIHAPUS -->

        @endif

        <a href="/profile"
           class="menu-item {{ request()->is('profile') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-user me-2"></i>
            Profil

        </a>

        <form
            action="/logout"
            method="POST"
            class="mt-4"
        >

            @csrf

            <button
                type="submit"
                class="btn logout-btn w-100"
            >

                <i class="fa-solid fa-right-from-bracket me-2"></i>

                Logout

            </button>

        </form>

    @endauth

    {{-- MENU GUEST --}}
@guest

    <a href="/streamers"
   class="sidebar-streamer-btn">

    <i class="fa-solid fa-users me-2"></i>
    Streamer

</a>

<div class="auth-card">

    <h6>
        <i class="fa-solid fa-user-circle me-2"></i>
        Belum Punya Akun?
    </h6>

    <p>
        Simpan riwayat donasi dan akses fitur lengkap KAsistream.
    </p>

    <a href="/login" class="sidebar-login-btn">

        <i class="fa-solid fa-right-to-bracket me-2"></i>
        Masuk Akun

    </a>

    <a href="/register" class="sidebar-register-btn">

        <i class="fa-solid fa-user-plus me-2"></i>
        Buat Akun

    </a>

</div>

@if(
    request()->is('streamers') ||
    request()->is('streamer/*') ||
    request()->is('donasi/*') ||
    request()->is('payment-method') ||
    request()->is('payment-detail') ||
    request()->is('payment-qr/*')
)

    <div class="sidebar-info-card">

        <i class="fa-solid fa-shield-halved"></i>

        <h6>Aman & Terpercaya</h6>

        <p>
            Transaksi donasi diproses aman
            melalui sistem KAistream.
        </p>

    </div>

    <div class="sidebar-info-card">

        <i class="fa-solid fa-qrcode"></i>

        <h6>Pembayaran QRIS</h6>

        <p>
            OnoPay, DANA, OVO,
            GoPay dan Mobile Banking.
        </p>

    </div>

    <div class="sidebar-info-card">

        <i class="fa-solid fa-heart"></i>

        <h6>Dukung Streamer</h6>

        <p>
            Setiap donasi membantu streamer
            berkembang dan berkarya.
        </p>

    </div>

    @endif

@endguest

</div>