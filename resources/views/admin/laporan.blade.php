@extends('layouts.admin')

@section('content')

    <div class="laporan-page">

        <div class="mb-4">

            <h2 class="fw-bold mb-1">
                Kelola Laporan Bulanan
            </h2>

            <p class="text-muted mb-0">
                Manage and publish transparency reports for foundation donors.
            </p>

        </div>

        <div class="laporan-card">

            <div class="laporan-toolbar">

                <form action="{{ route('admin.laporan') }}" method="GET" class="search-box" id="searchForm">

                    <i class="bi bi-search"></i>

                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control border-0"
                        placeholder="Cari periode..." onkeyup="if(event.key==='Enter'){this.form.submit();}">

                </form>

                <button class="btn-laporan" data-bs-toggle="modal" data-bs-target="#modalTambahLaporan">

                    <i class="bi bi-plus-circle"></i>

                    Tambah Laporan Baru

                </button>

            </div>

            <div class="table-responsive">

                <table class="table laporan-table mb-0">

                    <thead>

                        <tr>

                            <th>PERIODE</th>
                            <th>DANA MASUK</th>
                            <th>DANA KELUAR</th>
                            <th>YATIM</th>
                            <th>PIATU</th>
                            <th>YATIM-PIATU</th>
                            <th>DUAFA ANAK</th>
                            <th>DUAFA LANSIA</th>
                            <th>STATUS</th>
                            <th>AKSI</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($laporans as $laporan)

                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($laporan->periode . '-01')->translatedFormat('F Y') }}
                                </td>

                                <td>
                                    Rp {{ number_format($laporan->dana_masuk,0,',','.') }}
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

                                    @if($laporan->status == 'Published')

                                        <span class="status-published">
                                            ● Published
                                        </span>

                                    @else

                                        <span class="status-draft">
                                            ● Draft
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="action-group">

                                        @if($laporan->status == 'Draft')

                                            <form action="{{ route('admin.laporan.publish', $laporan->id) }}" method="POST"
                                                class="d-inline">

                                                @csrf

                                                <button type="submit" class="btn-publish">

                                                    <i class="bi bi-megaphone-fill"></i>

                                                    Publish

                                                </button>

                                            </form>

                                        @endif

                                        <a href="{{ asset('storage/' . $laporan->dokumen) }}" target="_blank"
                                            class="text-decoration-none">

                                            <i class="bi bi-download action-icon"></i>

                                        </a>

                                        <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal"
                                            data-bs-target="#editLaporan{{ $laporan->id }}">

                                            <i class="bi bi-pencil-square action-icon"></i>

                                        </button>

                                        <form action="{{ route('admin.laporan.hapus', $laporan->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" style="border:none;background:none;padding:0;">

                                                <i class="bi bi-trash action-icon"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editLaporan{{ $laporan->id }}" tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form action="{{ route('admin.laporan.update', $laporan->id) }}" method="POST"
                                            enctype="multipart/form-data">

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Edit Laporan
                                                </h5>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Periode
                                                    </label>

                                                    <input type="month" name="periode" class="form-control"
                                                        value="{{ $laporan->periode }}" required>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Dana Keluar
                                                    </label>

                                                    <input type="number" name="dana_keluar" class="form-control"
                                                        value="{{ $laporan->dana_keluar }}" min="0" required>

                                                </div>

                                                <div class="mb-3">

                                                    @if($laporan->dokumen)

                                                        <div class="mb-3">

                                                            <a href="{{ asset('storage/' . $laporan->dokumen) }}" target="_blank"
                                                                class="btn btn-outline-primary btn-sm">

                                                                <i class="bi bi-file-earmark-arrow-down"></i>

                                                                Lihat Dokumen Saat Ini

                                                            </a>

                                                        </div>

                                                    @endif

                                                    <label class="form-label">
                                                        Ganti Dokumen
                                                    </label>

                                                    <input type="file" name="dokumen" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">

                                                    <small class="text-muted">
                                                        Kosongkan jika tidak ingin mengganti dokumen.
                                                    </small>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Keterangan
                                                    </label>

                                                    <textarea name="keterangan" rows="3"
                                                        class="form-control">{{ $laporan->keterangan }}</textarea>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                    Batal

                                                </button>

                                                <button type="submit" class="btn btn-primary">

                                                    Simpan

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-4">

                                    Belum ada laporan.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    <div class="modal fade" id="modalTambahLaporan" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('admin.laporan.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Tambah Laporan Bulanan
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Periode
                            </label>

                            <input type="month" name="periode" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Dana Keluar
                            </label>

                            <input type="number" name="dana_keluar" class="form-control" min="0" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Dokumen
                            </label>

                            <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>

                            <small class="text-muted">
                                Format: PDF, JPG, JPEG, PNG (Maks. 5 MB)
                            </small>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <textarea name="keterangan" rows="3" class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection