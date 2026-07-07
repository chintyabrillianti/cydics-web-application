<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CYDICS Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="sidebar">

        <div class="logo">

            <h4>CYDICS</h4>

            <small class="text-muted">
                "Cinta Yatim Duafa" Indonesian Communities
            </small>

        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>

        <a href="{{ route('admin.website') }}"
            class="menu-item {{ request()->routeIs('admin.website') ? 'active' : '' }}">
            🌐 Manajemen Website
        </a>

        <a href="{{ route('admin.pengajuan') }}"
            class="menu-item {{ request()->routeIs('admin.pengajuan') ? 'active' : '' }}">
            📄 Kelola Pengajuan
        </a>

        <a href="{{ route('admin.arsip') }}" class="menu-item {{ request()->routeIs('admin.arsip') ? 'active' : '' }}">
            📁 Arsip Pengajuan
        </a>

        <a href="{{ route('admin.donasi') }}"
            class="menu-item {{ request()->routeIs('admin.donasi') ? 'active' : '' }}">
            💰 Kelola Donasi
        </a>

        <a href="{{ route('admin.laporan') }}"
            class="menu-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            📈 Laporan Bulanan
        </a>

        <a href="{{ route('admin.laporan.tahunan') }}"
            class="menu-item {{ request()->routeIs('admin.laporan.tahunan') ? 'active' : '' }}">
            📊 Laporan Tahunan
        </a>

        <div style="margin-top:auto;padding:15px;">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn btn-danger w-100">
                    Logout
                </button>

            </form>

        </div>

    </div>

<div class="main-content">

    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show mb-4">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show mb-4">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif

        @yield('content')

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
const toggle = document.getElementById('sidebarToggle');

toggle.addEventListener('click', function () {
    document.body.classList.toggle('sidebar-hide');
});
</script>
</body>

</html>