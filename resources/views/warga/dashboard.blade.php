@extends('layouts.warga')

@section('content')

    {{-- PENGAJUAN DITOLAK (BARU ATAU PERUBAHAN) --}}
    @php
        $latestDitolak = null;

        if (
            $pengajuanBaru &&
            $pengajuanBaru->status_pengajuan == 'Ditolak'
        ) {
            $latestDitolak = $pengajuanBaru;
        }

        if (
            $perubahan &&
            $perubahan->status_pengajuan == 'Ditolak'
        ) {
            if (
                !$latestDitolak ||
                $perubahan->updated_at > $latestDitolak->updated_at
            ) {
                $latestDitolak = $perubahan;
            }
        }
    @endphp


    <div class="greeting-card">

        @if(auth()->user()->foto)

    <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="profile-image">

@else

    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1d4ed8&color=fff&size=200"
         class="profile-image">

@endif

        <div>

            <h2>
                Halo, {{ auth()->user()->name }}!
            </h2>

            @if($pengajuan && $pengajuan->status_penerima == 'Aktif')
                <span class="status-active">
                    <i class="bi bi-circle-fill"></i>
                    Penerima Aktif
                </span>
            @else
                <span class="status-inactive">
                    <i class="bi bi-circle-fill"></i>
                    Nonaktif
                </span>
            @endif

        </div>

    </div>
    {{-- MENUNGGU --}}
    @if(
            (
                $pengajuanBaru &&
                $pengajuanBaru->status_pengajuan == 'Menunggu' &&
                !$pengajuan
            )
            ||
            (
                $perubahan &&
                $perubahan->status_pengajuan == 'Menunggu'
            )
        )

        <div class="dashboard-card info-blue">

            <h5>
                <i class="bi bi-clock-history"></i>
                {{ $perubahan ? 'Pengajuan Perubahan Kategori' : 'Pengajuan Baru' }}
            </h5>

            <p>
                Kategori:
                <strong>
                    {{ $perubahan ? $perubahan->kategori_bantuan : $pengajuanBaru->kategori_bantuan }}
                </strong>
            </p>

            <p>
    Status:
    Menunggu Verifikasi Admin
</p>

<p>
    Silakan buka kembali aplikasi dalam <strong>1–2 hari kerja</strong>.
</p>

<hr>

            <hr>

<div class="mt-3">

    <h6 class="fw-bold mb-2">
        <i class="bi bi-headset"></i>
        Hubungi Admin
    </h6>

    <p class="mb-2">
        Jika terdapat pertanyaan mengenai proses verifikasi,
        silakan hubungi admin Yayasan.
    </p>

    @if($website && $website->telepon)

        @php
            $wa = preg_replace('/^0/', '62', $website->telepon);
        @endphp

        <p class="mb-2">
            Nomor Admin:
            <strong>{{ $website->telepon }}</strong>
        </p>

        <a href="https://wa.me/{{ $wa }}"
           target="_blank"
           class="btn btn-success">

            <i class="bi bi-whatsapp"></i>
            WhatsApp Admin

        </a>

    @endif

</div>

</div>

@endif

{{-- PERBAIKAN --}}
    @if(

            ($pengajuanBaru && $pengajuanBaru->status_pengajuan == 'Perbaikan')

            ||

            ($perubahan && $perubahan->status_pengajuan == 'Perbaikan')

        )

        @php

            $data = $perubahan ?: $pengajuanBaru;

            $sisa = $perubahan ? $sisaPerbaikanKategori : $sisaPerbaikan;

        @endphp

        <div class="dashboard-card info-warning">

            <h5>

                ⚠ Perbaikan Dokumen

            </h5>

            <div class="alert alert-warning mt-3">

                <p>
                    <strong>Catatan Admin</strong>
                </p>

                <p>
                    {{ $data->catatan_admin }}
                </p>

                <hr>

                <p>
                    Batas waktu perbaikan pada
                    <strong>
                        {{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d F Y') }}
                    </strong>
                </p>

                <p>
                    Sisa waktu:
                    <strong class="text-danger">
                        {{ $sisa }} hari
                    </strong>
                </p>

            </div>

            <p>

                Harap memperbaiki dokumen sesuai catatan admin.

            </p>
            <p>

                Revisi:

                <strong>

                    ke-{{ $data->jumlah_perbaikan }} dari 2

                </strong>

            </p>

            <a href="{{ route('perbaikan.dokumen') }}" class="btn-warning-custom">

                Perbaiki Dokumen

            </a>

        </div>

    @endif
{{-- DITERIMA --}}
@if(
    $pengajuan &&
    $pengajuan->status_penerima == 'Aktif'
)

    <div class="dashboard-card info-success">

        <h5>
            <i class="bi bi-shield-check"></i>
            Status Bantuan Aktif
        </h5>

        <div class="status-box-new">

            <div>
                <small>Kategori Bantuan</small>

                <h4>
                    {{ $pengajuan->kategori_bantuan }}
                </h4>
            </div>

            <div>
                <small>Status</small>

                <span class="badge-success-custom">
                    Aktif Disalurkan
                </span>
            </div>

            <div>
                <small>Tanggal Mulai</small>

                <p>
                    @if($pengajuan->tanggal_mulai)
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>

            <div>
                <small>Berlaku Sampai</small>

                <p>
                    @if($pengajuan->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>

        </div>

    </div>

@endif

    {{-- PEMBARUAN DOKUMEN --}}

    @if($notifPembaruan && !$pembaruanMenunggu)

        <div class="dashboard-card pembaruan-card">

            <div class="notif-icon">

                <i class="bi bi-exclamation-triangle-fill"></i>

            </div>

            <div class="notif-content">

                <h5>
                    Pembaruan Dokumen
                </h5>

                <p>
                    Masa pembaruan dokumen dimulai pada:

                    <strong>
                        {{ \Carbon\Carbon::parse(
                $pengajuan->tanggal_pembaruan_dokumen
            )->translatedFormat('d F Y') }}
                    </strong>
                </p>

                <p>
                    Batas pembaruan dokumen:

                    <strong>
                        {{ \Carbon\Carbon::parse(
                $pengajuan->tanggal_selesai
            )->translatedFormat('d F Y') }}
                    </strong>
                </p>

                <p>
                    Sisa waktu:

                    <strong>
                        {{ $sisaHari }}
                    </strong>
                </p>

                <p>
                    Silakan memperbarui dokumen agar bantuan tetap aktif.
                </p>

                <a href="{{ route('pembaruan.dokumen') }}" class="btn-update">

                    Perbarui Dokumen

                </a>

            </div>

        </div>

    @endif

    @if($pembaruanMenunggu)

<div class="dashboard-card info-blue">

    <h5>
        <i class="bi bi-cloud-check"></i>
        Pembaruan Dokumen
    </h5>

    <p>
        Dokumen pembaruan telah berhasil dikirim.
    </p>

    <p>
        Tanggal Pengajuan :
        <strong>
            {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan_pembaruan)
    ->translatedFormat('d F Y H:i') }}
        </strong>
    </p>

    <p>
        Saat ini dokumen sedang diverifikasi oleh admin.
    </p>

    <p>
        <strong>
            Bantuan Anda tetap aktif sampai proses verifikasi selesai.
        </strong>
    </p>
    <div class="alert alert-warning mt-3 mb-0">

    <i class="bi bi-info-circle me-2"></i>

    <strong>Perhatian!</strong>

    Harap cek aplikasi secara berkala melalui <strong>Dashboard</strong>.
    Informasi apakah pembaruan dokumen
    <strong>diterima</strong> atau
    <strong>ditolak</strong>
    akan ditampilkan pada halaman ini setelah proses verifikasi admin selesai.

</div>

</div>

@endif

@if(
    $pengajuan &&
    $pengajuan->status_pembaruan == 'Diterima'
)

<div class="dashboard-card info-success">

    <h5>
        <i class="bi bi-check-circle-fill"></i>
        Pembaruan Dokumen Berhasil
    </h5>

    <p>
    Dokumen pembaruan telah diverifikasi dan diterima oleh admin.
</p>

<p>
    Masa bantuan berhasil diperpanjang selama 6 bulan.
</p>

<p>
    Jadwal pembaruan dokumen berikutnya akan tersedia sesuai periode selanjutnya.
</p>

<p>
    Berlaku sampai :

    <strong>
        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
    </strong>
</p>

</div>

@endif

@if($pembaruanDitolak && now()->lt(\Carbon\Carbon::parse($pembaruanDitolak->tanggal_boleh_ajukan)))

<div class="dashboard-card info-danger">

    <h5>
        <i class="bi bi-x-circle-fill"></i>
        Pembaruan Dokumen Ditolak
    </h5>

    <div class="alert alert-danger mt-3">

        <p>
            <strong>Alasan Penolakan</strong>
        </p>

        <p>
            {{ $pembaruanDitolak->catatan_admin }}
        </p>

        <hr>

        <p>
            Anda dapat mengajukan bantuan kembali pada
            <strong>
                {{ \Carbon\Carbon::parse($pembaruanDitolak->tanggal_boleh_ajukan)->translatedFormat('d F Y') }}
            </strong>
        </p>

        <p>
            Sisa waktu :
            <strong class="text-danger">

                {{ now()->startOfDay()->diffInDays(
                    \Carbon\Carbon::parse($pembaruanDitolak->tanggal_boleh_ajukan),
                    false
                ) }}

                hari

            </strong>

        </p>

    </div>

</div>

@endif

@if($pembaruanDitolak && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($pembaruanDitolak->tanggal_boleh_ajukan)))

