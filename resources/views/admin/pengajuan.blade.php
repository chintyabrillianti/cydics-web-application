@extends('layouts.admin')

@section('content')

    <!-- CONTENT -->

    <!-- Header -->

    <div class="mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Kelola Pengajuan
            </h2>

            <p class="text-muted mb-0">
                Kelola dan Tinjau permohonan bantuan dari masyarakat.
                Harap pastikan transparansi dan kejujuran dalam setiap keputusan.
            </p>

        </div>

    </div>

    <div class="row g-4 align-items-stretch">

        <div class="col-lg-7">

            <div class="content-card pengajuan-card h-100">

                <form method="GET" action="{{ route('admin.pengajuan') }}" class="row g-3 mb-4 align-items-center">

                    <div class="col-md-4">

                        <input type="text" name="nama" class="form-control pengajuan-filter"
                            placeholder="🔍 Cari nama pemohon..." value="{{ request('nama') }}">

                    </div>

                    <div class="col-md-4">

                        <select name="kategori" class="form-select">

                            <option value="">
                                Semua Kategori
                            </option>

                            <option value="Yatim" {{ request('kategori') == 'Yatim' ? 'selected' : '' }}>
                                Yatim
                            </option>

                            <option value="Piatu" {{ request('kategori') == 'Piatu' ? 'selected' : '' }}>
                                Piatu
                            </option>

                            <option value="Yatim-Piatu" {{ request('kategori') == 'Yatim-Piatu' ? 'selected' : '' }}>
                                Yatim-Piatu
                            </option>

                            <option value="Duafa Anak" {{ request('kategori') == 'Duafa Anak' ? 'selected' : '' }}>
                                Duafa Anak
                            </option>

                            <option value="Duafa Lansia" {{ request('kategori') == 'Duafa Lansia' ? 'selected' : '' }}>
                                Duafa Lansia
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <select name="status" class="form-select">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>
                                Menunggu
                            </option>

                            <option value="Perbaikan" {{ request('status') == 'Perbaikan' ? 'selected' : '' }}>
                                Perbaikan
                            </option>

                            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>
                                Diterima
                            </option>

                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>

                        </select>

                    </div>

                    <div class="col-md-12 d-flex gap-2">

                        <button class="btn btn-cydics">
                            Filter
                        </button>

                        <a href="{{ route('admin.pengajuan') }}" class="btn btn-cancel">
                            Reset
                        </a>

                    </div>

                </form>



                <div class="table-wrapper">
                    <table class="table pengajuan-table">

                        <thead>

                            <tr>

                                <th>NAMA</th>
                                <th>KATEGORI</th>
                                <th>JENIS</th>
                                <th>TANGGAL</th>
                                <th>STATUS</th>
                                <th>AKSI</th>

                            </tr>

                        </thead>

                        <tbody>

                            @if($pengajuan->count() == 0)

                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>

                            @endif


                            @foreach($pengajuan as $item)

                                                    <tr>

                                                        <td>
                                                            {{ $item->nama_lengkap }}
                                                        </td>

                                                        <td>
                                                            <span class="badge badge-yatim">
                                                                {{ $item->kategori_bantuan }}
                                                            </span>
                                                        </td>

                                                        <td>

                                                            @if(
    in_array($item->kategori_bantuan,['Duafa Anak','Duafa Lansia'])
    && in_array($item->status_pembaruan,['Menunggu','Diterima','Ditolak'])
)

    Pembaruan Dokumen

@elseif($item->kategori_sebelumnya)

    Perubahan Kategori

@else

    Pengajuan Baru

@endif
                                                        </td>

                                                        <td>

                                                            @if(
    in_array($item->kategori_bantuan,['Duafa Anak','Duafa Lansia'])
    && in_array($item->status_pembaruan,['Menunggu','Diterima','Ditolak'])
)
    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan_pembaruan)->translatedFormat('d F Y') }}

@else

    {{ $item->created_at->translatedFormat('d F Y') }}

