@extends('layouts.admin')

@section('content')

    <div class="archive-page">

        <div class="d-flex justify-content-between align-items-start mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Arsip Pengajuan
                </h2>

                <p class="text-muted small mb-0">
                    Riwayat lengkap seluruh pengajuan bantuan yang telah diproses.
                    Data historis ini digunakan untuk transparansi dan audit operasional CYDICS.
                </p>
            </div>


        </div>

    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <small>Total Arsip</small>

                <h1>{{ $totalArsip }}</h1>

                <span>Keseluruhan data riwayat</span>

                <i class="bi bi-file-earmark-text"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card approved">
                <small>Disetujui</small>

                <h1>{{ $totalDisetujui }}</h1>

                <span>{{ $persenDisetujui }}% dari total arsip</span>

                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card rejected">
                <small>Ditolak</small>

                <h1>{{ $totalDitolak }}</h1>

                <span>{{ $persenDitolak }}% dari total arsip</span>

                <i class="bi bi-x-circle"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card expired">
                <small>Selesai</small>

                <h1>{{ $totalSelesai }}</h1>

                <span>{{ $persenSelesai }}% dari total arsip</span>

                <i class="bi bi-clock-history"></i>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="card border-0">

        <div class="card-body">
            <form method="GET" action="{{ route('admin.arsip') }}">

            <div class="d-flex justify-content-between mb-4">

                <div style="width:420px">
                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
type="text"
name="cari"
value="{{ request('cari') }}"
class="form-control"
placeholder="Cari Nama atau ID Pengajuan...">

                    </div>
                </div>

                <div class="d-flex gap-2">

                    <select
    name="kategori"
    class="form-select">

    <option value="">Semua Kategori</option>

    <option value="Yatim"
{{ request('kategori')=='Yatim' ? 'selected' : '' }}>
Yatim
</option>
    <option value="Piatu"
{{ request('kategori')=='Piatu' ? 'selected' : '' }}>
Piatu
</option>
    <option value="Yatim-Piatu"
{{ request('kategori')=='Yatim-Piatu' ? 'selected' : '' }}>
Yatim-Piatu
</option>
    <option value="Duafa Anak"
{{ request('kategori')=='Duafa Anak' ? 'selected' : '' }}>
Duafa Anak
</option>
    <option value="Duafa Lansia"
{{ request('kategori')=='Duafa Lansia' ? 'selected' : '' }}>
Duafa Lansia
</option>

</select>

                    <select
    name="status"
    class="form-select">

    <option value="">
    Semua Status
</option>

<option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>
    Diterima
</option>

<option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>
    Ditolak
</option>

<option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
    Selesai
</option>

</select>
<button class="btn btn-cydics" type="submit">
    Filter
</button>

<a href="{{ route('admin.arsip') }}"
   class="btn btn-outline-secondary">
    Reset
</a>

                </div>

            </div>


</form>

<div class="table-responsive">

                <table class="table archive-table align-middle">

                    <thead>
                        <tr>
                            <th>Pemohon</th>
<th>Kategori</th>
<th>Tanggal Mulai</th>
<th>Tanggal Selesai</th>
<th>Status</th>
<th>Alasan/Keterangan</th>
<th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

@forelse($arsip as $item)

<tr>

    <td>

    <div class="d-flex align-items-center gap-3">

        <img
src="{{ $item->user && $item->user->foto
    ? asset('storage/'.$item->user->foto)
    : 'https://ui-avatars.com/api/?name='.urlencode($item->nama_lengkap).'&background=e5e7eb&color=374151' }}"
class="table-avatar"
alt="Foto Pemohon"
@if($item->user && $item->user->foto)
onerror="this.onerror=null;this.src='{{ asset('images/default-profile.png') }}';"
@endif
>


        <div>

            <strong>{{ $item->nama_lengkap }}</strong>

            <div class="small text-muted">

                ID: {{ $item->id_pengajuan }}

            </div>

        </div>

    </div>

</td>

    <td>

        {{ $item->kategori_bantuan }}

    </td>

    <td>

    @if($item->tanggal_mulai)

        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}

    @else

        -

    @endif

</td>

<td>

    @if($item->tanggal_selesai)

        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}

    @else

        -

    @endif

</td>

    <td>

@if($item->status_pembaruan == 'Ditolak')

    <span class="badge-status danger">
        ● Pembaruan Ditolak
    </span>

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'
    &&
    str_contains(
        strtolower($item->catatan_admin ?? ''),
        'meninggal'
    )

)

    <span class="badge-status warning">
        ● Selesai (Meninggal)
    </span>

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'

)

    <span class="badge-status warning">
        ● Selesai (Usia)
    </span>

@elseif($item->status_pengajuan == 'Ditolak')

    <span class="badge-status danger">
        ● Ditolak
    </span>

@else

    <span class="badge-status success">
        ● Disetujui
    </span>

@endif

</td>

    <td>

@if($item->status_pembaruan == 'Ditolak')

    {{ $item->catatan_admin }}

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'
    &&
    str_contains(
        strtolower($item->catatan_admin ?? ''),
        'meninggal'
    )

)

    Bantuan dihentikan karena penerima meninggal dunia.

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'

)

    Bantuan berakhir karena batas usia penerima telah terpenuhi.

@elseif($item->status_pengajuan == 'Ditolak')

    {{ $item->catatan_admin }}

@else

    Bantuan telah disetujui dan disalurkan.

@endif

</td>

    <td>

        <i
    class="bi bi-eye action-view"
    data-bs-toggle="modal"
    data-bs-target="#detailModal{{ $item->id }}">
</i>

    </td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center">
    Belum ada arsip.
</td>

</tr>

@endforelse

</tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">

    <small class="text-muted">
        Menampilkan {{ $arsip->firstItem() ?? 0 }}
        -
        {{ $arsip->lastItem() ?? 0 }}
        dari
        {{ $arsip->total() }}
        data arsip
    </small>

    {{ $arsip->links('pagination::bootstrap-5') }}

</div>

        </div>

    </div>

    </div>
    
    @foreach($arsip as $item)
    <div style="display:none">
Modal {{ $item->id }}
</div>

<div class="modal fade"
    id="detailModal{{ $item->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Detail Arsip Pengajuan

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

<div class="row">

    <div class="col-md-6">

        <h6 class="fw-bold text-primary mb-3">

            Data Pemohon

        </h6>

        <table class="table table-borderless">

            <tr>

                <th width="40%">
                    ID Pengajuan
                </th>

                <td>
                    {{ $item->id_pengajuan }}
                </td>

            </tr>

            <tr>

                <th>
                    Nama
                </th>

                <td>
                    {{ $item->nama_lengkap }}
                </td>

            </tr>

            <tr>

                <th>
                    Kategori
                </th>

                <td>
                    {{ $item->kategori_bantuan }}
                </td>

            </tr>

            <tr>

                <th>
                    No HP
                </th>

                <td>
                    {{ $item->no_hp }}
                </td>

            </tr>

            <tr>

                <th>
                    Jenis Kelamin
                </th>

                <td>
                    {{ $item->jenis_kelamin }}
                </td>

            </tr>

            <tr>

                <th>
                    Tanggal Lahir
                </th>

                <td>

                    {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') }}

                </td>

            </tr>

        </table>

    </div>

    <div class="col-md-6">

<h6 class="fw-bold text-success mb-3">

Informasi Bantuan

</h6>

<table class="table table-borderless">

<tr>

<th width="40%">
Status Pengajuan
</th>

<td>

{{ $item->status_pengajuan }}

</td>

</tr>

<tr>

<th>
Status Penerima
</th>

<td>

{{ $item->status_penerima }}

</td>

</tr>
<tr>

    <th>Status Akhir</th>

    <td>

@if($item->status_pembaruan == 'Ditolak')

    <span class="badge-status danger">
        ● Pembaruan Ditolak
    </span>

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'
    &&
    str_contains(
        strtolower($item->catatan_admin ?? ''),
        'meninggal'
    )

)

    <span class="badge-status warning">
        ● Selesai (Meninggal)
    </span>

@elseif(

    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_penerima == 'Tidak Aktif'

)

    <span class="badge-status warning">
        ● Selesai (Usia)
    </span>

@elseif($item->status_pengajuan == 'Ditolak')

    <span class="badge-status danger">
        ● Ditolak
    </span>

@else

    <span class="badge-status success">
        ● Disetujui
    </span>

@endif

</td>

</tr>

<tr>

<th>
Tanggal Mulai
</th>

<td>

{{ $item->tanggal_mulai ?? '-' }}

</td>

</tr>

<tr>

<th>
Tanggal Selesai
</th>

<td>

{{ $item->tanggal_selesai ?? '-' }}

</td>

</tr>

<tr>

<th>
Catatan Admin
</th>

<td>

{{ $item->catatan_admin ?? '-' }}

</td>

</tr>

<tr>

<th>
Alasan Penolakan
</th>

<td>

{{ $item->alasan_penolakan ?? '-' }}

</td>

</tr>

</table>

</div>

</div>
<hr class="my-4">

<h5 class="fw-bold text-primary mb-3">

    Riwayat Dokumen

</h5>

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-primary text-white">

        <strong>Dokumen Pengajuan Awal</strong>

    </div>

    <div class="card-body">

@php

$dokumenAwal = [

'Akta Kelahiran' => $item->file_akta_kelahiran,
'Kartu Keluarga' => $item->file_kk,
'Surat Domisili' => $item->file_domisili,
'SKTM' => $item->file_sktm,
'Surat Kematian Ayah' => $item->file_kematian_ayah,
'Surat Kematian Ibu' => $item->file_kematian_ibu,
'Surat Kematian Orang Tua' => $item->file_kematian_orangtua,
'Surat Kematian Pasangan' => $item->file_kematian_pasangan,
'Bukti Perceraian' => $item->file_bukti_cerai,
'Bukti Kondisi Rumah' => $item->file_bukti_rumah,
'Surat Tidak Menikah' => $item->file_tidak_menikah,
'Surat Tidak Bekerja' => $item->file_tidak_bekerja,

];

@endphp

@foreach($dokumenAwal as $nama => $file)

@if($file)

<div class="d-flex justify-content-between align-items-center mb-2">

<span>{{ $nama }}</span>

<a href="{{ asset('storage/'.$file) }}"
   target="_blank"
   class="btn btn-sm btn-primary">

    Lihat

</a>

</div>

@endif

@endforeach

</div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-success text-white">

        <strong>Dokumen Pembaruan</strong>

    </div>
    <div class="card-body">

@php

$dokumenBaru = [
'Kartu Keluarga Terbaru' => $item->file_kk_pembaruan,
'Surat Domisili Terbaru' => $item->file_domisili_pembaruan,
'SKTM Terbaru' => $item->file_sktm_pembaruan,

];

@endphp

@php($ada=false)

@foreach($dokumenBaru as $nama => $file)

@if($file)

@php($ada=true)

<div class="d-flex justify-content-between align-items-center mb-2">

<span>{{ $nama }}</span>

<a href="{{ asset('storage/'.$file) }}"
   target="_blank"
   class="btn btn-sm btn-success">

    Lihat

</a>

</div>

@endif

@endforeach

@if(!$ada)

<small class="text-muted">

Belum pernah melakukan pembaruan dokumen.

</small>

@endif

</div>

</div>



        </div>

    </div>

</div>
</div>

@endforeach

@endsection