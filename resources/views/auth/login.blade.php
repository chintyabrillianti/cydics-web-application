<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:#f4f6fb;
}

.register-wrapper{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px 20px;
}

.register-card{

    width:100%;
    max-width:420px;

    background:#fff;

    padding:32px;

    border-radius:14px;

    box-shadow:
    0 20px 50px rgba(15,23,42,.08);

    animation:fadeUp .6s ease;
}

@keyframes fadeUp{

    from{
        opacity:0;
        transform:translateY(25px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }

}

.form-label{

    font-size:.85rem;
    font-weight:600;
    color:#1e293b;

}

.form-control{

    height:46px;

    border-radius:6px;

    border:1px solid #dbe2ea;

    font-size:.92rem;

}

.form-control:focus{

    box-shadow:none;

    border-color:#0B3B91;

}

.input-group-text{

    background:#fff;

    border-right:none;
    border:1px solid #dbe2ea;

    color:#6b7280;

    min-width:48px;

    justify-content:center;

}

.input-group .form-control{

    border-left:none;

}

.input-group button{
    background:#fff;
}

.robot-box{

    height:52px;

    border:1px solid #dbe2ea;

    border-radius:8px;

    background:#f5f8fc;

    padding:0 16px;

    display:flex;

    align-items:center;

    justify-content:space-between;

}
.btn-register{

    width:100%;
    height:52px;

    border:none;

    border-radius:8px;

    background:#2948c7;

    color:white;

    font-weight:600;

    font-size:.95rem;

    transition:.3s ease;

}

.btn-register:hover{

    background:#203db5;
    transform:translateY(-2px);

}

.form-check-input{

    width:20px;
    height:20px;

}

.form-control::placeholder{

    color:#9ca3af;

}

.password-toggle{

    cursor:pointer;

    color:#6b7280;

}
.form-control:hover{

    border-color:#c7d2e0;

}
</style>
<div class="register-wrapper">

<div class="register-card">

    <div class="text-center mb-4">

        <img
        src="{{ asset('images/logo.jpeg') }}"
        width="60"
        class="mb-3">

        <h4 class="fw-bold mb-1 text-primary">
            CYDICS
        </h4>

        <small class="text-muted">
            Cinta Yatim Duafa Indonesian Communities
        </small>

    </div>

    <h3 class="fw-bold mb-1">
    Selamat Datang 👋
    </h3>

        <p class="text-muted mb-4">
            Login untuk melanjutkan ke dashboard CYDICS
        </p>

    @if ($errors->any())

    <div class="alert alert-danger mb-3">

        {{ $errors->first() }}

    </div>

    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->

        <div class="mb-3">

            <label class="form-label">
                Email
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>

                <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                required>

            </div>

        </div>

            @error('email')
            <small class="text-danger">
                {{ $message }}
            </small>
            @enderror

        <!-- Password -->

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
                id="password"
                name="password"
                class="form-control"
                placeholder="Masukkan kata sandi"
                required>

                <button
                type="button"
                class="input-group-text password-toggle"
                onclick="togglePassword()">

                    <i
                    class="bi bi-eye"
                    id="eyeIcon">
                    </i>

                </button>

            </div>

        </div>

            @error('password')
            <small class="text-danger">
                {{ $message }}
            </small>
            @enderror

        <!-- Remember -->

        <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="form-check">

            <input
            class="form-check-input"
            type="checkbox"
            name="remember"
            id="remember">

            <label
            class="form-check-label"
            for="remember">

                Ingat Saya

            </label>

        </div>

        <a
        href="{{ route('password.request') }}"
        class="text-decoration-none small">

            Lupa Password?

        </a>

    </div>

        <button
        type="submit"
        class="btn-register">

            Login
            <i class="bi bi-box-arrow-in-right ms-2"></i>

        </button>

    </form>

    <div class="text-center mt-4">

        <small class="text-muted">

            Belum punya akun?

            <a
            href="{{ route('register') }}"
            class="text-decoration-none fw-semibold">

                Daftar di sini

            </a>

        </small>

    </div>

</div>

</div>

<script>

function togglePassword(){

    let password =
        document.getElementById('password');

    let eye =
        document.getElementById('eyeIcon');

    if(password.type === "password"){

        password.type = "text";

        eye.classList.remove("bi-eye");
        eye.classList.add("bi-eye-slash");

    }else{

        password.type = "password";

        eye.classList.remove("bi-eye-slash");
        eye.classList.add("bi-eye");

    }

}

</script>
