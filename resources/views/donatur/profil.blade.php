@extends('layouts.donatur')

@section('content')

    <div class="mb-4">

        <h2 class="donasi-title">
            Profil Saya
        </h2>

        <p class="donasi-subtitle">
            Kelola informasi akun dan keamanan akun Anda.
        </p>

    </div>

    <div class="profil-layout">

        <!-- KIRI -->
        <div>

            <div class="profil-card text-center">

                <div class="profil-avatar">

                    @if($user->foto)

                        <img src="{{ asset('storage/' . $user->foto) }}">

                    @else

                        <img src="https://i.pravatar.cc/150?img=12">

                    @endif

                    <form id="formFoto" action="{{ route('donatur.foto.update') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp" hidden
                            onchange="document.getElementById('formFoto').submit()">

                        <button type="button" class="avatar-btn" onclick="document.getElementById('foto').click()">

                            📷

                        </button>

                    </form>

                </div>

                <h4>
                    {{ $user->name }}
                </h4>

                <p>
                    {{ $user->email }}
                </p>

            </div>

            <div class="quote-card">

                <h3>❤</h3>

                <p>
                    "Terima kasih telah menjadi bagian dari perubahan melalui setiap donasi yang Anda berikan."
                </p>

            </div>

        </div>

        <!-- KANAN -->
        <div>

            <div class="profil-card mb-4">

                <h4 class="section-title">
                    👤 Informasi Pribadi
                </h4>
                <form action="{{ route('donatur.profil.update') }}" method="POST">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                minlength="3" maxlength="100" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')">
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"
                                maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <div class="col-md-6">
                            <label>Tanggal Bergabung</label>
                            <input type="text" class="form-control"
                                value="{{ $user->created_at->translatedFormat('d F Y') }}" readonly>
                        </div>

                        <div class="col-12">
                            <label>Kota</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota', $user->kota) }}"
                                minlength="3" maxlength="50" oninput="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'')">
                        </div>

                    </div>

                    <div class="text-end mt-4">

                        <button type="submit" class="btn-simpan">

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

            <div class="profil-card">

                <h4 class="section-title">
                    🛡 Keamanan Akun
                </h4>

                <div class="security-item">

                    <div>

                        <strong>Password</strong>

                        <small>
                            Terakhir diubah 3 bulan lalu
                        </small>

                    </div>
                    <button class="btn-password" data-bs-toggle="modal" data-bs-target="#ubahPasswordModal">

                        Ubah Password

                    </button>

                </div>

                <div class="security-item">

                    <div>

                        <strong>Verifikasi Email</strong>

                        <small>

                            {{ $user->email }}

                        </small>

                    </div>

                    <span class="badge-email">
                        Terverifikasi
                    </span>

                </div>
            </div>

        </div>

    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="ubahPasswordModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('donatur.password.update') }}" method="POST">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Ubah Password

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label>Password Lama</label>

                            <input type="password" name="current_password" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Password Baru</label>

                            <input type="password" name="password" class="form-control" minlength="8" maxlength="20"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Konfirmasi Password Baru</label>

                            <input type="password" name="password_confirmation" class="form-control" minlength="8"
                                maxlength="20" required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-cydics">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection