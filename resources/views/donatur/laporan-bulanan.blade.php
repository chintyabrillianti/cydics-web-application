@extends('layouts.donatur')

@section('content')

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h2 class="donasi-title">
                Semua Laporan Bulanan
            </h2>

            <p class="donasi-subtitle mb-4">
                Seluruh laporan bulanan yang telah dipublikasikan yayasan.
            </p>

        </div>

        <a href="{{ route('donatur.laporan') }}" class="btn btn-cancel">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <div class="laporan-summary-grid mb-4">

        <div class="summary-box blue">

            <div class="summary-icon">

                <i class="bi bi-file-earmark-text"></i>

            </div>

            <div>

                <small>Total Laporan</small>

                <h3>{{ $totalLaporan }}</h3>

                <span>Laporan Terbit</span>

            </div>

        </div>

        <div class="summary-box green">

            <div class="summary-icon">

                <i class="bi bi-cash-stack"></i>

            </div>

            <div>

                <small>Total Dana Masuk</small>

                <h3>

                    <span class="currency">Rp</span>

                    {{ number_format($totalDanaMasuk, 0, ',', '.') }}

                </h3>

            </div>

        </div>

        <div class="summary-box red">

            <div class="summary-icon">

                <i class="bi bi-wallet2"></i>

            </div>

            <div>

                <small>Total Dana Keluar</small>

                <h3>

                    <span class="currency">Rp</span>

                    {{ number_format($totalDanaKeluar, 0, ',', '.') }}

                </h3>

            </div>

        </div>

    </div>

    <div class="riwayat-card">

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Periode</th>
<th>Saldo Awal</th>
<th>Dana Masuk</th>
<th>Dana Keluar</th>
<th>Saldo Akhir</th>
<th>Yatim</th>
<th>Piatu</th>
<th>Yatim Piatu</th>
<th>Duafa Anak</th>
<th>Duafa Lansia</th>
<th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($laporanBulanan as $laporan)

                        <tr>

                            <td>

                                <div class="periode-box">

                                    <strong>

                                        {{ \Carbon\Carbon::parse($laporan->periode)->translatedFormat('F') }}

                                    </strong>

                                    <span>

                                        {{ \Carbon\Carbon::parse($laporan->periode)->translatedFormat('Y') }}

                                    </span>

                                </div>

                            </td>

                            </td>

<td>

    <div class="uang-masuk">

        Rp

        <strong>

            {{ number_format($laporan->saldo_awal,0,',','.') }}

        </strong>

    </div>

</td>

<td>

    <div class="uang-masuk">

        Rp

        <strong>

            {{ number_format($laporan->dana_masuk,0,',','.') }}

        </strong>

    </div>

</td>

<td>

    <div class="uang-keluar">

        Rp

        <strong>

            {{ number_format($laporan->dana_keluar,0,',','.') }}

        </strong>

    </div>

</td>

<td>

    <div class="uang-masuk">

        Rp

        <strong>

            {{ number_format($laporan->saldo_akhir,0,',','.') }}

        </strong>

    </div>

</td>

                            <td>{{ $laporan->jumlah_yatim }}</td>

                            <td>{{ $laporan->jumlah_piatu }}</td>

                            <td>{{ $laporan->jumlah_yatim_piatu }}</td>

                            <td>{{ $laporan->jumlah_duafa_anak }}</td>

                            <td>{{ $laporan->jumlah_duafa_lansia }}</td>

                            <td>

                                <a href="{{ asset('storage/' . $laporan->dokumen) }}" class="btn-download" download>

                                    <i class="bi bi-download"></i>

                                    Download

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11" class="text-center">

                                Belum ada laporan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $laporanBulanan->links() }}

        </div>

    </div>

@endsection