<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CYDICS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

</head>

<body>
    <nav id="mainNavbar" class="navbar navbar-expand-lg">

        <div class="container">

            <!-- Logo -->

            <a href="/" class="navbar-brand fw-bold">

                <span class="text-cydics">
                    CYDICS
                </span>

                <div style="
            font-size:.72rem;
            color:#64748b;
            margin-top:-3px;
            letter-spacing:.5px;
            ">
                    "Cinta Yatim Duafa" Indonesian Communities
                </div>

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- Menu Tengah -->

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#program" id="nav-program">
                            Program Aktif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#visi-misi" id="nav-visi-misi">
                            Visi & Misi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#kegiatan" id="nav-kegiatan">
                            Kegiatan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#dampak" id="nav-dampak">
                            Donasi Al-Farisi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#donasi" id="nav-donasi">
                            Donasi
                        </a>
                    </li>

                </ul>

                <!-- Tombol Kanan -->

                <div>

                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        Register
                    </a>

                    <a href="{{ route('login') }}" class="btn btn-cydics">
                        Login
                    </a>

                </div>

            </div>

        </div>

    </nav>
</body>

<section class="position-relative overflow-hidden" style="
    min-height:650px;
    background:url('{{ asset('storage/' . $website->banner) }}');
    background-size:cover;
    background-position:center;">

    <div class="position-absolute top-0 start-0 w-100 h-100" style="
background:linear-gradient(
90deg,
rgba(15,23,42,.80),
rgba(15,23,42,.28)
);
backdrop-filter:blur(1px);
">
    </div>

    <div class="container position-relative">

        <div class="row align-items-center" style="min-height:720px;">

            <div class="col-lg-6 text-white">

                <span class="badge px-3 py-2 mb-3 hero-animate hero-delay-1" style="
                    background:#0B3B91;
                    border-radius:30px;
                    ">

                    YAYASAN AL-FARISI INDONESIA

                </span>

                <h1 class="fw-bold hero-title hero-animate hero-delay-2">

                    {!! nl2br($website->hero_title ?? 'Setiap Bantuan Setiap Kehidupan Berarti') !!}

                </h1>

                <p class="lead mt-4 hero-desc hero-animate hero-delay-3">

                    {{ $website->hero_subtitle ?? '' }}

                </p>

                <div class="mt-4 hero-buttons hero-animate hero-delay-4">

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->telepon ?? '') }}?text=Halo%20Admin%20Yayasan%20Al-Farisi%20Indonesia.%0A%0ASaya%20ingin%20mendaftar%20sebagai%20donatur.%0A%0ANama%20:%20%0AEmail%20:%20%0ANomor%20HP%20:%20%0AKota%20:%20%0A%0AMohon%20informasi%20lebih%20lanjut.%20Terima%20kasih."
    target="_blank" class="btn btn-light btn-lg">

                        <i class="bi bi-whatsapp me-2"></i>
                        Donasi Sekarang

                    </a>

                    <a href="#program" class="btn btn-outline-light btn-lg px-4">

                        <i class="bi bi-arrow-down-circle me-2"></i>
                        Pelajari Lebih Lanjut
                    </a>

                </div>

            </div>
            <div class="col-lg-6 d-flex">

                <div class="card border-0 shadow-lg mx-auto position-relative hero-card" style="
width:360px;
border-radius:24px;
top:40px;
padding:12px;">
                    <div class="card-body bg-white p-5">

                        <div class="text-center">

                            <small class="text-uppercase text-muted fw-semibold">
                                Penerima Bantuan Aktif
                            </small>

                            <h1 class="fw-bold mt-3 mb-2 text-cydics counter" data-target="{{ $totalPenerima }}"
                                style="font-size:72px;">
                                0
                            </h1>

                            <p class="text-muted mb-4">
                                Penerima Bantuan
                            </p>

                        </div>

                        <div class="d-flex justify-content-center gap-5">

                            <small class="text-success">
                                ✓ Terverifikasi
                            </small>

                            <small class="text-success">
                                ✓ Tepat Sasaran
                            </small>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">

                            <small style="color:#64748b;">
                                by Yayasan Al-Farisi Indonesia
                            </small>
                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
