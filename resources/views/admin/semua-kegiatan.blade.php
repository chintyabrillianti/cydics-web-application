@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Semua Kegiatan
            </h2>

            <small class="text-muted">
                Total Kegiatan : {{ $kegiatans->total() }}
            </small>

        </div>

        <a href="{{ route('admin.website') }}" class="btn btn-cancel">

            Kembali

        </a>

    </div>

    <form method="GET" class="d-flex gap-3 mb-4">

        <input type="text" name="cari" class="form-control" placeholder="Cari judul kegiatan..."
            value="{{ request('cari') }}">

        <button class="btn btn-cydics">

            Cari

        </button>

    </form>

    <div class="website-card">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($kegiatans as $kegiatan)

                        <tr>

                            <td>

                                <img src="{{ asset('storage/' . $kegiatan->foto) }}" width="70" class="rounded">

                            </td>

                            <td>

                                {{ $kegiatan->judul }}

                            </td>

                            <td>

                                {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 60) }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}

                            </td>

                            <td>

                                <i class="bi bi-pencil-fill table-action" data-bs-toggle="modal"
                                    data-bs-target="#editKegiatan{{ $kegiatan->id }}">
                                </i>

                                <form action="{{ route('admin.kegiatan.hapus', $kegiatan->id) }}" method="POST"
                                    style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" style="border:none;background:none;padding:0;"
                                        onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">

                                        <i class="bi bi-trash table-action text-danger"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center">

                                Belum ada kegiatan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $kegiatans->links() }}

        </div>

    </div>

    @foreach($kegiatans as $kegiatan)

<div class="modal fade"
     id="editKegiatan{{ $kegiatan->id }}"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form
                action="{{ route('admin.kegiatan.update',$kegiatan->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Kegiatan
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    @if($kegiatan->foto)

                        <img
                            src="{{ asset('storage/'.$kegiatan->foto) }}"
                            class="img-fluid rounded d-block mx-auto mb-3"
                            style="
                                max-height:200px;
                                width:100%;
                                object-fit:cover;">

                    @endif

                    <label class="form-label">
                        Foto
                    </label>

                    <input
                        type="file"
                        name="foto"
                        class="form-control mb-3"
                        accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted d-block mb-3">
                        Kosongkan jika tidak ingin mengganti foto.
                    </small>

                    <label class="form-label">
                        Judul Kegiatan
                    </label>

                    <input
                        type="text"
                        name="judul"
                        class="form-control mb-3"
                        value="{{ $kegiatan->judul }}"
                        required>

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        class="form-control mb-3"
                        required>{{ $kegiatan->deskripsi }}</textarea>

                    <label class="form-label">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="{{ $kegiatan->tanggal }}"
                        required>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-cancel"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-cydics">

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach

@endsection