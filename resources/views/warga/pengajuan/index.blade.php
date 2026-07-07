@extends('layouts.warga')

@section('content')
@if(
    $pengajuan &&
    $pengajuan->status_pengajuan == 'Ditolak' &&
    $sisaTunggu
)

<div class="alert alert-danger mb-4">

    <h5>
        Pengajuan Ditolak
    </h5>

    <p>
        {{ $pengajuan->alasan_penolakan }}
    </p>

    <p>
        Anda dapat mengajukan kembali dalam

        <strong>
            {{ $sisaTunggu }} hari
        </strong>
    </p>

    <p class="mb-0">
        Tanggal pengajuan kembali:

        <strong>
            {{ \Carbon\Carbon::parse(
                $pengajuan->tanggal_boleh_ajukan
            )->translatedFormat('d F Y') }}
        </strong>
    </p>

</div>

@endif
@php

$perubahan =
    session('kategori_perubahan');

@endphp

    @if(session('warning'))

        <div class="alert alert-warning mb-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('warning') }}

            Silakan tunggu keputusan admin.

        </div>

    @endif

    <div class="program-header">

        <h1>Program Bantuan</h1>

        <p>
            Pilih kategori bantuan yang sesuai dengan kondisi Anda untuk memulai pengajuan bantuan.
        </p>

    </div>

    <form class="search-program" id="searchProgram">

    <div class="search-input">

        <i class="bi bi-search"></i>

        <input
            type="text"
            id="searchInput"
            placeholder="Cari kategori bantuan..."
        >

    </div>

    <button
        type="submit"
        class="btn-search-program">

        <i class="bi bi-search me-1"></i>
        Cari

    </button>

    <button
        type="button"
        id="resetSearch"
        class="btn-reset-program">

        <i class="bi bi-arrow-clockwise me-1"></i>
        Reset

    </button>

</form>

<div id="emptySearch" class="empty-search" style="display:none;">

    <i class="bi bi-search"></i>

    <h4>Kategori bantuan tidak ditemukan</h4>

    <p>
        Coba gunakan kata kunci lain.
    </p>

</div>

    <div class="program-grid-custom">

        <!-- YATIM -->
        <div class="program-card-new searchable-card">

            <div class="program-top">
                <div class="program-icon">
                    <i class="bi bi-person"></i>
                </div>

                <span class="program-badge">
                    KATEGORI 1
                </span>
            </div>

            <h4>Yatim</h4>

            <p>
                Bantuan untuk anak yang telah kehilangan ayah.
            </p>

            <div class="program-info-box">
                <div>✓ Usia di bawah 18 tahun</div>
                <div>✓ Surat Kematian Ayah Kandung</div>
            </div>

            <div class="program-footer">

                <span class="program-status">
                    ● Kuota Tersedia
                </span>

                @if($sisaTunggu)

    <button class="btn-ajukan disabled" disabled>
        Tunggu {{ $sisaTunggu }} Hari
    </button>

@elseif(
    $pengajuan &&
    $pengajuan->kategori_bantuan == 'Yatim' &&
    $pengajuan->status_pengajuan != 'Ditolak'
)

                    <button class="btn-ajukan disabled" disabled>

                        Sudah Mengajukan

                    </button>

                @else

                    <a href="{{ route('pengajuan.yatim') }}" class="btn-ajukan">

                        Ajukan Sekarang

                    </a>

                @endif

            </div>

        </div>

        <!-- PIATU -->
        <div class="program-card-new searchable-card">

            <div class="program-top">
                <div class="program-icon">
                    <i class="bi bi-person-heart"></i>
                </div>

                <span class="program-badge">
                    KATEGORI 2
                </span>
            </div>

            <h4>Piatu</h4>

            <p>
                Bantuan untuk anak yang telah kehilangan ibu.
            </p>

            <div class="program-info-box">
                <div>✓ Usia di bawah 18 tahun</div>
                <div>✓ Surat Kematian Ibu Kandung</div>
            </div>

            <div class="program-footer">

                <span class="program-status">
                    ● Kuota Tersedia
                </span>

                @if($sisaTunggu)

    <button class="btn-ajukan disabled" disabled>
        Tunggu {{ $sisaTunggu }} Hari
    </button>

@elseif(
    $pengajuan &&
    (
        $pengajuan->kategori_bantuan == 'Yatim' ||
        $pengajuan->kategori_bantuan == 'Piatu'
    ) &&
    $pengajuan->status_pengajuan != 'Ditolak'
)

                    <button class="btn-ajukan disabled" disabled>

                        Sudah Mengajukan

                    </button>

                @else

                    <a href="{{ route('pengajuan.piatu') }}" class="btn-ajukan">

                        Ajukan Sekarang

                    </a>

                @endif
            </div>

        </div>

        <!-- YATIM PIATU -->
        <div class="program-card-new searchable-card">

            <div class="program-top">
                <div class="program-icon">
                    <i class="bi bi-people"></i>
                </div>

                <span class="program-badge">
                    KATEGORI 3
                </span>
            </div>

            <h4>Yatim-Piatu</h4>

            <p>
                Bantuan untuk anak yang kehilangan kedua orang tua.
            </p>

            <div class="program-info-box">
                <div>✓ Usia di bawah 18 tahun</div>
                <div>✓ Prioritas bantuan</div>
            </div>

            <div class="program-footer">

                <span class="program-status">
                    ● Kuota Tersedia
                </span>

                @if($sisaTunggu)

    <button class="btn-ajukan disabled" disabled>
        Tunggu {{ $sisaTunggu }} Hari
    </button>