<section id="penerima" class="py-5" style="background:#FFFFFF;">

    <div class="container">

        <div class="row g-4">

            <div class="col-lg col-md-6">

                <div class="card h-100 stats-card" style="
border:1px solid #E5EAF3 !important;
border-radius:16px;
box-shadow:none;
">

                    <div class="card-body d-flex align-items-center p-4">

                        <div class="stats-icon me-3" style="
width:52px;
height:52px;
background:#EEF2FF;
color:#0B3B91;
font-size:22px;
">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>
                            <small class="text-muted text-uppercase fw-semibold">
                                JUMLAH DONATUR
                            </small>

                            <h4 class="fw-bold mb-0 text-cydics counter" data-target="{{ $totalDonatur }}">
                                0
                            </h4>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg col-md-6">

                <div class="card h-100 stats-card" style="
border:1px solid #E5EAF3 !important;
border-radius:16px;
">

                    <div class="card-body d-flex align-items-center p-4">

                        <div class="stats-icon me-3" style="
width:52px;
height:52px;
background:#E8F4FF;
color:#0B3B91;
font-size:22px;
">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div>
                            <small class="text-muted text-uppercase fw-semibold">
                                TOTAL DONASI
                            </small>

                            <h4
    class="fw-bold mb-0 text-cydics counter-rupiah"
    data-target="{{ $totalDonasi }}">
    Rp 0
</h4>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg col-md-6">

                <div class="card h-100 stats-card" style="
border:1px solid #E5EAF3 !important;
border-radius:16px;
">

                    <div class="card-body d-flex align-items-center p-4">

                        <div class="stats-icon me-3" style="
width:52px;
height:52px;
background:#EEF2FF;
color:#0B3B91;
font-size:22px;
">
                            <i class="bi bi-hand-thumbs-up"></i>
                        </div>

                        <div>
                            <small class="text-muted text-uppercase fw-semibold">
                                PENERIMA MANFAAT
                            </small>

                            <h4 class="fw-bold mb-0 text-cydics counter" data-target="{{ $totalPenerima }}">
                                0
                            </h4>

                            <small class="text-muted">
                                Anak & Lansia
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg col-md-6">

                <div class="card h-100 stats-card" style="
border:1px solid #E5EAF3 !important;
border-radius:16px;
">

                    <div class="card-body d-flex align-items-center p-4">

                        <div class="stats-icon me-3" style="
width:52px;
height:52px;
background:#E8F4FF;
color:#0B3B91;
font-size:22px;
">
                            <i class="bi bi-clipboard-check"></i>
                        </div>

                        <div>
                            <small class="text-muted text-uppercase fw-semibold">
                                PROGRAM AKTIF
                            </small>

                            <h4 class="fw-bold mb-0 text-cydics counter" data-target="{{ $programAktif }}">
                                0
                            </h4>

                            <small class="text-muted">
                                Program
                            </small>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
<section id="program" class="py-5 fade-up" style="
background:linear-gradient(
180deg,
#F4F7FC,
#EEF3FA
);
">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge px-3 py-2 mb-3" style="
background:#EEF2FF;
color:#0B3B91;
border-radius:30px;
">
                Program Aktif
            </span>

            <div>
                <h2 class="fw-bold mb-3" style="
font-size:3rem;
line-height:1.2;
color:#0B3B91;
">
                    Program Aktif
                </h2>

                <p class="text-muted mt-3" style="
