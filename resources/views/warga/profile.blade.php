@extends('layouts.warga')

@section('content')
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <div class="mb-4">

        <h2 class="donasi-title">
            Profil Saya
        </h2>

        <p class="donasi-subtitle">
            Kelola informasi akun dan keamanan akun Anda.
        </p>

    </div>

    <div class="profil-layout">

        {{-- KIRI --}}
        <div>

            <div class="profil-card text-center">

                <div class="profil-avatar">

                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}">
                    @else
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1d4ed8&color=fff&size=200">
                    @endif

                    <form id="formFoto" action="{{ route('warga.foto.update') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png" hidden
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

                <h3>🌱</h3>

                <p>
                    "Terima kasih telah menjadi bagian dari keluarga CYDICS. Semoga setiap bantuan yang diterima dapat
                    memberikan manfaat, harapan, dan semangat untuk kehidupan yang lebih baik."
                </p>

            </div>

        </div>

        {{-- KANAN --}}
        <div>

            <div class="profil-card mb-4">

                <h4 class="section-title">
                    👤 Informasi Pribadi
                </h4>

                <form action="{{ route('warga.profil.update') }}" method="POST">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" readonly value="{{ $pengajuan->nama_lengkap ?? '-' }}">
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label>Nomor Telepon</label>
                            <input type="text" class="form-control" readonly value="{{ $pengajuan->no_hp ?? '-' }}">
                        </div>

                        <div class="col-md-6">
                            <label>Tanggal Bergabung</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $user->created_at->translatedFormat('d F Y') }}">
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
                            Gunakan password yang kuat untuk menjaga keamanan akun Anda.
                        </small>

                    </div>

                    <button type="button" class="btn-password" data-bs-toggle="modal" data-bs-target="#ubahPasswordModal">

                        Ubah Password

                    </button>

                </div>

                <div class="security-item">

                    <div>

                        <strong>Role</strong>

                        <small>{{ $user->email }}</small>

                    </div>

                    <span class="badge-email">

                        Warga

                    </span>

                </div>

            </div>

        </div>

    </div>
    <!-- Modal Ubah Password -->
    <div class="modal fade" id="ubahPasswordModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('password.update') }}" method="POST">

                    @csrf
                    @method('PUT')

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

                            <input type="password" name="password" class="form-control" minlength="8" required>

                        </div>

                        <div class="mb-3">

                            <label>Konfirmasi Password Baru</label>

                            <input type="password" name="password_confirmation" class="form-control" minlength="8" required>

                        </div>

                    </div>

                    <div class="modal-footer">

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

@endsection