@endif

                                                        </td>

                                                        <td>

                                                            @if($item->status_pembaruan == 'Menunggu')

                                                                <span class="text-info">
                                                                    ● Menunggu Verifikasi Pembaruan
                                                                </span>

                                                            @elseif($item->status_pembaruan == 'Ditolak')

                                                                <span class="text-danger">
                                                                    ● Pembaruan Ditolak
                                                                </span>

                                                            @elseif($item->status_pembaruan == 'Diterima')

                                                                <span class="text-success">
                                                                    ● Pembaruan Diterima
                                                                </span>

                                                            @elseif($item->status_pengajuan == 'Menunggu')

                                                                <span class="text-warning">
                                                                    ● Menunggu
                                                                </span>

                                                            @elseif($item->status_pengajuan == 'Perbaikan')

                                                                <span class="text-primary">
                                                                    ● Perbaikan
                                                                </span>

                                                            @elseif($item->status_pengajuan == 'Ditolak')

                                                                <span class="text-danger">
                                                                    ● Ditolak
                                                                </span>

                                                            @elseif($item->status_pengajuan == 'Diterima')

                                                                <span class="text-success">
                                                                    ● Diterima
                                                                </span>

                                                            @endif

                                                        <td>

                                                            <div class="d-flex gap-1">

                                                                <a href="{{ route(
                                    'admin.pengajuan.detail',
                                    [
                                        'id' => $item->id,
                                        'nama' => request('nama'),
                                        'kategori' => request('kategori'),
                                        'status' => request('status')
                                    ]
                                ) }}" class="btn btn-primary btn-sm">

                                                                    Lihat

                                                                </a>

                                                            </div>

                                                        </td>

                                                    </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>
        </div>


        @if($detail)

                <div class="col-lg-5">

                    <div class="content-card detail-panel h-100">

                        <div class="detail-header">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    @if(
    in_array($detail->kategori_bantuan, ['Duafa Anak','Duafa Lansia'])
    && $detail->status_pembaruan != 'Belum'
)

    <h4>Detail Pembaruan Dokumen</h4>

@else

    <h4>Detail Pengajuan</h4>