max-width:700px;
margin:auto;
font-size:1.1rem;
">
                    Program bantuan sosial yang sedang berjalan
                    dan dapat diikuti oleh masyarakat sesuai
                    kategori penerima bantuan.
                </p>
            </div>

        </div>

        <div id="programCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000"
            data-bs-pause="false">

            <div class="carousel-inner">

                @foreach($programs->chunk(2) as $index => $chunk)

                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                        <div class="row g-4 justify-content-center">

                            @foreach($chunk as $program)

                                <div class="col-lg-6 d-flex">

                                    <div class="card border-0 shadow-sm overflow-hidden program-card">

                                        <img src="{{ asset('storage/' . $program->foto) }}" alt="{{ $program->nama }}">

                                        <div class="card-body p-4">

                                            <h4 class="fw-bold">
                                                {{ $program->nama }}
                                            </h4>

                                            <p class="text-muted">
                                                {{ $program->deskripsi }}
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#programCarousel" data-bs-slide="prev"
                style="width:60px;">

                <span class="carousel-control-prev-icon"></span>

            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#programCarousel" data-bs-slide="next"
                style="width:60px;">

                <span class="carousel-control-next-icon"></span>

            </button>

        </div>

    </div>

</section>

<section id="visi-misi" class="fade-up py-5" style="background:#FFFFFF;">

    <div class="container" style="max-width:1000px;">

        <div class="text-center mb-5">

            <span class="badge px-3 py-2 mb-3" style="
background:#EEF2FF;
color:#0B3B91;
border-radius:30px;
">
                Visi Misi
            </span>

            <h2 class="fw-bold" style="
font-size:3rem;
color:#0B3B91;
">
                Visi & Misi Yayasan
            </h2>

            <p class="text-muted mx-auto" style="
max-width:750px;
font-size:1.1rem;
">
                Komitmen Yayasan Al-Farisi Indonesia
                dalam membangun masyarakat yang lebih
                sejahtera melalui bantuan sosial yang
                amanah dan berkelanjutan.
            </p>

        </div>

        <!-- VISI -->
        <div class="card border-0 shadow-sm mb-4" style="
border-radius:24px;
padding:10px;
">

            <div class="card-body p-4 p-md-5">

                <div class="d-flex align-items-center mb-3">

                    <div style="
width:55px;
height:55px;
background:#EEF2FF;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
">

                        <i class="bi bi-eye-fill" style="
color:#0B3B91;
font-size:22px;
"></i>

                    </div>

                    <h3 class="fw-bold ms-3 mb-0" style="color:#0B3B91;">
                        Visi
                    </h3>

                </div>

                <p class="text-muted mb-0" style="
font-size:1.05rem;
line-height:1.9;
">
                    {{ $website->visi ?? '' }}
                </p>

            </div>
        </div>

        <!-- MISI -->

        <div class="card border-0 shadow-sm" style="
border-radius:24px;
padding:10px;
">

            <div class="card-body p-4 p-md-5">

                <div class="d-flex align-items-center mb-4">

                    <div style="
width:55px;
height:55px;
background:#EEF2FF;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
">

                        <i class="bi bi-bullseye" style="
color:#0B3B91;
font-size:22px;
"></i>

                    </div>

                    <h3 class="fw-bold ms-3 mb-0" style="color:#0B3B91;">
                        Misi
                    </h3>

                </div>

                @foreach(explode("\n", $website->misi ?? '') as $misi)
                    <div class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                        <span>{{ $misi }}</span>
                    </div>
                @endforeach

            </div>
        </div>

</section>

<section id="kegiatan" class="fade-up py-5" style="background:#F2F6FC;">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge px-3 py-2 mb-3" style="
background:#EEF2FF;
color:#0B3B91;
border-radius:30px;
">
                Kegiatan
            </span>

            <h2 class="fw-bold" style="
