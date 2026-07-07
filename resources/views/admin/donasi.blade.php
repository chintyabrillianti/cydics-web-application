@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            Kelola Donasi
        </h2>

    </div>

    {{-- DATA DONATUR --}}
    <div class="donasi-card mb-4">

        <div class="donasi-header d-flex justify-content-between align-items-center">

            <div>
                <h5>Data Donatur</h5>
                <p class="mb-0">
                    Kelola akun dan informasi donatur terdaftar.
                </p>
            </div>

            <button class="btn btn-donatur" data-bs-toggle="modal" data-bs-target="#tambahDonaturModal">

                <i class="bi bi-person-plus"></i>
                Tambah Akun Donatur

            </button>

        </div>

        <div class="table-responsive">

            <table class="table donasi-table mb-0">

                <thead class="table-light">

                    <tr>
                        <th>DONATUR</th>
                        <th>NO HP</th>
                        <th>STATUS</th>
                        <th>DONASI TERAKHIR</th>
                        <th>DIBUAT</th>
                        <th>AKSI</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($donatur as $item)

                        <tr>

                            <td>
    <div class="user-info">

        <img
            class="table-avatar"
            src="{{ $item->foto ? asset('storage/'.$item->foto) : asset('images/default-profile.png') }}"
            alt="foto">

        <strong>{{ $item->name }}</strong>

    </div>
</td>

                            <td>{{ $item->phone }}</td>

                                <td>

                                    @if($item->status_donatur == 'Aktif')

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $item->last_donation_at
                                        ? \Carbon\Carbon::parse($item->last_donation_at)->translatedFormat('d M Y')
                                        : '-' }}

                                </td>

                                <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>

                            <td>

                                <i class="bi bi-pencil-square me-3 text-primary" role="button" title="Edit Donatur"
                                    data-bs-toggle="modal" data-bs-target="#modalDonatur{{ $item->id }}">
                                </i>

                                <i class="bi bi-key-fill text-warning" role="button" title="Reset Password"
                                    data-bs-toggle="modal" data-bs-target="#resetPassword{{ $item->id }}">
                                </i>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">

                                Belum ada data donatur.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-between align-items-center p-3">

    <small class="text-muted">
        Menampilkan {{ $donatur->count() }} dari {{ $totalDonatur }} donatur
    </small>

    <a href="{{ route('admin.semua.donatur') }}"
        class="text-decoration-none fw-semibold">

        Lihat Semua Donatur

    </a>

</div>

    </div>

    {{-- BAWAH --}}
    <div class="row g-4">

        {{-- RIWAYAT DONASI --}}
        <div class="col-lg-8">

            <div class="donasi-card donasi-history">

                <div class="donasi-header">

                    <h5>Riwayat Donasi</h5>

                    <p class="mb-0">
                        Daftar transaksi donasi terbaru.
                    </p>

                </div>

                <div class="table-responsive">

                    <table class="table donasi-table mb-0">

                        <thead class="table-light">

                            <tr>

    <th>ID Donasi</th>
<th>ID Donatur</th>
<th>Donatur</th>
<th>No HP</th>
<th>Program</th>
<th>Tanggal</th>
<th>Status</th>
<th>Aksi</th>

</tr>

                        </thead>

                        <tbody>

    @forelse($donasis as $donasi)

        <tr>

    <td class="text-nowrap">
    DN-{{ str_pad($donasi->id,4,'0',STR_PAD_LEFT) }}
</td>

    <td class="text-nowrap">
    DNT-{{ str_pad($donasi->user->id,4,'0',STR_PAD_LEFT) }}
</td>

    <td>
    <div class="user-info">

        <img
            class="table-avatar"
            src="{{ $donasi->user->foto ? asset('storage/'.$donasi->user->foto) : asset('images/default-profile.png') }}"
            alt="foto">

        <span>{{ $donasi->user->name }}</span>

    </div>
</td>

    <td>
        {{ $donasi->user->phone }}
    </td>

    <td>
        {{ $donasi->program->nama }}
    </td>

    <td>
        {{ $donasi->created_at->translatedFormat('d F Y') }}
    </td>

    <td>

        @if($donasi->status=='Menunggu')

            <span class="badge bg-warning text-dark">
                Menunggu
            </span>

        @else

            <span class="badge bg-success">
                Terverifikasi
            </span>

        @endif

    </td>

    <td>

        <button
            type="button"
            class="btn btn-sm btn-cydics btn-lihat"

            data-id="{{ $donasi->id }}"
            data-user="{{ $donasi->user->name }}"
            data-foto="{{ $donasi->user->foto ? asset('storage/'.$donasi->user->foto) : asset('images/default-profile.png') }}"
            data-program="{{ $donasi->program->nama }}"
            data-bukti="{{ asset('storage/'.$donasi->bukti_transfer) }}"
            data-status="{{ $donasi->status }}"
            data-nominal="{{ $donasi->nominal }}"
