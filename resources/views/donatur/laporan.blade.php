@extends('layouts.donatur')

@section('content')

    <div class="mb-4">

        <h2 class="donasi-title">
            Laporan Transparansi
        </h2>

        <p class="donasi-subtitle">
            Lihat penggunaan dana dan laporan keuangan yayasan secara transparan.
        </p>

    </div>

    <div class="riwayat-card mb-4">

        <h5 class="fw-bold mb-4">

            Laporan Bulanan

        </h5>

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Periode</th>

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

                    @forelse($laporanBulanan as $laporan)

                        <tr>

                            <td>

                                {{ \Carbon\Carbon::parse($laporan->periode)->translatedFormat('F Y') }}

                            </td>

                            <td>

                                Rp {{ number_format($laporan->dana_masuk, 0, ',', '.') }}

                            </td>

                            <td>

                                Rp {{ number_format($laporan->dana_keluar, 0, ',', '.') }}

                            </td>

                            <td>

                                {{ $laporan->jumlah_yatim }}

                            </td>

                            <td>

                                {{ $laporan->jumlah_piatu }}

                            </td>

                            <td>

                                {{ $laporan->jumlah_yatim_piatu }}

                            </td>

                            <td>

                                {{ $laporan->jumlah_duafa_anak }}

                            </td>

                            <td>

                                {{ $laporan->jumlah_duafa_lansia }}

                            </td>

                            <td>

                                <button class="btn btn-sm btn-cydics" data-bs-toggle="modal"
                                    data-bs-target="#laporan{{ $laporan->id }}">

                                    Lihat

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada laporan bulanan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="text-end mt-3">

            <a href="{{ route('donatur.laporan.bulanan') }}" class="text-decoration-none fw-semibold">

                Lihat Semua →

            </a>

        </div>

    </div>

    @foreach($laporanBulanan as $laporan)

        <div class="modal fade" id="laporan{{ $laporan->id }}" tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Laporan Bulanan
                            {{ \Carbon\Carbon::parse($laporan->periode)->translatedFormat('F Y') }}

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <table class="table">

                            <tr>

                                <th width="40%">
                                    Periode
                                </th>

                                <td>

                                    {{ \Carbon\Carbon::parse($laporan->periode)->translatedFormat('F Y') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Dana Masuk
                                </th>

                                <td>

                                    Rp {{ number_format($laporan->dana_masuk, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Dana Keluar
                                </th>

                                <td>

                                    Rp {{ number_format($laporan->dana_keluar, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Yatim Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_yatim }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Piatu Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_piatu }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Yatim Piatu Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_yatim_piatu }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Duafa Anak Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_duafa_anak }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Duafa Lansia Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_duafa_lansia }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Keterangan
                                </th>

                                <td>

                                    {{ $laporan->keterangan ?? '-' }}

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="modal-footer">

                        <a href="{{ asset('storage/' . $laporan->dokumen) }}" class="btn btn-cydics" download>

                            <i class="bi bi-download"></i>

                            Download Dokumen

                        </a>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

    <div class="riwayat-card mb-4">

        <h5 class="fw-bold mb-4">
            Laporan Tahunan
        </h5>

        <div class="table-responsive">

            <table class="table table-hover">

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

                            <td>{{ $laporan->periode }}</td>

                            <td>
                                Rp {{ number_format($laporan->dana_masuk, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($laporan->dana_keluar, 0, ',', '.') }}
                            </td>

                            <td>{{ $laporan->jumlah_yatim }}</td>

                            <td>{{ $laporan->jumlah_piatu }}</td>

                            <td>{{ $laporan->jumlah_yatim_piatu }}</td>

                            <td>{{ $laporan->jumlah_duafa_anak }}</td>

                            <td>{{ $laporan->jumlah_duafa_lansia }}</td>

                            <td>

                                <button class="btn btn-sm btn-cydics" data-bs-toggle="modal"
                                    data-bs-target="#tahun{{ $laporan->id }}">

                                    Lihat

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada laporan tahunan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="text-end mt-3">

            <a href="{{ route('donatur.laporan.tahunan') }}" class="text-decoration-none fw-semibold">

                Lihat Semua →

            </a>

        </div>

    </div>

    @foreach($laporanTahunan as $laporan)

        <div class="modal fade" id="tahun{{ $laporan->id }}" tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Laporan Tahunan
                            {{ $laporan->periode }}

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <table class="table">

                            <tr>

                                <th width="40%">
                                    Tahun
                                </th>

                                <td>

                                    {{ $laporan->periode }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Dana Masuk
                                </th>

                                <td>

                                    Rp {{ number_format($laporan->dana_masuk, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Dana Keluar
                                </th>

                                <td>

                                    Rp {{ number_format($laporan->dana_keluar, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Yatim Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_yatim }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Piatu Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_piatu }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Yatim Piatu Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_yatim_piatu }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Duafa Anak Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_duafa_anak }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Duafa Lansia Aktif
                                </th>

                                <td>

                                    {{ $laporan->jumlah_duafa_lansia }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Keterangan
                                </th>

                                <td>

                                    {{ $laporan->keterangan ?? '-' }}

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="modal-footer">

                        <a href="{{ asset('storage/' . $laporan->dokumen) }}" class="btn btn-cydics" download>

                            <i class="bi bi-download"></i>

                            Download Dokumen

                        </a>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

@endsection