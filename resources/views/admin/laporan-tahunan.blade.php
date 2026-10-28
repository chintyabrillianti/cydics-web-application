@extends('layouts.admin')

@section('content')

    <div class="laporan-page">

        <div class="mb-4">

            <h2 class="fw-bold mb-1">
                Kelola Laporan Tahunan
            </h2>

            <p class="text-muted">
                Kelola laporan transparansi tahunan yayasan.
            </p>

        </div>

        <div class="laporan-card">

            <div class="laporan-toolbar">

                <form action="{{ route('admin.laporan.tahunan') }}" method="GET" class="search-box">

                    <i class="bi bi-search"></i>

                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control border-0"
                        placeholder="Cari tahun...">

                </form>

                <button class="btn-laporan" data-bs-toggle="modal" data-bs-target="#modalTambahTahunan">

                    <i class="bi bi-plus-circle"></i>

                    Tambah Laporan Tahunan

                </button>

            </div>

            <div class="table-responsive">

                <table class="table laporan-table mb-0">

                    <thead>

                        <tr>

                            <th>PERIODE</th>
                            <th>SALDO AWAL</th>
                            <th>DANA MASUK</th>
                            <th>DANA KELUAR</th>
                            <th>SALDO AKHIR</th>
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

                                <td>{{ $laporan->periode }}</td>

                                <td>
                                    Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($laporan->dana_masuk, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($laporan->dana_keluar, 0, ',', '.') }}
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                                    </strong>
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

        <form action="{{ route('admin.laporan.tahunan.publish', $laporan->id) }}"
            method="POST"
            class="d-inline">
            @csrf

            <button type="submit" class="btn-publish">
                <i class="bi bi-megaphone-fill"></i>
                Publish
            </button>
        </form>

        <button type="button"
            class="btn p-0 border-0 bg-transparent"
            data-bs-toggle="modal"
            data-bs-target="#editTahunan{{ $laporan->id }}">
            <i class="bi bi-pencil-square action-icon"></i>
        </button>

        <form action="{{ route('admin.laporan.tahunan.hapus', $laporan->id) }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Yakin ingin menghapus laporan tahunan ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="btn p-0 border-0 bg-transparent">
                <i class="bi bi-trash action-icon"></i>
            </button>
        </form>

    @endif

    {{-- Download selalu boleh --}}
    <a href="{{ asset('storage/' . $laporan->dokumen) }}"
        target="_blank"
        class="btn p-0 border-0 bg-transparent"
        title="Download">
        <i class="bi bi-download action-icon"></i>
    </a>

</div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="12" class="text-center">

                                    Belum ada laporan tahunan.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Modal Tambah Laporan Tahunan -->
    <div class="modal fade" id="modalTambahTahunan" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('admin.laporan.tahunan.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Tambah Laporan Tahunan

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Tahun

                            </label>

                            <input type="number" name="periode" class="form-control" min="2020" max="{{ date('Y') }}"
                                value="{{ date('Y') }}" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Dokumen

                            </label>

                            <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>

                            <small class="text-muted">

                                PDF, JPG, JPEG, PNG (maks. 5 MB)

                            </small>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Keterangan

                            </label>

                            <textarea name="keterangan" class="form-control" rows="3"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer d-flex justify-content-end gap-2">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

            </div>

            </form>

        </div>

    </div>

    </div>

    @foreach($laporans as $laporan)

        <div class="modal fade" id="editTahunan{{ $laporan->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.laporan.tahunan.update', $laporan->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Edit Laporan Tahunan

                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Tahun</label>

                                <input type="number" name="periode" class="form-control" value="{{ $laporan->periode }}"
                                    min="2020" max="{{ date('Y') }}" required>

                            </div>

                            <div class="mb-3">

                                <label>Dokumen Baru</label>

                                <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti dokumen.

                                </small>

                            </div>

                            <div class="mb-3">

                                <label>Keterangan</label>

                                <textarea name="keterangan" class="form-control" rows="3">{{ $laporan->keterangan }}</textarea>

                            </div>

                        </div>

                        <div class="modal-footer d-flex justify-content-end gap-2">

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

    @endforeach

@endsection