data-catatan="{{ $donasi->catatan }}"
data-url="{{ route('admin.donasi.verifikasi',$donasi->id) }}">

            Lihat

        </button>

    </td>

</tr>

    @empty

        <tr>

            <td colspan="5" class="text-center py-4">

                Belum ada donasi.

            </td>

        </tr>

    @endforelse

</tbody>

                    </table>

                                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">

                    <small class="text-muted">
                        Menampilkan
                        {{ $donasis->firstItem() ?? 0 }}
                        sampai
                        {{ $donasis->lastItem() ?? 0 }}
                        dari
                        {{ $donasis->total() }}
                        data donasi
                    </small>

                    {{ $donasis->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

        {{-- PANEL VERIFIKASI --}}
        <div class="col-lg-4">

            <div class="donasi-card border-primary">

                <div class="donasi-header">

                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-shield-check"></i>
                        Panel Verifikasi
                    </h4>

                    <p class="mb-0">
                        Menunggu tindakan Admin
                    </p>

                </div>

                <div class="p-3">

    <form id="formVerifikasi"
        action=""
        method="POST">

        @csrf
        @method('PUT')

        <div class="border rounded p-3 bg-light">

            <div class="mb-3">

            <div class="mb-3">

    <span
        id="panelStatus"
        class="badge bg-warning text-dark">

        Menunggu

    </span>

</div>
<div class="text-center mb-3">

    <img
    id="panelFoto"
    class="panel-avatar d-none"
    src=""
    alt="Foto Donatur">

</div>

                <small class="text-muted">
                    Pengirim
                </small>

                <div id="panelUser" class="fw-bold">

                    Belum ada donasi dipilih

                </div>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    Program
                </small>

                <div id="panelProgram">

                    -

                </div>

            </div>
            <div class="mb-3">

    <small class="text-muted">
        Catatan Donatur
    </small>

    <div
        id="panelCatatan"
        class="border rounded bg-white p-2 mt-1"
        style="min-height:70px;">

        -

    </div>

</div>

            <div class="mb-3">

                <small class="text-muted">

                    Bukti Transfer

                </small>

                <img
    id="panelBukti"
    src=""
    class="img-fluid rounded border mt-2 d-none"
    style="cursor:pointer;"
    data-bs-toggle="modal"
    data-bs-target="#modalBukti">

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Nominal Transfer

                </label>

                <input
    type="number"
    id="panelNominal"
    name="nominal"
    class="form-control"
    placeholder="Masukkan nominal transfer"
    min="1000"
    required>

<div
    id="warningNominal"
    class="text-danger small mt-2 d-none">

</div>

            </div>

        </div>
        <div
    id="warningPanel"
    class="alert alert-danger d-none mt-3">

</div>

        <button
    id="btnVerifikasi"
    class="btn btn-cydics w-100 mt-3">

    <i class="bi bi-check-circle"></i>

    Verifikasi Pembayaran

</button>

    </form>

</div>
            </div>

        </div>

    </div>
    <div class="modal fade" id="tambahDonaturModal" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('admin.donasi.store') }}" method="POST">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Tambah Donatur
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label">
Nama <span class="text-danger">*</span>
</label>

                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required
                                minlength="3" maxlength="100" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')">
                        </div>

                        <div class="mb-3">

                        {{-- Email --}}
                        <label class="form-label">
Email <span class="text-danger">*</span>
</label>

                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            value="{{ old('email') }}"
                            maxlength="100"
                            placeholder="contoh@email.com"
                            required>

                    </div>


                        {{-- No HP --}}
                        <div class="mb-3">
                            <label class="form-label">
Nomor Hp <span class="text-danger">*</span>
</label>

                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" minlength="10" maxlength="15"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">

                            <label class="form-label">
Password <span class="text-danger">*</span>
</label>

                            <input
    type="password"
    class="form-control"
    name="password"
    placeholder="Minimal 8 karakter"
    minlength="8"
    maxlength="20"
    autocomplete="new-password"
    required>

                        </div>
                        <div class="mb-3">

                            <label class="form-label">