font-size:3rem;
color:#0B3B91;
">
                Kegiatan Kami
            </h2>

            <div class="row g-4">

                @forelse($kegiatans as $kegiatan)

                            <div class="col-lg-6">

                                <div class="card border-0 shadow-sm h-100" style="border-radius:22px;overflow:hidden;">

                                    <img src="{{ asset('storage/' . $kegiatan->foto) }}" style="
                            height:320px;
                            width:100%;
                            object-fit:cover;">

                                    <div class="card-body p-4">

                                        <small class="fw-bold" style="color:#0B3B91;">

                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)
                    ->translatedFormat('d F Y') }}

                                        </small>

                                        <h4 class="fw-bold mt-2">

                                            {{ $kegiatan->judul }}

                                        </h4>

                                        <p class="text-muted mb-0">

                                            {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 120) }}

                                        </p>

                                    </div>

                                </div>

                            </div>

                @empty

                    <div class="col-12">

                        <div class="alert alert-info text-center">

                            Belum ada kegiatan.

                        </div>

                    </div>

                @endforelse

            </div>

            <div class="text-center mt-5">

                <a href="{{ route('kegiatan') }}" class="btn btn-cydics" style="
   background:#0B3B91;
   color:white;
   border:none;
   border-radius:14px;
   font-weight:600;
   transition:.3s;
   ">
                    Lihat Selengkapnya
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>

            </div>

        </div>

</section>

<section id="dampak" class="fade-up py-5" style="background:#FFFFFF;">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold" style="
font-size:2.8rem;
color:#0B3B91;
">
                Mengapa Berdonasi Melalui CYDICS?
            </h2>

            <p class="text-muted mt-3" style="
max-width:700px;
margin:auto;
font-size:1.1rem;
">
                Kami berkomitmen menjaga kepercayaan donatur
                melalui sistem yang transparan, tepat sasaran,
                dan dapat dipantau secara berkala.
            </p>

        </div>

        <div class="row g-4">

            <!-- CARD 1 -->

            <div class="col-lg-4">

                <div class="card h-100 border-0 shadow-sm" style="
border-radius:20px;
">

                    <div class="card-body p-4">

                        <div class="mb-4 d-flex align-items-center justify-content-center" style="
width:70px;
height:70px;
border-radius:50%;
background:#EEF2FF;
">

                            <i class="bi bi-shield-check" style="
font-size:32px;
color:#0B3B91;
"></i>

                        </div>

                        <h4 class="fw-bold mb-3" style="color:#0B3B91;">
                            Transparan
                        </h4>

                        <p class="text-muted mb-0">
                            Setiap dana yang diterima dan disalurkan
                            dicatat dengan jelas sehingga donatur dapat
                            mengetahui penggunaan bantuan secara terbuka.
                        </p>

                    </div>

                </div>

            </div>

            <!-- CARD 2 -->

            <div class="col-lg-4">

                <div class="card h-100 border-0 shadow-sm" style="
border-radius:20px;
">

                    <div class="card-body p-4">

                        <div class="mb-4 d-flex align-items-center justify-content-center" style="
width:70px;
height:70px;
border-radius:50%;
background:#EEF2FF;
">

                            <i class="bi bi-bullseye" style="
font-size:32px;
color:#0B3B91;
"></i>

                        </div>

                        <h4 class="fw-bold mb-3" style="color:#0B3B91;">
                            Tepat Sasaran
                        </h4>

                        <p class="text-muted mb-0">
                            Setiap penerima bantuan melalui proses
                            verifikasi dan klasifikasi sistem sehingga bantuan diberikan kepada
                            mereka yang benar-benar membutuhkan.
                        </p>

                    </div>

                </div>

            </div>

            <!-- CARD 3 -->

            <div class="col-lg-4">

                <div class="card h-100 border-0 shadow-sm" style="
border-radius:20px;
">

                    <div class="card-body p-4">

                        <div class="mb-4 d-flex align-items-center justify-content-center" style="
width:70px;
height:70px;
border-radius:50%;
background:#EEF2FF;
">

                            <i class="bi bi-bar-chart-line" style="
