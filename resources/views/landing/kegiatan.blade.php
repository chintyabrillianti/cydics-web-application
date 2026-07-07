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
    <div class="container py-4">

        <div class="d-flex align-items-center justify-content-between mb-5">

            <a href="/" class="btn btn-light border rounded-pill px-4">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <div class="text-center">

                <h3 class="fw-bold mb-0" style="color:#0B3B91;">
                    CYDICS
                </h3>

                <small style="color:#64748b;">
                    "Cinta Yatim Duafa" Indonesian Communities
                </small>

            </div>

            <div style="width:110px;"></div>

        </div>


        <body style="background:#F5F8FD;">

            <div class="container">

                <div class="text-center mb-5">

                    <h1 class="fw-bold" style="
    color:#0B3B91;
    font-size:3rem;">
                        Kegiatan Yayasan
                    </h1>

                    <p class="text-muted">
                        Dokumentasi kegiatan Yayasan Al-Farisi Indonesia.
                    </p>

                </div>
<div class="row g-4">

@forelse($kegiatans as $kegiatan)

<div class="col-lg-4 d-flex">
    <div class="card border-0 shadow-sm"
         style="border-radius:20px;">

        <img
            src="{{ asset('storage/'.$kegiatan->foto) }}"
            class="img-fluid"
            style="
                height:260px;
                width:100%;
                object-fit:cover;
                border-radius:20px 20px 0 0;">

        <div class="card-body">

            <small
                class="fw-bold"
                style="color:#0B3B91;">

                {{ \Carbon\Carbon::parse($kegiatan->tanggal)
                    ->translatedFormat('d F Y') }}

            </small>

            <h5 class="mt-2">

                {{ $kegiatan->judul }}

            </h5>

            <p class="text-muted mb-0">

                {{ $kegiatan->deskripsi }}

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

            </div>

            <footer class="text-center py-4 mt-5">

                <hr>

                <p class="text-muted mb-0">
                    © {{ date('Y') }} Yayasan Al-Farisi Indonesia
                </p>

            </footer>

        </body>

        </section>