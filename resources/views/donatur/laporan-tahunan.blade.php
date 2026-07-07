@extends('layouts.donatur')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="donasi-title">
                Semua Laporan Tahunan
            </h2>

            <p class="donasi-subtitle">
                Seluruh laporan tahunan yang telah dipublikasikan yayasan.
            </p>

        </div>

        <a href="{{ route('donatur.laporan') }}" class="btn btn-cancel">

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
                <h3>{{ $laporanTahunan->total() }}</h3>
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

                    {{ number_format($laporanTahunan->sum('dana_masuk'), 0, ',', '.') }}

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

                    {{ number_format($laporanTahunan->sum('dana_keluar'), 0, ',', '.') }}

                </h3>

            </div>

        </div>

    </div>

    <div class="riwayat-card laporan-table-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>Tahun</th>
                        <th>Dana Masuk</th>
                        <th>Dana Keluar</th>
                        <th>Yatim</th>
                        <th>Piatu</th>
                        <th>Yatim Piatu</th>
                        <th>Duafa Anak</th>
                        <th>Duafa Lansia</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($laporanTahunan as $laporan)

                        <tr>

                            <td>

                                <div class="periode-box">

                                    <strong>

                                        {{ $laporan->periode }}

                                    </strong>

                                    <span>Tahun</span>

                                </div>

                            </td>

                            <td>

                                <div class="uang-masuk">

                                    Rp

                                    <strong>

                                        {{ number_format($laporan->dana_masuk, 0, ',', '.') }}

                                    </strong>

                                </div>

                            </td>

                            <td>

                                <div class="uang-keluar">

                                    Rp

                                    <strong>

                                        {{ number_format($laporan->dana_keluar, 0, ',', '.') }}

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

                            <td colspan="9" class="text-center">

                                Belum ada laporan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $laporanTahunan->links() }}

        </div>

    </div>

@endsection