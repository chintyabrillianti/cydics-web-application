@extends('layouts.admin')
@php
    use Illuminate\Support\Str;
@endphp

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Manajemen Website
            </h2>

            <p class="text-muted mb-0">
                Kelola konten, program, dan informasi kontak untuk publik CYDICS.
            </p>
        </div>

    </div>

    <div class="row g-3">

        <!-- KIRI -->
        <div class="col-lg-9">

            <!-- BANNER -->

            <form action="{{ route('admin.website.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="website-card mb-3">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Banner Utama (Hero)</h6>
                    </div>

                    <label class="form-label small">
                        Headline Utama
                    </label>

                    <input type="text" name="hero_title" class="form-control"
                        value="{{ old('hero_title', $website->hero_title ?? '') }}"><label class="form-label small">
                        Sub-Headline
                    </label>

                    <textarea name="hero_subtitle" class="form-control mb-3"
                        rows="3">{{ $website->hero_subtitle ?? '' }}</textarea>
                    <div class="row">

                        <div class="col-md-6">

                            <label class="form-label small">
                                Gambar Banner
                            </label>

                            <div class="d-flex gap-2">

                                @if($website && $website->banner)

                                    <img src="{{ asset('storage/' . $website->banner) }}" class="banner-preview">

                                @endif

                                <input type="file" name="banner" class="form-control">

                            </div>

                        </div>

                    </div>

                    <div class="text-end mt-3">
    <button class="btn btn-cydics">
        Simpan Perubahan
    </button>
</div>

</form>

                </div>

                <!-- PROGRAM AKTIF -->
                <div class="website-card">

                    <div class="program-header">

                        <div>

                            <div class="section-title mb-1">
                                Program Aktif
                            </div>

                            <small class="text-muted">
                                Kelola daftar program donasi yang sedang berjalan.
                            </small>

                        </div>

                        <button type="button" class="btn btn-cydics" data-bs-toggle="modal"
                            data-bs-target="#tambahProgram">
                            + Tambah Program
                        </button>

                    </div>

                    <div class="table-responsive">

                        <table class="table program-table">

                            <thead>

                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Program</th>
                                    <th>Deskripsi Singkat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($programs as $program)

                                    <tr>

                                        <td>
                                            <img src="{{ asset('storage/' . $program->foto) }}" class="banner-preview">
                                        </td>

                                        <td>
                                            {{ $program->nama }}
                                        </td>

                                        <td>
                                            {{ Str::limit($program->deskripsi, 40) }}
                                        </td>

                                        <td>

                                            <form id="status{{ $program->id }}"
                                                action="{{ route('admin.program.status', $program->id) }}" method="POST">

                                                @csrf

                                                <label class="switch">

                                                    <input type="checkbox" onchange="this.form.submit()" {{ $program->status ? 'checked' : '' }}>

                                                    <span class="slider"></span>

                                                </label>

                                            </form>

                                        </td>

                                        

                                        <td>

                                            <i class="bi bi-pencil-fill table-action" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $program->id }}">
                                            </i>

                                            <form action="{{ route('admin.program.hapus', $program->id) }}"
      method="POST"
      style="display:inline;">

    @csrf
    @method('DELETE')

    <button
        style="border:none;background:none;padding:0;"
        onclick="return confirm('Yakin ingin menghapus program ini?')">

        <i class="bi bi-trash table-action text-danger"></i>

    </button>

</form>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>
                </div>

                <!-- KEGIATAN -->
                <div class="website-card">

                    <div class="kegiatan-header">

                        <div>

                            <div class="section-title mb-1">
                                Manajemen Kegiatan
                            </div>

                            <small class="text-muted">
                                Kelola galeri kegiatan dan aktivitas terbaru yayasan.
                            </small>

                        </div>

                        <button
    class="btn btn-cydics"
    data-bs-toggle="modal"
    data-bs-target="#tambahKegiatan">

    + Tambah Kegiatan Baru

