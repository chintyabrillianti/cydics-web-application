@extends('layouts.donatur')

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="welcome-section d-flex align-items-center justify-content-between">

        <div>

            <h2 class="welcome-title">
                Selamat Datang, {{ auth()->user()->name }}
            </h2>

            <p>
                Kontribusi Anda memberikan harapan baru bagi mereka yang membutuhkan.
            </p>

        </div>

        <div class="dashboard-avatar">

            @if(auth()->user()->foto)

                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil">

            @else

                <img src="{{ asset('images/default-profile.png') }}" alt="Default">

            @endif

        </div>

    </div>

    <div class="donation-summary-card">

        <div>

            <small>
                TOTAL DONASI SAYA
            </small>

            <h1>
                Rp {{ number_format($totalDonasi, 0, ',', '.') }}
            </h1>

            <div class="summary-info">

                <div>
                    <span>Donasi Terakhir</span>
                    <strong>
                        @if($donasiTerakhir)
                            {{ $donasiTerakhir->created_at->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </strong>
                </div>

                <div>

                    <span>Status Donatur</span>

                    <strong>

                        {{ auth()->user()->status_donatur }}

                    </strong>

                </div>

            </div>

        </div>

        <div class="quote-side">

            <h4>"Terima Kasih"</h4>

            <p>
                Setiap rupiah yang Anda berikan adalah langkah nyata menuju perubahan.
            </p>

            <small>
                - Yayasan Al-Farisi Indonesia
            </small>

        </div>

    </div>

    <div class="dashboard-grid">

        <div class="recent-donation-card">

            <div class="section-header">

                <h5>Donasi Terakhir Saya</h5>

                <div class="d-flex align-items-center gap-2">

                    <form method="GET" class="d-flex gap-2">

                        <div class="mini-search">

                            <i class="bi bi-search"></i>

                            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari program">

                        </div>

                        <button class="btn btn-primary">

                            Cari

                        </button>

                    </form>

                </div>

            </div>

            <table class="table mb-0">

                <thead>

                    <tr>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Program</th>
                        <th>Status Verifikasi</th>
                    </tr>

                </thead>
                <tbody>

                    @forelse($donasis as $donasi)

                        <tr>

                            <td>
                                {{ $donasi->created_at->format('d M Y') }}
                            </td>

                            <td>
                                Rp {{ number_format($donasi->nominal ?? 0, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $donasi->program->nama }}
                            </td>

                            <td>
                                <span class="
                                                                        @if($donasi->status == 'Menunggu')
                                                                            badge-menunggu
                                                                        @elseif($donasi->status == 'Terverifikasi')
                                                                            badge-verifikasi
                                                                        @else
                                                                            badge-ditolak
                                                                        @endif
                                                                        ">
                                    {{ $donasi->status }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center text-muted">

                                Belum ada riwayat donasi.

                            </td>

                        </tr>

                    @endforelse
                </tbody>

            </table>

            <div class="text-end mt-3">

                <a href="{{ route('donatur.riwayat') }}">

                    Lihat Semua →

                </a>

            </div>

        </div>

        <div class="impact-card">

            <h5>Statistik Yayasan</h5>

            <div class="impact-item">

                <i class="bi bi-heart-fill"></i>

                <div>

                    <strong>{{ $totalProgram }}</strong>

                    <small>Program Aktif</small>

                </div>

            </div>

            <div class="impact-item">

                <i class="bi bi-people-fill"></i>

                <div>

                    <strong>{{ $totalDonatur }}</strong>

                    <small>Donatur Terdaftar</small>

                </div>

            </div>

            <div class="impact-item">

                <i class="bi bi-person-check-fill"></i>

                <div>

                    <strong>{{ $totalPenerima }}</strong>

                    <small>Penerima Bantuan Aktif</small>

                </div>

            </div>

        </div>

    </div>

    <div class="program-section">

        <div class="section-header">

            <h5>Program Terbaru Yayasan</h5>

        </div>

        <div class="program-grid">

            {{-- CARD 1 --}}
            @forelse($programs as $program)

                <div class="program-card">

                    <img src="{{ asset('storage/' . $program->foto) }}" alt="{{ $program->nama }}">

                    <div class="program-body">

                        <h6>{{ $program->nama }}</h6>

                        <p>

                            {{ $program->deskripsi }}

                        </p>

                    </div>

                </div>

            @empty

                <p class="text-muted">

                    Belum ada program yang tersedia.

                </p>

            @endforelse

        </div>

    </div>

@endsection