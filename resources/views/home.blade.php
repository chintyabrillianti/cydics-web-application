<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yayasan Deparis Peduli</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold text-primary">
            Yayasan Deparis Peduli
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link">Program</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link">Artikel</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link">Kegiatan</a>
                </li>

                <li class="nav-item ms-3">

    @auth

        <a href="{{ route('dashboard') }}"
           class="btn btn-primary">
            Dashboard
        </a>

    @endauth

    @guest

        <a href="{{ route('login') }}"
           class="btn btn-outline-primary">
            Login
        </a>

    @endguest

</li>

            </ul>

        </div>

    </div>

</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>