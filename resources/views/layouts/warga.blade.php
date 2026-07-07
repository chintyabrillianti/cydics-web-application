@php

    $pengajuanSidebar = \App\Models\Pengajuan::where(
        'user_id',
        auth()->id()
    )->latest()->first();

    $disableProgram = false;

    if ($pengajuanSidebar) {

        if (
            in_array(
                $pengajuanSidebar->status_pengajuan,
                ['Menunggu', 'Perbaikan']
            )
        ) {
            $disableProgram = true;
        }

        if (
            $pengajuanSidebar->status_penerima == 'Aktif'
        ) {
            $disableProgram = true;
        }

        if (
            $pengajuanSidebar->status_pengajuan == 'Ditolak' &&
            $pengajuanSidebar->tanggal_boleh_ajukan &&
            now()->lt(
                $pengajuanSidebar->tanggal_boleh_ajukan
            )
        ) {
            $disableProgram = true;
        }
    }

@endphp
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CYDICS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/warga.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="logo">
                <h4>CYDICS</h4>

                <small class="text-muted">
                    "Cinta Yatim Duafa"
                    <br>
                    Indonesian Communities
                </small>
            </div>

            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Beranda</span>
            </a>

            @if($disableProgram)

                <a href="#" class="menu-item disabled" style="opacity:.5;pointer-events:none;">
                    <i class="bi bi-grid-fill"></i>
                    <span>Program Bantuan</span>
                </a>

            @else

                <a href="{{ route('pengajuan.index') }}"
                    class="menu-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Program Bantuan</span>
                </a>

            @endif

            <a href="{{ route('warga.profile') }}"
                class="menu-item {{ request()->routeIs('warga.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Profil Saya</span>
            </a>

            <div style="margin-top:auto;padding:15px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn btn-danger w-100">
                        Logout
                    </button>
                </form>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="main-content">

            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            @yield('content')

        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.getElementById('sidebarToggle').addEventListener('click', function () {
    document.body.classList.toggle('sidebar-hide');
});
</script>
</body>

</html>