<div class="dashboard-card info-blue">

    <h5>

        <i class="bi bi-check-circle-fill"></i>

        Anda Sudah Bisa Mengajukan Bantuan

    </h5>

    <p>

        Masa tunggu 30 hari telah berakhir.

    </p>

    <a href="{{ route('pengajuan.index') }}"
       class="btn-primary-custom">

        Ajukan Bantuan

    </a>

</div>

@endif

    @if(
    !$pengajuan &&
    $latestDitolak &&
    now()->lt(Carbon\Carbon::parse($latestDitolak->tanggal_boleh_ajukan))
)

    <div class="dashboard-card info-danger">

        <h5 class="text-danger">
            <i class="bi bi-x-circle-fill"></i>
            Pengajuan Ditolak
        </h5>

        <div class="alert alert-danger mt-3">

            <p><strong>Alasan Penolakan</strong></p>

            <p>{{ $latestDitolak->catatan_admin }}</p>

            <hr>

            <p>
                Anda dapat mengajukan kembali pada
                <strong>
                    {{ \Carbon\Carbon::parse($latestDitolak->tanggal_boleh_ajukan)->translatedFormat('d F Y') }}
                </strong>
            </p>

            <p>
                Sisa waktu :
                <strong class="text-danger">
                    {{ Carbon\Carbon::today()->diffInDays(
                        Carbon\Carbon::parse($latestDitolak->tanggal_boleh_ajukan)->startOfDay()
                    ) }} hari
                </strong>
            </p>

        </div>

    </div>

@elseif(
    !$pengajuan &&
    $latestDitolak &&
    now()->gte(Carbon\Carbon::parse($latestDitolak->tanggal_boleh_ajukan))
)

    <div class="dashboard-card info-blue">

        <h5>
            <i class="bi bi-check-circle-fill"></i>
            Ajukan Bantuan
        </h5>

        <p>Masa tunggu pengajuan telah berakhir.</p>

        <p>Anda sudah dapat mengajukan bantuan kembali.</p>

        <a href="{{ route('pengajuan.index') }}" class="btn-primary-custom">
            Ajukan Bantuan
        </a>

    </div>

@endif
    @if(
    !$pengajuan &&
    $riwayat->isNotEmpty() &&
    $riwayat->first()->status_penerima == 'Tidak Aktif' &&
    $riwayat->first()->status_pengajuan == 'Diterima' &&
    $riwayat->first()->status_pembaruan == 'Belum'
)

<div class="dashboard-card info-dark">

    <h5>
        <i class="bi bi-info-circle-fill"></i>
        Bantuan Telah Selesai
    </h5>

    <div class="alert alert-secondary mt-3">

        <p>
            Bantuan telah dinyatakan selesai.
        </p>

        <p>
            <strong>Alasan :</strong><br>
            {{ $riwayat->first()->catatan_admin }}
        </p>

    </div>

</div>