</button>

                    </div>

                    <div class="table-responsive">

                        <table class="table kegiatan-table">

                            <thead>

                                <tr>
    <th>Foto</th>
    <th>Judul Kegiatan</th>
    <th>Keterangan</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

                            </thead>

                            <tbody>

    @foreach($kegiatans as $kegiatan)

    <tr>

        <td>
            <img src="{{ asset('storage/'.$kegiatan->foto) }}"
                 class="banner-preview">
        </td>

        <td>
    {{ $kegiatan->judul }}
</td>

<td>
    {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 45) }}
</td>

<td>
    {{ \Carbon\Carbon::parse($kegiatan->tanggal)
        ->translatedFormat('d F Y') }}
</td>

        <td>

    <i class="bi bi-pencil-fill table-action"
        data-bs-toggle="modal"
        data-bs-target="#editKegiatan{{ $kegiatan->id }}">
    </i>

    <form
    action="{{ route('admin.kegiatan.hapus', $kegiatan->id) }}"
    method="POST"
    style="display:inline;">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        style="border:none;background:none;padding:0;"
        onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">

        <i class="bi bi-trash table-action text-danger"></i>

    </button>

</form>

</td>

    </tr>

    @endforeach

</tbody>

                        </table>

                        </div>
                        <div class="d-flex justify-content-end mt-3">

    <a href="{{ route('admin.semua.kegiatan') }}"
       class="text-decoration-none fw-semibold">

        Lihat Semua Kegiatan →

    </a>

</div>

                    

                </div>

        </div>

        <!-- KANAN -->
        <div class="col-lg-3">

            <!-- VISI MISI -->
            <form action="{{ route('admin.website.update') }}" method="POST">
    @csrf
            <div class="website-card mb-3">

                <h6 class="fw-bold">
                    Visi & Misi
                </h6>

                <label class="small mb-2">
                    Visi
                </label>

                <textarea name="visi" class="form-control" rows="4">{{ old('visi', $website->visi ?? '') }}</textarea>
                <label class="small mb-2">
                    Misi
                </label>

                <textarea name="misi" class="form-control" rows="4">{{ old('misi', $website->misi ?? '') }}</textarea>
                <div class="text-end">
                    <button class="btn btn-cydics">
                        Simpan
                    </button>
                </div>

            </div>
            </form>

            <!-- KONTAK -->
            <form action="{{ route('admin.website.update') }}" method="POST">
            @csrf

            <div class="website-card mb-3 contact-card">

                <div class="section-title">
                    Kontak & Lokasi
                </div>

                <label class="small mb-1">
                    Nomor WhatsApp Admin
                </label>

                <small class="text-muted d-block mb-2">
                    Contoh: 6281234567890
                </small>

                <input
    type="text"
    name="telepon"
    class="form-control mb-2"
    value="{{ old('telepon', $website->telepon ?? '') }}"
    placeholder="6281234567890">
                <label class="small mb-1">
                    Email
                </label>

                <input
    type="email"
    name="email"
    class="form-control mb-2"
    value="{{ old('email', $website->email ?? '') }}"
    placeholder="contoh@yayasan.or.id">
                <label class="small mb-1">
                    Alamat
                </label>

                <textarea
    name="alamat"
    class="form-control"
    rows="4"
    placeholder="Contoh: Jl. Raya Pamulang No.10, Tangerang Selatan">{{ old('alamat', $website->alamat ?? '') }}</textarea>
                <div class="text-end mt-3">

                    <button class="btn btn-cydics">
                        Simpan Kontak
                    </button>

                </div>

            </div>
            </form>


            <!-- SOSMED -->
            <form action="{{ route('admin.website.update') }}" method="POST">
            @csrf
            <div class="website-card">

                <div class="section-title">
                    Tautan Sosial Media
                </div>

                <label class="small mb-1">
                    Instagram
                </label>

                <input
    type="text"
    name="instagram"
    class="form-control social-input"
    value="{{ old('instagram', $website->instagram ?? '') }}"
    placeholder="https://instagram.com/cydics">

                <label class="small mb-1">
                    Youtube
                </label>

                <input
    type="text"
    name="youtube"
    class="form-control social-input"
    value="{{ old('youtube', $website->youtube ?? '') }}"
    placeholder="https://youtube.com/@cydics">
                <div class="text-end mt-3">

                    <button class="btn btn-cydics">
                        Simpan Tautan
                    </button>

                </div>

            </div>
            </form>

        </div>

    </div>

    <div class="modal fade" id="tambahProgram" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('admin.program.store') }}"
      method="POST"
      enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">
                        <h5>Tambah Program</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <label>Nama Program</label>

                        <input type="text" name="nama" class="form-control mb-3" required>

                        <label>Deskripsi</label>

                        <textarea name="deskripsi" rows="4" class="form-control" required></textarea>

                        <label class="mt-3">Foto Program</label>

