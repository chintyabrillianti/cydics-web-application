<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CYDICS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6fb;
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .register-card {

            width: 100%;
            max-width: 420px;

            background: #fff;

            padding: 32px;

            border-radius: 14px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);

            animation: fadeUp .6s ease;

            @keyframes fadeUp {

                from {
                    opacity: 0;
                    transform: translateY(25px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }

            }
        }

        .form-label {

            font-size: .85rem;
            font-weight: 600;
            color: #1e293b;

        }

        .form-control {

            height: 46px;

            border-radius: 6px;

            border: 1px solid #dbe2ea;

            font-size: .92rem;

        }

        .form-control:focus {

            box-shadow: none;

            border-color: #0B3B91;

        }

        .input-group-text {

            background: #fff;

            border-right: none;
            border: 1px solid #dbe2ea;

            color: #6b7280;

            min-width: 48px;

            justify-content: center;

        }

        .input-group .form-control {

            border-left: none;

        }

        .input-group button {
            background: #fff;
        }

        .robot-box {

            height: 52px;

            border: 1px solid #dbe2ea;

            border-radius: 8px;

            background: #f5f8fc;

            padding: 0 16px;

            display: flex;

            align-items: center;

            justify-content: space-between;

        }

        .btn-register {

            width: 100%;
            height: 52px;

            border: none;

            border-radius: 8px;

            background: #2948c7;

            color: white;

            font-weight: 600;

            font-size: .95rem;

        }

        .btn-register:hover {

            background: #203db5;

        }

        .form-check-input {

            width: 20px;
            height: 20px;

        }

        .form-control::placeholder {

            color: #9ca3af;

        }

        .password-toggle {

            cursor: pointer;

            color: #6b7280;

        }

        .form-control:hover {

            border-color: #c7d2e0;

        }
    </style>

</head>

<body>

    <div class="register-wrapper">

        <div class="register-card">

            <div class="text-center mb-4">

                <h4 class="fw-bold mb-1" style="color:#0B3B91;">
                    CYDICS
                </h4>

                <small class="text-muted">
                    "Cinta Yatim Duafa" Indonesian Communities
                </small>

            </div>

            <h3 class="fw-bold mb-2">
                Daftar Akun Baru
            </h3>

            <p class="text-muted mb-4" style="font-size:.9rem;">
                Lengkapi data diri Anda untuk mengakses portal transparansi Warga.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- FORM -->

                <div class="mb-3">

                    <label class="form-label">
                        Nama Lengkap (Sesuai KTP)
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>

                        <input
type="text"
name="name"
class="form-control @error('name') is-invalid @enderror"
placeholder="Masukkan nama lengkap Anda"
value="{{ old('name') }}"
minlength="5"
maxlength="100"
pattern="[A-Za-z\s]+"
title="Nama hanya boleh berisi huruf dan spasi."
autocomplete="name"
required>

                        @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email Aktif
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
type="email"
name="email"
class="form-control @error('email') is-invalid @enderror"
placeholder="contoh@email.com"
value="{{ old('email') }}"
maxlength="255"
autocomplete="email"
required>

                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <small class="text-muted" style="font-size:.72rem;">
                        Email ini akan digunakan untuk verifikasi dan login.
                    </small>
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Nomor Telepon
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            +62
                        </span>

                        <input
type="text"
name="phone"
class="form-control @error('phone') is-invalid @enderror"
placeholder="81234567890"
value="{{ old('phone') }}"
minlength="9"
maxlength="13"
pattern="[0-9]+"
inputmode="numeric"
autocomplete="tel"
required>

                        @error('phone')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Kata Sandi
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
type="password"
name="password"
id="password"
class="form-control @error('password') is-invalid @enderror"
placeholder="Minimal 8 karakter"
minlength="8"
maxlength="50"
pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,50}"
title="Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, dan angka."
autocomplete="new-password"
required>

                        <button type="button" class="input-group-text password-toggle" onclick="togglePassword()">

                            <i class="bi bi-eye" id="eyeIcon"></i>

                        </button>

                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>

                        <input
type="password"
name="password_confirmation"
id="password_confirmation"
class="form-control"
placeholder="Ulangi kata sandi"
minlength="8"
maxlength="50"
autocomplete="new-password"
required>

                    </div>

                </div>
                <div class="robot-box mb-4">

    <div>

        <div class="form-check">

            <input
            class="form-check-input"
            type="checkbox"
            name="captcha"
            id="robotCheck"
            value="1"
            {{ old('captcha') ? 'checked' : '' }}>

            <label
            class="form-check-label"
            for="robotCheck">
                Saya bukan robot
            </label>

        </div>

        @error('captcha')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>

    <i class="bi bi-shield-check"
       style="font-size:22px;color:#7aa7d8;">
    </i>

</div>

                <button type="submit" class="btn-register mb-3">

                    Daftar Sekarang
                    <i class="bi bi-arrow-right ms-1"></i>

                </button>
                <div class="text-center">

                    <small class="text-muted">

                        Sudah punya akun?

                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">

                            Masuk di sini

                        </a>

                    </small>

                </div>
            </form>

        </div>

    </div>
    </div>



    </div>
    <script>

        function togglePassword() {

            let password =
                document.getElementById('password');

            let eye =
                document.getElementById('eyeIcon');

            if (password.type === "password") {

                password.type = "text";

                eye.classList.remove("bi-eye");
                eye.classList.add("bi-eye-slash");

            } else {

                password.type = "password";

                eye.classList.remove("bi-eye-slash");
                eye.classList.add("bi-eye");

            }

        }
        const nameInput = document.querySelector('input[name="name"]');

nameInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^A-Za-z\s]/g, '');
});
        const phone = document.querySelector('input[name="phone"]');

phone.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0,13);
});
document.querySelector('form').addEventListener('submit', function (e) {

    const nama = document.querySelector('[name="name"]').value.trim();
    const phone = document.querySelector('[name="phone"]').value.trim();
    const password = document.querySelector('[name="password"]').value;

    if (!/^[A-Za-z\s]{5,100}$/.test(nama)) {
        alert('Nama hanya boleh berisi huruf dan spasi (5-100 karakter).');
        e.preventDefault();
        return;
    }

    if (!/^[0-9]{9,13}$/.test(phone)) {
        alert('Nomor telepon harus terdiri dari 9-13 digit angka.');
        e.preventDefault();
        return;
    }

    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,50}$/.test(password)) {
        alert('Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.');
        e.preventDefault();
    }

});

    </script>
</body>

</html>