@elseif(
    $pengajuan &&(
    $pengajuan->kategori_bantuan == 'Yatim-Piatu'
) &&
    $pengajuan->status_pengajuan != 'Ditolak'
)

                    <button class="btn-ajukan disabled" disabled>

                        Sudah Mengajukan

                    </button>

                @else

                    <a href="{{ route('pengajuan.yatim-piatu') }}" class="btn-ajukan">

                        Ajukan Sekarang

                    </a>

                @endif

            </div>

        </div>

    </div>

    <div class="program-bottom">

        <!-- DUAFA ANAK -->
        <div class="program-card-new searchable-card">

            <div class="program-top">
                <div class="program-icon">
                    <i class="bi bi-person-standing"></i>
                </div>

                <span class="program-badge">
                    KATEGORI 4
                </span>
            </div>

            <h4>Duafa Anak</h4>

            <p>
                Bantuan bagi anak dari keluarga kurang mampu.
            </p>

            <div class="program-info-box">
                <div>✓ Surat Keterangan Tidak Mampu</div>
                <div>✓ Surat Keterangan Tidak Menikah</div>
            </div>

            <div class="program-footer">

                <span class="program-status">
                    ● Kuota Tersedia
                </span>

                @if($sisaTunggu)

    <button
        class="btn-ajukan disabled"
        disabled>

        Tunggu {{ $sisaTunggu }} Hari

    </button>

@elseif(
    $pengajuan &&
    $pengajuan->kategori_bantuan == 'Duafa Anak' &&
    in_array($pengajuan->status_pengajuan, [
        'Menunggu',
        'Perbaikan',
        'Diterima'
    ])
)

<button
    class="btn-ajukan disabled"
    disabled>

    Sudah Mengajukan

</button>

@else

    <a href="{{ route('pengajuan.duafa-anak') }}"
       class="btn-ajukan">

        Ajukan Sekarang

    </a>

@endif

            </div>

        </div>

        <!-- DUAFA LANSIA -->
        <div class="program-card-new searchable-card">

            <div class="program-top">
                <div class="program-icon">
                    <i class="bi bi-person-wheelchair"></i>
                </div>

                <span class="program-badge">
                    KATEGORI 5
                </span>
            </div>

            <h4>Duafa Lansia</h4>

            <p>
                Bantuan bagi lansia kurang mampu.
            </p>

            <div class="program-info-box">
                <div>✓ Usia di atas 55 tahun</div>
                <div>✓ Tidak memiliki penghasilan tetap</div>
            </div>

            <div class="program-footer">

                <span class="program-status">
                    ● Kuota Tersedia
                </span>

                @if($sisaTunggu)

    <button
        class="btn-ajukan disabled"
        disabled>

        Tunggu {{ $sisaTunggu }} Hari

    </button>

@elseif(
    $pengajuan &&
    (
        $pengajuan->kategori_bantuan == 'Yatim-Piatu' ||
        $pengajuan->kategori_bantuan == 'Duafa Anak' ||
        $pengajuan->kategori_bantuan == 'Duafa Lansia'
    ) &&
    in_array($pengajuan->status_pengajuan, [
        'Menunggu',
        'Perbaikan',
        'Diterima'
    ])
)

    <button
        class="btn-ajukan disabled"
        disabled>

        Sudah Mengajukan

    </button>

@else

    <a href="{{ route('pengajuan.duafa-lansia') }}"
       class="btn-ajukan">

        Ajukan Sekarang

    </a>

@endif

            </div>

        </div>

    </div>
    <script>

document.addEventListener("DOMContentLoaded",function(){

    const input=document.getElementById("searchInput");

    const cards=document.querySelectorAll(".searchable-card");

    const empty=document.getElementById("emptySearch");

    document
    .getElementById("searchProgram")
    .addEventListener("submit",function(e){

        e.preventDefault();

        const keyword=input.value.toLowerCase().trim();

        let ditemukan=0;

        cards.forEach(function(card){

            const text=card.innerText.toLowerCase();

            if(text.includes(keyword)){

                card.style.display="flex";
                ditemukan++;

            }else{

                card.style.display="none";

            }

        });

        if(ditemukan===0){

            empty.style.display="block";

        }else{

            empty.style.display="none";

        }

    });

    document
    .getElementById("resetSearch")
    .addEventListener("click",function(){

        input.value="";

        cards.forEach(function(card){

            card.style.display="flex";

        });

        empty.style.display="none";

    });

});

</script>

@endsection