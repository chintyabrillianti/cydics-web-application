@extends('layouts.warga')

@section('content')

    <h2 class="fw-bold">
        Pembaruan Dokumen
    </h2>

    <p class="text-muted">
        Dokumen wajib diperbarui setiap 6 bulan.
    </p>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h5 class="mb-3">
                Pengajuan Aktif
            </h5>

            <p>
                Bantuan
                <strong>
                    {{ $pengajuan->kategori_bantuan }}
                </strong>
            </p>

            @if($wajibUpdate && !$lewatDeadline)

<p class="text-danger mb-3">
    Batas akhir pembaruan dokumen:
    <strong>
        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
    </strong>
</p>

@endif

            <hr>
            @if(!$wajibUpdate)

    <div class="alert alert-info">
        Jadwal pembaruan dokumen belum dimulai.
    </div>

@elseif($pengajuan->status_pembaruan == 'Menunggu')

    <div class="alert alert-warning">
        Dokumen pembaruan telah dikirim dan sedang menunggu verifikasi admin.
    </div>

@elseif($lewatDeadline)

    <div class="alert alert-danger">
        Masa pembaruan dokumen telah berakhir.
    </div>
   
@elseif($pengajuan->status_pembaruan == 'Ditolak')

<div class="alert alert-danger">
    Pembaruan dokumen ditolak oleh admin.
</div>

@if($pengajuan->catatan_admin)
<div class="alert alert-light border">
    <strong>Catatan Admin :</strong><br>
    {{ $pengajuan->catatan_admin }}
</div>

@elseif($pengajuan->status_pembaruan == 'Diterima')

<div class="alert alert-success">
    Dokumen pembaruan telah disetujui. Jadwal pembaruan berikutnya akan tersedia pada periode selanjutnya.
</div>
@endif

@else

            <form action="{{ route('pembaruan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($pengajuan->kategori_bantuan == 'Duafa Anak')
                    <div class="mb-3">
    <label class="form-label">
        Kartu Keluarga Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_kk"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

<div class="mb-3">
    <label class="form-label">
        SKTM Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_sktm"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

@if($pengajuan->file_domisili)

<div class="mb-3">
    <label class="form-label">
        Surat Domisili Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_domisili"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

@endif
                @elseif($pengajuan->kategori_bantuan == 'Duafa Lansia')

                    <div class="mb-3">
    <label class="form-label">
        Kartu Keluarga Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_kk"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

<div class="mb-3">
    <label class="form-label">
        SKTM Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_sktm"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

@if($pengajuan->file_domisili)

<div class="mb-3">
    <label class="form-label">
        Surat Domisili Terbaru <span class="text-danger">*</span>
    </label>

    <input type="file"
        name="file_domisili"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf"
        required>

    <small class="text-muted">
        JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>

@endif

<div class="mb-3">
    <label class="form-label">
        Bukti Kondisi Rumah Terbaru
    </label>

    <input type="file"
        name="file_bukti_rumah"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf">

    <small class="text-muted">
        Opsional. JPG, JPEG, PNG atau PDF (maks. 5 MB)
    </small>
</div>
                    

                @endif

                <button type="submit" class="btn btn-warning">
                    Kirim Pembaruan Dokumen
                </button>
            </form>
            @endif

        </div>

    </div>

@endsection