font-size:32px;
color:#0B3B91;
"></i>

                        </div>

                        <h4 class="fw-bold mb-3" style="color:#0B3B91;">
                            Laporan Berkala
                        </h4>

                        <p class="text-muted mb-0">
                            Perkembangan program dan penyaluran bantuan
                            dapat dipantau secara berkala untuk menjaga
                            akuntabilitas yayasan.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<section id="donasi" class="py-5 text-center text-white" style="
background:linear-gradient(
135deg,
#0B3B91,
#0A2E73
);
padding-top:120px;
padding-bottom:120px;
">

    <div class="container">

        <h2 class="fw-bold mb-3">
            Wujudkan Senyum Mereka Hari Ini
        </h2>

        <p class="mb-4">
            Setiap donasi yang Anda berikan membantu
            anak yatim, piatu, yatim-piatu,
            dan keluarga duafa menjalani kehidupan
            yang lebih baik.
        </p>

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->telepon ?? '') }}?text=Halo%20Admin%20Yayasan%20Al-Farisi%20Indonesia.%0A%0ASaya%20ingin%20mendaftar%20sebagai%20donatur.%0A%0ANama%20:%20%0AEmail%20:%20%0ANomor%20HP%20:%20%0AKota%20:%20%0A%0AMohon%20informasi%20lebih%20lanjut.%20Terima%20kasih."
            target="_blank" class="btn btn-light btn-lg px-5 py-3 fw-semibold" style="border-radius:14px;">
            <i class="bi bi-whatsapp me-2"></i>
            Daftar Jadi Donatur Sekarang
        </a>

    </div>

</section>

<footer class="fade-up pt-5 pb-3" style="
background:#F8FAFC;
border-top:1px solid #E5EAF3;
">

    <div class="container">

        <div class="row gy-4">

            <!-- KOLOM 1 -->

            <div class="col-lg-5">

                <h4 class="fw-bold mb-3" style="color:#0B3B91;">
                    Yayasan Al-Farisi Indonesia
                </h4>

                <p class="text-muted" style="
line-height:1.8;
max-width:420px;
">
                    Sistem bantuan sosial berbasis web
                    untuk mendukung transparansi,
                    akuntabilitas, dan penyaluran bantuan
                    yang tepat sasaran bagi masyarakat.
                </p>

                <div class="d-flex gap-4 mt-4 flex-wrap">

                    <div>
                        <h5 class="fw-bold text-cydics mb-0">
                            {{ $totalPenerima }}
                        </h5>
                        <small class="text-muted">
                            Penerima
                        </small>
                    </div>

                    <div>
                        <h5 class="fw-bold text-cydics mb-0">
                            {{ $totalDonatur }}
                        </h5>
                        <small class="text-muted">
                            Donatur
                        </small>
                    </div>

                    <div>
                        <h5 class="fw-bold text-cydics mb-0">
                            {{ $programAktif }}
                        </h5>
                        <small class="text-muted">
                            Program
                        </small>
                    </div>

                </div>
            </div>
            <!-- KOLOM 2 -->

            <div class="col-lg-3">

                <h6 class="fw-bold mb-3" style="color:#0B3B91;">
                    Navigasi
                </h6>

                <ul class="list-unstyled">

                    <li class="mb-3">
                        <a href="#program" class="text-decoration-none text-muted">
                            Program Aktif
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="#visi-misi" class="text-decoration-none text-muted">
                            Visi & Misi
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="#kegiatan" class="text-decoration-none text-muted">
                            Kegiatan
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="#dampak" class="text-decoration-none text-muted">
                            Donasi Al-Farisi
                        </a>
                    </li>

                </ul>

            </div>

            <!-- KOLOM 3 -->

            <div class="col-lg-4">

                <h6 class="fw-bold mb-3" style="color:#0B3B91;">
                    Hubungi Kami
                </h6>

                <div class="d-flex align-items-center mb-3">

                    <a href="mailto:{{ $website->email ?? '-' }}" class="text-decoration-none">

                        <div style="