Konfirmasi Password <span class="text-danger">*</span>
</label>

                            <input
    type="password"
    class="form-control"
    name="password_confirmation"
    placeholder="Ulangi password"
    minlength="8"
    maxlength="20"
    autocomplete="new-password"
    required>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-cydics">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    @foreach($donatur as $item)

        <div class="modal fade" id="modalDonatur{{ $item->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.donasi.update', $item->id) }}" method="POST">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Edit Donatur

                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Nama</label>

                                <input type="text" name="name" class="form-control" minlength="3" maxlength="100" value="{{ $item->name }}"
                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                type="email" class="form-control" name="email" value="{{ old('email') }}" maxlength="100" required>
                            </div>

                            <div class="mb-3">

                                <label>No HP</label>

                                <input type="text" name="phone" class="form-control" minlength="10" maxlength="15" value="{{ $item->phone }}" maxlength="15"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-cydics">

                                Simpan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

    @foreach($donatur as $item)

        <div class="modal fade" id="resetPassword{{ $item->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.donasi.reset.password', $item->id) }}" method="POST" autocomplete="off">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Reset Password

                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Password Baru</label>

                                <input type="password" name="password" class="form-control" minlength="8" maxlength="20"
                                    autocomplete="new-password" required>

                            </div>

                            <div class="mb-3">

                                <label>Konfirmasi Password</label>

                                <input type="password" name="password_confirmation" class="form-control" minlength="8"
                                    maxlength="20" autocomplete="new-password" required>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-cydics">

                                Reset Password

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach
    <!-- Modal Preview Bukti Transfer -->
<div class="modal fade"
     id="modalBukti"
     tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Bukti Transfer Donatur

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center">

                <img
                    id="previewBukti"
                    src=""
                    class="img-fluid rounded">

            </div>

        </div>

    </div>

</div>

    <script>
const panelFoto = document.getElementById('panelFoto');
panelFoto.classList.add('d-none');
panelFoto.src = '';
const panelCatatan = document.getElementById('panelCatatan');
const panelUser = document.getElementById('panelUser');
const panelProgram = document.getElementById('panelProgram');
const panelBukti = document.getElementById('panelBukti');
const panelNominal = document.getElementById('panelNominal');
const panelStatus = document.getElementById('panelStatus');
const formVerifikasi = document.getElementById('formVerifikasi');
const warningPanel = document.getElementById('warningPanel');
const warningNominal = document.getElementById('warningNominal');
const btnVerifikasi = document.getElementById('btnVerifikasi');
const previewBukti = document.getElementById('previewBukti');

let dipilih = false;

document.querySelectorAll('.btn-lihat').forEach(button=>{

    button.addEventListener('click',function(){
        panelFoto.src = this.dataset.foto;
panelFoto.classList.remove('d-none');

        panelUser.innerHTML = this.dataset.user;

        panelProgram.innerHTML = this.dataset.program;

        panelCatatan.innerHTML =
    this.dataset.catatan && this.dataset.catatan.trim() !== ''
        ? this.dataset.catatan
        : '<span class="text-muted">Tidak ada catatan dari donatur.</span>';
        
        if (this.dataset.status == 'Menunggu') {

    panelStatus.innerHTML = 'Menunggu';
    panelStatus.className = 'badge bg-warning text-dark';

    btnVerifikasi.disabled = false;

    btnVerifikasi.classList.remove('btn-secondary');
    btnVerifikasi.classList.add('btn-cydics');

    btnVerifikasi.innerHTML =
        '<i class="bi bi-check-circle"></i> Verifikasi Pembayaran';

    panelNominal.readOnly = false;

} else {

    panelStatus.innerHTML = 'Terverifikasi';
    panelStatus.className = 'badge bg-success';

    btnVerifikasi.disabled = true;

    btnVerifikasi.classList.remove('btn-cydics');
    btnVerifikasi.classList.add('btn-secondary');

    btnVerifikasi.innerHTML =
        '<i class="bi bi-check-circle"></i> Sudah Terverifikasi';

    panelNominal.readOnly = true;

}

        panelBukti.src = this.dataset.bukti;

        previewBukti.src = this.dataset.bukti;

        panelBukti.classList.remove('d-none');

        panelNominal.value = this.dataset.nominal ?? '';

        formVerifikasi.action = this.dataset.url;

        dipilih = true;

warningPanel.classList.add('d-none');
warningNominal.classList.add('d-none');

    });

});
formVerifikasi.addEventListener('submit',function(e){

    warningPanel.classList.add('d-none');
    warningNominal.classList.add('d-none');

    if(!dipilih){

        e.preventDefault();

        warningPanel.innerHTML =
        'Silakan pilih salah satu donasi terlebih dahulu.';

        warningPanel.classList.remove('d-none');

        return;
    }

    if(panelNominal.value == ''){

        e.preventDefault();

        warningNominal.innerHTML =
        'Nominal transfer wajib diisi.';

        warningNominal.classList.remove('d-none');

        return;
    }

});

</script>

@endsection