<div class="col-md-2 sidebar p-3">

    <div class="logo mb-4">
        KAsistream
    </div>

    <a href="/admin-dashboard"
       class="menu-item {{ request()->is('admin-dashboard') ? 'active-menu' : '' }}">
        <i class="fa-solid fa-table-columns"></i>
        Dashboard
    </a>

    <a href="/admin-users"
       class="menu-item {{ request()->is('admin-users*') ? 'active-menu' : '' }}">
        <i class="fa-solid fa-users"></i>
        Data User
    </a>

    <a href="/admin-streamers"
       class="menu-item {{ request()->is('admin-streamers*') ? 'active-menu' : '' }}">
        <i class="fa-solid fa-video"></i>
        Data Streamer
    </a>

    <a
    href="/admin-streamer-ranking"
    class="menu-item"
>
    <i class="fa-solid fa-trophy"></i>
    Ranking Streamer
    </a>

    <a href="/admin-donations"
       class="menu-item {{ request()->is('admin-donations*') ? 'active-menu' : '' }}">
        <i class="fa-solid fa-money-bill-wave"></i>
        Data Donasi
    </a>

    <a href="/admin-withdraws"
    class="menu-item {{ request()->is('admin-withdraws*') ? 'active-menu' : '' }}">

        <i class="fa-solid fa-wallet"></i>
        Withdraw Request

    </a>

   <a href="/admin-reports"
        class="menu-item {{ request()->is('admin-reports') ? 'active-menu' : '' }}">

            <i class="fa-solid fa-chart-line"></i>
            Laporan
    </a>

    <form action="/logout"
          method="POST"
          class="mt-4">

        @csrf

        <button
            type="submit"
            class="btn btn-danger w-100">

            Logout

        </button>

    </form>

</div>