width:42px;
height:42px;
background:#EDF2FF;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
">

                            <i class="bi bi-envelope-fill" style="
color:#0B3B91;
font-size:18px;
"></i>

                        </div>

                    </a>

                    <span class="ms-3 text-muted">
                        {{ $website->email ?? '-' }}
                    </span>

                </div>

                <div class="d-flex align-items-center mb-3">

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->telepon ?? '') }}?text=Halo%20Admin%20Yayasan%20Al-Farisi%20Indonesia.%0A%0ASaya%20ingin%20bertanya,"
                        target="_blank" class="text-decoration-none">

                        <div style="
width:42px;
height:42px;
background:#EDF2FF;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
">

                            <i class="bi bi-whatsapp" style="
color:#0B3B91;
font-size:18px;
"></i>

                        </div>

                    </a>

                    <span class="ms-3 text-muted">
                        {{ $website->telepon ?? '-' }}
                    </span>

                </div>

                <div class="d-flex align-items-center">

                    <div style="
width:42px;
height:42px;
background:#EDF2FF;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
">

                        <i class="bi bi-geo-alt-fill" style="
color:#0B3B91;
font-size:18px;
"></i>

                    </div>

                    <span class="ms-3 text-muted">
                        {{ $website->alamat ?? '-' }}
                    </span>

                </div>

            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex
justify-content-between
align-items-center
flex-wrap">

            <div class="d-flex gap-3 mb-3">

                @if($website->instagram)
                            <a href="{{ $website->instagram }}" target="_blank" class="text-decoration-none">

                                <i class="bi bi-instagram" style="
                       font-size:24px;
                       color:#0B3B91;
                       ">
                                </i>

                            </a>
                @endif

                @if($website->youtube)
                            <a href="{{ $website->youtube }}" target="_blank" class="text-decoration-none">

                                <i class="bi bi-youtube" style="
                       font-size:24px;
                       color:#0B3B91;
                       ">
                                </i>

                            </a>
                @endif

            </div>

            <p class="text-muted mb-0">
                © {{ date('Y') }} Yayasan Al-Farisi Indonesia
            </p>

        </div>

    </div>

</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('scroll', function () {

        let navbar =
            document.getElementById('mainNavbar');

        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

    });

    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    window.addEventListener("scroll", () => {

        let current = "";

        sections.forEach(section => {

            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;

            if (window.scrollY >= sectionTop) {
                current = section.getAttribute("id");
            }

        });

        navLinks.forEach(link => {

            link.classList.remove("active");

            if (link.getAttribute("href") === "#" + current) {
                link.classList.add("active");
            }

        });

    });

</script>
<script>

    const observer = new IntersectionObserver(entries => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }

        });

    }, {
        threshold: 0.15
    });

    document.querySelectorAll('.fade-up').forEach(el => {
        observer.observe(el);
    });

</script>
<script>
    document.documentElement.style.scrollBehavior = "smooth";
</script>
<script>
window.addEventListener("load", () => {

    // Counter angka biasa
    document.querySelectorAll(".counter").forEach(counter => {

    const target = parseInt(counter.dataset.target);
    let current = 0;

    const timer = setInterval(() => {

        current++;

        counter.innerText = current.toLocaleString("id-ID");

        if (current >= target) {
            clearInterval(timer);
        }

    }, 120); // makin besar makin pelan

});

    // Counter rupiah
    document.querySelectorAll(".counter-rupiah").forEach(counter => {

        const target = parseInt(counter.dataset.target) || 0;
        let current = 0;

        const speed = Math.max(1000, Math.ceil(target / 80));

        const timer = setInterval(() => {

            current += speed;

            if (current >= target) {
                current = target;
                clearInterval(timer);
            }

            counter.innerText = "Rp " + current.toLocaleString("id-ID");

        }, 20);

    });

});
</script>
</body>

</html>