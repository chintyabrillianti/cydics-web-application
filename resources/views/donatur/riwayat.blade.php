@extends('layouts.donatur')

@section('content')
    <div class="mb-4">

        <a href="{{ route('donatur.dashboard') }}" class="back-link">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <h2 class="page-title mt-3">
            Riwayat Donasi
        </h2>

        <p class="donasi-subtitle">
            Lihat seluruh riwayat kontribusi dan status verifikasi donasi Anda.
        </p>

    </div>

    <div class="filter-box mb-4">

        <form method="GET" class="filter-box mb-4">

            <input type="text" name="cari" class="form-control search-input" placeholder="Cari nama program..."
                value="{{ request('cari') }}">

            <select name="tahun" class="form-select filter-select">

                <option value="">Semua Tahun</option>

                @foreach($tahunList as $tahun)

                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>

                @endforeach

            </select>

            <select name="status" class="form-select filter-select">

                <option value="">Semua Status</option>

                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>
                    Menunggu
                </option>

                <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>
                    Terverifikasi
                </option>

            </select>

            <button class="btn btn-cydics">

                Cari

            </button>

        </form>

    </div>

    <div class="row g-4">

        <div class="col-lg-9">
            <div class="riwayat-card">

                <table class="table riwayat-table">

                    <thead>

                        <tr>

                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($donasis as $donasi)

                            <tr>

                                <td>

                                    {{ \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d F Y') }}

                                </td>

                                <td>
                                    Rp {{ number_format($donasi->nominal ?? 0, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $donasi->program->nama }}
                                </td>

                                <td>

                                    @if($donasi->status == 'Menunggu')

                                        <span class="badge bg-warning text-dark">

                                            Menunggu

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            Terverifikasi

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-4">

                                    Belum ada riwayat donasi.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

                @if($donasis->hasPages())
                    <div class="riwayat-footer">
                        <div class="pagination-box">
                            {{ $donasis->links() }}
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <div class="col-lg-3">
            <div class="summary-card">

                <h4>
                    Ringkasan Donasi Saya
                </h4>

                <div class="summary-total">

                    <small>Total Transaksi</small>

                    <h2>{{ $totalDonasi }}</h2>

                </div>

                <div class="summary-divider"></div>

                <div class="summary-stat">

                    <span>Total Nominal</span>

                    <strong>
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </strong>

                </div>

                <div class="summary-divider"></div>

                <div class="summary-last">

                    <small>Donasi Terakhir</small>

                    <strong>

                        @if($donasiTerakhir)

                            {{ $donasiTerakhir->created_at->translatedFormat('d F Y') }}

                        @else

                            -

                        @endif

                    </strong>

                </div>

            </div>
        </div>

    </div>
@endsection