@endif

                                    <small>
                                        {{ $detail->nama_lengkap }}
                                    </small>
                                </div>

                                <a href="{{ route('admin.pengajuan') }}" class="close-detail text-dark text-decoration-none">
                                    ✕
                                </a>

                            </div>
                        </div>

                        <div class="detail-body">

                            <div class="section-divider"></div>

                            <div class="detail-section-title">
                                Data Diri
                            </div>

                            <div class="detail-label">
                                Nama Lengkap
                            </div>
                            <div class="detail-value">
                                {{ $detail->nama_lengkap }}
                            </div>

                            <div class="detail-label">
                                Alamat KK di Deparis Residence
                            </div>

                            <div class="detail-value">
                                {{ $detail->alamat_sesuai_kk }}
                            </div>

                            @if($detail->alamat_sesuai_kk == 'Tidak')

                                <div class="detail-label">
                                    Alamat Domisili Sekarang
                                </div>

                                <div class="detail-value">
                                    {{ $detail->alamat_sekarang }}
                                </div>

                            @endif

                            <div class="detail-label">
                                Nomor Telepon
                            </div>
                            <div class="detail-value">
                                {{ $detail->no_hp }}
                            </div>

                            <div class="detail-label">
                                Kategori
                            </div>

                            <div class="detail-value">
                                {{ $detail->kategori_bantuan }}
                            </div>
                            <div class="detail-label mt-3">
                                Jenis Pengajuan
                            </div>

                            <div class="detail-value">

                                @if($detail->status_pembaruan && $detail->status_pembaruan != 'Belum')

                                    <span class="badge bg-info">
                                        Pembaruan Dokumen
                                    </span>

                                @elseif($detail->kategori_sebelumnya)

                                    <span class="badge bg-warning text-dark">
                                        Perubahan Kategori
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Pengajuan Baru
                                    </span>

                                @endif

                            </div>
                            @if($detail->status_pengajuan == 'Perbaikan' && $detail->catatan_admin)

                                <div class="detail-label mt-3">
                                    Catatan Perbaikan
                                </div>

                                <div class="detail-value text-warning">
                                    {{ $detail->catatan_admin }}
                                </div>

                            @endif

                            @if($detail->status_pembaruan == 'Ditolak')

                                <div class="alert alert-danger mt-3">
                                    <strong>Pembaruan dokumen ditolak.</strong><br>
                                    Bantuan dinonaktifkan.
                                </div>
                                @if($detail->status_pembaruan == 'Ditolak' && $detail->catatan_admin)

                                    <div class="detail-label mt-3">
                                        Alasan Penolakan
                                    </div>

                                    <div class="detail-value text-danger">
                                        {{ $detail->catatan_admin }}
                                    </div>

                                @endif

                            @elseif($detail->status_pembaruan == 'Diterima')

                                <div class="alert alert-success mt-3">
                                    <strong>Pembaruan dokumen telah diterima.</strong><br>
                                    Masa bantuan berhasil diperpanjang 6 bulan.
                                </div>
                                <div class="detail-label mt-3">
                                    Tanggal Pembaruan
                                </div>

                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($detail->tanggal_pengajuan_pembaruan)->translatedFormat('d F Y') }}
                                </div>

                            @elseif($detail->status_pengajuan == 'Diterima' && $detail->status_pembaruan == 'Belum')

                                <div class="alert alert-success mt-3">
                                    <strong>Pengajuan telah diterima.</strong><br>
                                    Bantuan telah aktif.
                                </div>

                            @endif

                            @if(
                                    $detail->status_pengajuan == 'Ditolak' &&
                                    $detail->status_pembaruan != 'Ditolak'
                                )

                                <div class="alert alert-danger mt-3">
                                    Pengajuan telah ditolak.
                                </div>

                                @if($detail->catatan_admin)

                                    <div class="detail-label mt-3">
                                        Alasan Penolakan
                                    </div>

                                    <div class="detail-value text-danger">
                                        {{ $detail->catatan_admin }}
                                    </div>

                                @endif

                            @endif


                            @if($detail->jumlah_perbaikan > 0)

                                <div class="detail-label mt-3">
                                    Jumlah Perbaikan
                                </div>

                                <div class="detail-value">
                                    {{ $detail->jumlah_perbaikan }} kali
                                </div>

                            @endif



                            <hr>

                            <div class="detail-section-title">
                                Dokumen
                            </div>

                            @if(
    in_array($detail->status_pembaruan,['Menunggu','Diterima'])
)

    {{-- =========================
    DOKUMEN PEMBARUAN
    ========================= --}}

                                @if($detail->file_kk_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-file-earmark-text me-2"></i>
                                            Kartu Keluarga Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif

                                @if($detail->file_domisili_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-house-door me-2"></i>
                                            Surat Domisili Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif

                                @if($detail->file_sktm_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-file-earmark-medical me-2"></i>
                                            SKTM Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_sktm_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif

                                @if($detail->file_bukti_rumah_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-house-check me-2"></i>
                                            Bukti Kondisi Rumah Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_rumah_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif

                                @if($detail->file_bukti_cerai_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-file-earmark-x me-2"></i>
                                            Bukti Cerai Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_cerai_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif

                                @if($detail->file_kematian_pasangan_pembaruan)

                                    <div class="document-item">
                                        <span>
                                            <i class="bi bi-file-earmark-medical me-2"></i>
                                            Surat Kematian Pasangan Terbaru
                                        </span>

                                        <a href="javascript:void(0)"
                                            onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_pasangan_pembaruan) }}')"
                                            class="badge-verified text-decoration-none">
                                            Lihat
                                        </a>

                                    </div>

                                @endif
                            @else

@if($detail->status_pembaruan == 'Ditolak')

<div class="alert alert-warning mb-3">
    <strong>Dokumen Pengajuan Awal</strong><br>
    Dokumen pembaruan ditolak sehingga yang ditampilkan adalah dokumen pengajuan sebelumnya.
</div>

@endif
                                @php

                                    function dokumenPerbaikan(
                                        $lama,
                                        $baru,
                                        $judul,
                                        $icon
                                    ) {
                                        return [
                                            'lama' => $lama,
                                            'baru' => $baru,
                                            'judul' => $judul,
                                            'icon' => $icon
                                        ];
                                    }

                                @endphp

                                {{-- YATIM --}}
                                @if($detail->kategori_bantuan == 'Yatim')

                                    {{-- Akta Kelahiran --}}
                                    @if($detail->file_akta_kelahiran)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Akta Kelahiran
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>

                                        @if($detail->file_akta_kelahiran_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Akta Kelahiran

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- KK --}}
                                    @if($detail->file_kk)

                                        <div class="document-item">

                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Kartu Keluarga
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk) }}')"
                                                class="badge-verified text-decoration-none">

                                                Lihat

                                            </a>

                                        </div>

                                        @if($detail->file_kk_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    <i class="bi bi-file-earmark-text me-2"></i>

                                                    Kartu Keluarga

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">

                                                    Lihat

                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Surat Domisili --}}
                                    @if($detail->file_domisili)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-door me-2"></i>
                                                Surat Domisili
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_domisili_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Domisili
                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Surat Kematian Ayah --}}
                                    @if($detail->file_kematian_ayah)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Kematian Ayah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_ayah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_kematian_ayah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Kematian Ayah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_ayah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- SK Belum Menikah --}}
                                    @if($detail->file_tidak_menikah)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-shield-check me-2"></i>
                                                SK Belum Menikah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_tidak_menikah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SK Belum Menikah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif

                                @endif

                                {{-- PIATU --}}
                                @if($detail->kategori_bantuan == 'Piatu')

                                    {{-- Akta Kelahiran --}}
                                    @if($detail->file_akta_kelahiran)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Akta Kelahiran
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>

                                    @endif


                                    {{-- Kartu Keluarga --}}
                                    @if($detail->file_kk)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Kartu Keluarga
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>

                                        @if($detail->file_kk_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    <i class="bi bi-file-earmark-text me-2"></i>

                                                    Kartu Keluarga

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">

                                                    Lihat

                                                </a>

                                            </div>

                                        @endif



                                    @endif


                                    {{-- Surat Domisili --}}
                                    @if($detail->file_domisili)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-door me-2"></i>
                                                Surat Domisili
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_domisili_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Domisili
                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Surat Kematian Ibu --}}
                                    @if($detail->file_kematian_ibu)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Kematian Ibu
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_ibu) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>

                                        @if($detail->file_kematian_ibu_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Kematian Ibu

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_ibu_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- SK Belum Menikah --}}
                                    @if($detail->file_tidak_menikah)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-shield-check me-2"></i>
                                                SK Belum Menikah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_tidak_menikah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SK Belum Menikah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif

                                @endif

                                {{-- YATIM PIATU --}}
                                @if($detail->kategori_bantuan == 'Yatim-Piatu')

                                    {{-- Akta Kelahiran --}}
                                    @if($detail->file_akta_kelahiran)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Akta Kelahiran
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_akta_kelahiran_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Akta Kelahiran

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Kartu Keluarga --}}
                                    @if($detail->file_kk)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Kartu Keluarga
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_kk_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    <i class="bi bi-file-earmark-text me-2"></i>

                                                    Kartu Keluarga

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">

                                                    Lihat

                                                </a>

                                            </div>

                                        @endif


                                    @endif


                                    {{-- Surat Domisili --}}
                                    @if($detail->file_domisili)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-door me-2"></i>
                                                Surat Domisili
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_domisili_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Domisili
                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Surat Kematian Orang Tua --}}
                                    @if($detail->file_kematian_orangtua)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Kematian Orang Tua
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_orangtua) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>

                                        @if($detail->file_kematian_orangtua_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Kematian Orang Tua

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_orangtua_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- SK Belum Menikah --}}
                                    @if($detail->file_tidak_menikah)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-shield-check me-2"></i>
                                                SK Belum Menikah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_tidak_menikah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SK Belum Menikah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif

                                @endif

                                {{-- DUAFA ANAK --}}
                                @if($detail->kategori_bantuan == 'Duafa Anak')

                                    {{-- Akta Kelahiran --}}
                                    @if($detail->file_akta_kelahiran)
                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Akta Kelahiran
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_akta_kelahiran_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Akta Kelahiran

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_akta_kelahiran_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif
                                    @endif

                                    {{-- KK --}}
                                    @if($detail->file_kk)

                                        <div class="document-item">

                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Kartu Keluarga
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk) }}')"
                                                class="badge-verified text-decoration-none">

                                                Lihat

                                            </a>

                                        </div>

                                        @if($detail->file_kk_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    <i class="bi bi-file-earmark-text me-2"></i>

                                                    Kartu Keluarga

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">

                                                    Lihat

                                                </a>

                                            </div>

                                        @endif

                                    @endif

                                    {{-- Surat Domisili --}}
                                    @if($detail->file_domisili)
                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-door me-2"></i>
                                                Surat Domisili
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_domisili_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Domisili
                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif
                                    @endif


                                    {{-- SKTM --}}
                                    @if($detail->file_sktm)
                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Keterangan Tidak Mampu
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_sktm) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>

                                        @if($detail->file_sktm_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SKTM

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_sktm_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif
                                    @endif




                                    {{-- SK Belum Menikah --}}
                                    @if($detail->file_tidak_menikah)
                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-shield-check me-2"></i>
                                                SK Belum Menikah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_tidak_menikah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SK Belum Menikah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_menikah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif
                                    @endif


                                    {{-- Surat Tidak Bekerja --}}
                                    @if($detail->file_tidak_bekerja)
                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-x me-2"></i>
                                                Surat Tidak Bekerja
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_bekerja) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>
                                        </div>
                                        @if($detail->file_tidak_bekerja_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Tidak Bekerja

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_tidak_bekerja_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif
                                    @endif


                                @endif

                                {{-- DUAFA LANSIA --}}
                                @if($detail->kategori_bantuan == 'Duafa Lansia')

                                    {{-- KK --}}
                                    @if($detail->file_kk)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Kartu Keluarga
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_kk_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    <i class="bi bi-file-earmark-text me-2"></i>

                                                    Kartu Keluarga

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kk_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">

                                                    Lihat

                                                </a>

                                            </div>

                                        @endif


                                    @endif


                                    {{-- Domisili --}}
                                    @if($detail->file_domisili)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-door me-2"></i>
                                                Surat Domisili
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_domisili_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Domisili
                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_domisili_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- SKTM --}}
                                    @if($detail->file_sktm)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Keterangan Tidak Mampu
                                            </span>

                                            <a href="javascript:void(0)" onclick="lihatDokumen('{{ asset('storage/' . $detail->file_sktm) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>

                                        @if($detail->file_sktm_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    SKTM

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_sktm_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Bukti Rumah --}}
                                    @if($detail->file_bukti_rumah)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-house-check me-2"></i>
                                                Bukti Kondisi Rumah
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_rumah) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_bukti_rumah_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Bukti Rumah

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_rumah_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Kematian Pasangan --}}
                                    @if($detail->file_kematian_pasangan)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-medical me-2"></i>
                                                Surat Kematian Pasangan
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_pasangan) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_kematian_pasangan_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Surat Kematian Pasangan

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_kematian_pasangan_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif


                                    {{-- Bukti Cerai --}}
                                    @if($detail->file_bukti_cerai)

                                        <div class="document-item">
                                            <span>
                                                <i class="bi bi-file-earmark-x me-2"></i>
                                                Bukti Perceraian
                                            </span>

                                            <a href="javascript:void(0)"
                                                onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_cerai) }}')"
                                                class="badge-verified text-decoration-none">
                                                Lihat
                                            </a>

                                        </div>
                                        @if($detail->file_bukti_cerai_perbaikan)

                                            <div class="document-item document-perbaikan">

                                                <span>
                                                    Bukti Cerai

                                                    <span class="badge-perbaikan">
                                                        PERBAIKAN
                                                    </span>
                                                </span>

                                                <a href="javascript:void(0)"
                                                    onclick="lihatDokumen('{{ asset('storage/' . $detail->file_bukti_cerai_perbaikan) }}')"
                                                    class="badge-verified text-decoration-none">
                                                    Lihat
                                                </a>

                                            </div>

                                        @endif

                                    @endif

                                @endif

                            @endif
                        </div>

                        <div class="detail-footer">

                            @if($detail->status_pengajuan == 'Perbaikan')

                                <div class="alert alert-warning">

                                    <h6 class="fw-bold mb-2">
                                        ⚠ Perbaikan Dokumen
                                    </h6>

                                    <p class="mb-2">
                                        Pemohon sedang melakukan perbaikan dokumen.
                                    </p>

                                    <small>
                                        Menunggu unggahan dokumen terbaru dari warga.
                                    </small>

                                </div>

                            @endif

                            @if($detail->status_pembaruan == 'Menunggu')

                                {{-- TERIMA PEMBARUAN --}}
                                <form action="{{ route('admin.pembaruan.terima', $detail->id) }}" method="POST" class="mb-3">
                                    @csrf

                                    <button class="btn btn-success w-100">
                                        Terima Pembaruan
                                    </button>
                                </form>

                                {{-- TOLAK PEMBARUAN --}}
                                <form action="{{ route('admin.pembaruan.tolak', $detail->id) }}" method="POST">

                                    @csrf

                                    <label class="mb-2">
                                        Alasan Penolakan
                                    </label>

                                    <textarea name="alasan_penolakan" class="form-control" rows="3" required></textarea>

                                    <button class="btn btn-danger w-100 mt-2">
                                        Tolak Pembaruan
                                    </button>

                                </form>

                            @else

                                {{-- TOMBOL TERIMA --}}
                                @if($detail->status_pengajuan == 'Menunggu')

                                    <form action="{{ route('admin.pengajuan.terima', $detail->id) }}" method="POST" class="mb-3">

                                        @csrf

                                        <button type="submit" class="btn btn-success w-100">
                                            Terima
                                        </button>

                                    </form>

                                @endif



                                {{-- FORM PERBAIKAN --}}
                                @if(
                                        $detail->status_pengajuan == 'Menunggu'
                                        &&
                                        $detail->jumlah_perbaikan < 2
                                    )

                                    <form action="{{ route('admin.pengajuan.perbaikan', $detail->id) }}" method="POST" class="mb-3">

                                        @csrf

                                        <label class="mb-2">
                                            Catatan Perbaikan
                                        </label>

                                        <textarea name="catatan_admin"
                                            class="form-control catatan-box @error('catatan_admin') is-invalid @enderror"
                                            rows="3"></textarea>

                                        @error('catatan_admin')
                                            <small class="text-danger d-block mt-1">
                                                {{ $message }}
                                            </small>
                                        @enderror

                                        <button type="submit" class="btn btn-revisi w-100 mt-2">
                                            Perbaikan
                                        </button>

                                    </form>

                                @endif


                                {{-- FORM TOLAK --}}

                                @if($detail->status_pengajuan == 'Menunggu')
                                    <form action="{{ route('admin.pengajuan.tolak', $detail->id) }}" method="POST">

                                        @csrf

                                        <label class="mb-2">
                                            Alasan Penolakan
                                        </label>

                                        <textarea name="alasan_penolakan"
                                            class="form-control catatan-box @error('alasan_penolakan') is-invalid @enderror"
                                            rows="3"></textarea>

                                        @error('alasan_penolakan')
                                            <small class="text-danger d-block mt-1">
                                                {{ $message }}
                                            </small>
                                        @enderror

                                        <button type="submit" class="btn btn-tolak w-100 mt-2">
                                            Tolak
                                        </button>

                                    </form>
                                @endif
                                @if(
                                        $detail->status_pengajuan == 'Diterima' &&
                                        $detail->status_penerima == 'Aktif'
                                    )

                                    <form action="{{ route('admin.pengajuan.selesai.meninggal', $detail->id) }}" method="POST" class="mt-3"
                                        onsubmit="return confirm('Yakin penerima bantuan telah meninggal dunia? Bantuan akan diselesaikan dan dipindahkan ke arsip.');">

                                        @csrf

                                        <button class="btn btn-dark w-100">
                                            Bantuan Selesai (Meninggal)
                                        </button>

                                    </form>

                                @endif

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

    <!-- Modal Dokumen -->
    <div class="modal fade" id="dokumenModal" tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Preview Dokumen
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body text-center">

                    <iframe id="pdfViewer" width="100%" height="600" style="display:none;border:none;">
                    </iframe>

                    <img id="imageViewer" class="img-fluid" style="display:none;max-height:600px;">

                </div>

                <div class="modal-footer">

                    <a id="downloadFile" class="btn btn-cydics" target="_blank">
                        Download
                    </a>

                </div>

            </div>

        </div>

    </div>

    <script>

        function lihatDokumen(file) {
            document.getElementById('downloadFile').href = file;

            let pdf = document.getElementById('pdfViewer');
            let image = document.getElementById('imageViewer');

            pdf.style.display = 'none';
            image.style.display = 'none';

            if (file.endsWith('.pdf')) {
                pdf.src = file;
                pdf.style.display = 'block';
            }
            else {
                image.src = file;
                image.style.display = 'block';
            }

            let modal = new bootstrap.Modal(
                document.getElementById('dokumenModal')
            );

            modal.show();
        }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

@endsection