<input type="file"
       name="foto"
       class="form-control"
       required>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-cydics">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    @foreach($programs as $program)

        <div class="modal fade" id="edit{{ $program->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.program.update', $program->id) }}"
      method="POST"
      enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5>Edit Program</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <label>Nama Program</label>

                            <input type="text" name="nama" class="form-control mb-3" value="{{ $program->nama }}">

                            <label>Deskripsi</label>

                            <textarea name="deskripsi" class="form-control" rows="4">{{ $program->deskripsi }}</textarea>

                            <label class="mt-3">Foto Program</label>

<input
    type="file"
    name="foto"
    class="form-control"
    accept=".jpg,.jpeg,.png,.webp">

@if($program->foto)

<img src="{{ asset('storage/'.$program->foto) }}"
     width="120"
     class="rounded">

@endif

                        </div>

                        <div class="modal-footer">

                            <button class="btn btn-cydics">
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

    <!-- =========================
     MODAL TAMBAH KEGIATAN
========================= -->

<div class="modal fade"
     id="tambahKegiatan"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('admin.kegiatan.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Kegiatan
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

    <label class="form-label">
        Foto
    </label>

    <input
        type="file"
        name="foto"
        class="form-control"
        accept=".jpg,.jpeg,.png,.webp"
        required>

    <small class="text-muted d-block mb-3">
        Format: JPG, JPEG, PNG, WEBP • Maks. 2 MB
    </small>


    <label class="form-label">
        Judul Kegiatan
    </label>

    <input
        type="text"
        name="judul"
        class="form-control mb-3"
        maxlength="100"
        placeholder="Masukkan judul kegiatan"
        required>


    <label class="form-label">
        Keterangan
    </label>

    <textarea
        name="deskripsi"
        rows="4"
        class="form-control mb-3"
        placeholder="Masukkan keterangan kegiatan"
        required></textarea>


    <label class="form-label">
        Tanggal Kegiatan
    </label>

    <input
        type="date"
        name="tanggal"
        class="form-control"
        max="{{ date('Y-m-d') }}"
        required>

    <small class="text-muted">
        Pilih tanggal kegiatan berlangsung.
    </small>

</div>

                <div class="modal-footer">

    <button
        type="button"
        class="btn btn-cancel"
        data-bs-dismiss="modal">

        Batal

    </button>

    <button
        type="submit"
        class="btn btn-cydics">

        Simpan

    </button>

</div>
            </form>

        </div>

    </div>

</div>

@foreach($kegiatans as $kegiatan)

<div class="modal fade"
     id="editKegiatan{{ $kegiatan->id }}"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="{{ route('admin.kegiatan.update',$kegiatan->id) }}"
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

                    <label class="form-label">
    Foto
</label>
                    @if($kegiatan->foto)

                        <img
    src="{{ asset('storage/'.$kegiatan->foto) }}"
    class="img-fluid rounded d-block mx-auto mb-3"
    style="
        max-height:180px;
        width:100%;
        object-fit:cover;
        border:1px solid #dee2e6;
        border-radius:12px;">

                    @endif

                    <input
                        type="file"
                        name="foto"
                        class="form-control mb-3"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                    <small class="text-muted d-block mb-3">

    Kosongkan jika tidak ingin mengganti foto.
    Format: JPG, JPEG, PNG, WEBP • Maks. 2 MB

</small>

                    <label class="form-label">
    Judul Kegiatan
</label>

                    <input
                        type="text"
                        name="judul"
                        class="form-control mb-3"
                        value="{{ $kegiatan->judul }}"
                        maxlength="100"
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
    Tanggal Kegiatan
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
                        type="submit"
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