@endif


    {{-- PERUBAHAN KATEGORI --}}
    @if(
            $pengajuan &&
            $pengajuan->status_penerima == 'Aktif' &&
            !$perubahan &&
            !$sisaTungguPerubahan
        )

        <div class="dashboard-card">

            <h5>
                <i class="bi bi-arrow-repeat"></i>
                Perubahan Kategori Bantuan
            </h5>

            <p>
                Apabila kondisi keluarga berubah, Anda dapat mengajukan perubahan kategori bantuan.
            </p>

            <div class="category-list">

                {{-- YATIM --}}
                @if($pengajuan->kategori_bantuan == 'Yatim')

                    <a href="{{ route(
                        'perubahan.kategori',
                        'Yatim-Piatu'
                    ) }}" class="category-btn">
                        Yatim-Piatu
                    </a>


                    {{-- PIATU --}}
                @elseif($pengajuan->kategori_bantuan == 'Piatu')

                    <a href="{{ route(
                        'perubahan.kategori',
                        'Yatim-Piatu'
                    ) }}" class="category-btn">
                        Yatim-Piatu
                    </a>

                    {{-- YATIM PIATU --}}
                {{-- YATIM PIATU --}}
@elseif($pengajuan->kategori_bantuan == 'Yatim-Piatu')

<div class="text-muted mt-2">
    Tidak ada perubahan kategori yang tersedia.
</div>

                    {{-- DUAFA ANAK --}}
                @elseif($pengajuan->kategori_bantuan == 'Duafa Anak')

                    <a href="{{ route(
                        'perubahan.kategori',
                        'Yatim'
                    ) }}" class="category-btn">
                        Yatim
                    </a>

                    <a href="{{ route(
                        'perubahan.kategori',
                        'Piatu'
                    ) }}" class="category-btn">
                        Piatu
                    </a>

                    <a href="{{ route(
                        'perubahan.kategori',
                        'Yatim-Piatu'
                    ) }}" class="category-btn">
                        Yatim-Piatu
                    </a>

                    {{-- DUAFA LANSIA --}}
                @elseif($pengajuan->kategori_bantuan == 'Duafa Lansia')

                    <div class="text-muted mt-2">
                        Tidak ada perubahan kategori yang tersedia.
                    </div>

                @endif

            </div>


        </div>

    @endif
    

    @if(
    $pengajuan &&
    $pengajuan->status_penerima == 'Tidak Aktif' &&
    $pengajuan->status_pengajuan == 'Diterima' &&
    $pengajuan->status_pembaruan == 'Belum'
)

<div class="dashboard-card info-secondary">

    <h5>
        <i class="bi bi-check-circle-fill"></i>
        Bantuan Telah Selesai
    </h5>

    <div class="alert alert-secondary mt-3">

        <p>
            Program bantuan telah selesai karena masa penerimaan bantuan telah berakhir sesuai ketentuan yayasan.
        </p>

        <p>
            Status bantuan Anda saat ini sudah dinyatakan selesai.
        </p>

        <p>
            Apabila di kemudian hari memenuhi persyaratan kategori bantuan lainnya,
            Anda dapat mengajukan bantuan kembali.
        </p>

    </div>

</div>

@endif

    <div class="dashboard-card">

        <h5>
            <i class="bi bi-clock-history"></i>
            Riwayat Bantuan
        </h5>
        <div class="table-responsive">
            <table class="table">

                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">
                            Status
                        </th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($riwayat as $item)

                        <tr>

                            <td>
                                {{ $item->kategori_bantuan }}
                            </td>


                            <td>

                                @if($item->status_pembaruan == 'Menunggu')

    <span class="status-badge waiting">
        Menunggu Pembaruan
    </span>

@elseif($item->status_pembaruan == 'Diterima')

    <span class="status-badge active">
        Pembaruan Diterima
    </span>

@elseif($item->status_pembaruan == 'Ditolak')

    <span class="status-badge reject">
        Pembaruan Ditolak
    </span>

@elseif($item->status_pengajuan == 'Menunggu')

    <span class="status-badge waiting">
        Menunggu
    </span>

@elseif($item->status_pengajuan == 'Perbaikan')

    <span class="status-badge repair">
        Perbaikan
    </span>

@elseif($item->status_pengajuan == 'Ditolak')

    <span class="status-badge reject">
        Ditolak
    </span>
    @elseif(
    $item->status_penerima == 'Tidak Aktif'
    &&
    $item->status_pengajuan == 'Diterima'
    &&
    $item->status_pembaruan == 'Belum'
)

    <span class="status-badge finish">
        Selesai
    </span>

@elseif($item->status_penerima == 'Aktif')

    <span class="status-badge active">
        Aktif
    </span>

@else

    <span class="status-badge finish">
        Selesai
    </span>

@endif
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

                                                    {{ \Carbon\Carbon::parse(
                                        $item->tanggal_selesai
                                    )->translatedFormat('d F Y') }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada riwayat bantuan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

@endsection