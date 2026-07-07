@extends('layouts.warga')

@section('content')

    <div class="form-page">

        <!-- BREADCRUMB -->

        <div class="form-header">

            <div>

                <h1>
                    Formulir Pengajuan Bantuan Yatim-Piatu
                </h1>

                <p>
                    Lengkapi seluruh tahapan pengajuan untuk proses verifikasi bantuan.
                    Pastikan data yang dimasukkan sesuai dengan dokumen resmi.
                </p>

            </div>
        </div>

        <!-- STEP FORM -->
        <div class="form-layout">

            {{-- KIRI --}}
            <div class="form-left">

                <div class="step-form">

                    <div class="step active">
                        <div class="step-circle">1</div>
                        <p>Data Diri</p>
                    </div>

                    <div class="step-line active"></div>

                    <div class="step">
                        <div class="step-circle">2</div>
                        <p>Dokumen</p>
                    </div>

                    <div class="step-line"></div>

                    <div class="step">
                        <div class="step-circle">3</div>
                        <p>Konfirmasi</p>
                    </div>

                </div>

                <h5 class="fw-bold mb-4">
                    Isi Data Diri Anda
                </h5>

                @if(session('warning'))
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                        {{ session('warning') }}
                    </div>
                @endif

                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Anda hanya dapat memiliki satu pengajuan aktif. Jika pengajuan tidak sesuai ketentuan dan persyaratan
                    yang ada,
                    Anda tidak dapat mengajukan bantuan lainnya. Harap mengisi form dengan jujur, benar dan teliti.
                </div>

                <form id="formYatimPiatu" action="{{ route('pengajuan.yatim-piatu.dokumen') }}" method="GET">
                    @csrf
                    <div class="row">

                        {{-- NAMA --}}
                        <div class="col-12 mb-4">

                            <label class="form-label">
                                Nama Lengkap *
                            </label>

                            <div class="input-box">
                                <i class="bi bi-person"></i>

                                <input type="text" id="nama_lengkap" name="nama_lengkap"
                                    placeholder="Nama Lengkap (Sesuai KTP)" minlength="3" maxlength="100" required>
                            </div>

                            <small class="text-muted">
                                Minimal 3 karakter, maksimal 100 karakter.
                            </small>

                        </div>

                        {{-- ALAMAT DOMISILI --}}

                        <div class="col-12 mb-4">

                            <label class="form-label">

                                Domisili di Deparis Residence? *

                            </label>

                            <div class="form-check mt-2">

                                <input class="form-check-input" type="radio" name="domisili_deparis" id="domisili_ya"
                                    value="Ya" required>

                                <label class="form-check-label" for="domisili_ya">

                                    Ya

                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="domisili_deparis" id="domisili_tidak"
                                    value="Tidak">

                                <label class="form-check-label" for="domisili_tidak">

                                    Tidak

                                </label>

                            </div>

                        </div>
                        <div class="col-12 mb-4 d-none" id="alamatSamaBox">

                            <label class="form-label">
                                Apakah alamat pada KK sama dengan alamat domisili saat ini? *
                            </label>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="alamat_sesuai_kk" id="alamat_sama"
                                    value="Ya">

                                <label class="form-check-label" for="alamat_sama">
                                    Ya
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="alamat_sesuai_kk" id="alamat_tidak"
                                    value="Tidak">

                                <label class="form-check-label" for="alamat_tidak">
                                    Tidak
                                </label>
                            </div>

                        </div>


                        <div class="col-12 mb-4 d-none" id="alamatDomisiliBox">

                            <label class="form-label">
                                Alamat Domisili Saat Ini *
                            </label>

                            <div class="input-box textarea-box">

                                <i class="bi bi-house"></i>

                                <textarea name="alamat_sekarang" id="alamat_sekarang" rows="3" minlength="10"
                                    maxlength="255" placeholder="Masukkan alamat domisili sekarang"></textarea>

                            </div>

                        </div>

                        {{-- NOMOR HP --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Nomor WhatsApp yang bisa dihubungi*
                            </label>

                            <div class="input-box">

                                <i class="bi bi-telephone"></i>

                                <input type="tel" id="no_hp" name="no_hp" placeholder="87654321234" minlength="10"
                                    maxlength="13" required>

                            </div>

                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Tanggal Lahir *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-calendar"></i>

                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" max="{{ date('Y-m-d') }}"
                                    required>

                            </div>

                        </div>

                        <div class="col-12">

                            <div id="warningUmur" class="alert alert-danger d-none">

                                Usia melebihi 18 tahun sehingga tidak memenuhi syarat bantuan Yatim-Piatu.

                            </div>

                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Jenis Kelamin *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-gender-ambiguous"></i>

                                <select name="jenis_kelamin" required>

                                    <option value="">
                                        Pilih Jenis Kelamin
                                    </option>

                                    <option value="Laki-laki">
                                        Laki-laki
                                    </option>

                                    <option value="Perempuan">
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Status Pernikahan *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-people"></i>

                                <select id="status_pernikahan" name="status_pernikahan" required>

                                    <option value="">
                                        Pilih Status Pernikahan
                                    </option>

                                    <option value="Belum Menikah">
                                        Belum Menikah
                                    </option>

                                    <option value="Menikah">
                                        Menikah
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="col-12">

                            <div id="warningMenikah" class="alert alert-danger d-none">

                                Kategori bantuan Yatim-Piatu hanya diperuntukkan bagi pemohon yang belum menikah.

                            </div>

                        </div>
                        {{-- BUTTON --}}
                        <div class="col-12">

                            <div class="form-footer">

                                <button type="submit" class="btn-next">

                                    Selanjutnya →

                                </button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            {{-- KANAN --}}
            <div class="form-right">

                <div class="summary-card">

                    <div class="summary-image">
                        <i class="bi bi-person-hearts"></i>
                    </div>

                    <h5>
                        Ringkasan Pengajuan
                    </h5>

                    <div class="summary-item">
                        <span>Kategori</span>
                        <strong>Yatim-Piatu</strong>
                    </div>

                    <div class="summary-item">
                        <span>Pengajuan</span>
                        <strong>Baru</strong>
                    </div>

                    <div class="summary-item">
                        <span>Tanggal</span>
                        <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                    </div>

                    <div class="summary-item">
                        <span>Periode</span>
                        <strong>{{ now()->translatedFormat('F Y') }}</strong>
                    </div>

                    <div class="summary-item">
                        <span>Estimasi Verifikasi</span>
                        <strong>1–2 Hari Kerja</strong>
                    </div>

                    <div class="summary-info">
                        Data akan diproses setelah seluruh dokumen berhasil diunggah dan dikirim untuk verifikasi admin.
                    </div>

                </div>

            </div>

        </div> {{-- form-layout --}}

    </div> {{-- form-page --}}

    <script>

        // NAMA HANYA HURUF
        document.getElementById('nama_lengkap')
            .addEventListener('input', function () {

                this.value =
                    this.value.replace(/[^a-zA-Z\s]/g, '');

            });

        // NOMOR HP HANYA ANGKA
        document.getElementById('no_hp')
            .addEventListener('input', function () {

                this.value =
                    this.value.replace(/[^0-9]/g, '');

                if (this.value.startsWith('0')) {
                    this.value =
                        this.value.substring(1);
                }

            });

        // STATUS MENIKAH
        document.getElementById('status_pernikahan')
            .addEventListener('change', function () {

                let warning =
                    document.getElementById('warningMenikah');

                if (this.value === 'Menikah') {
                    warning.classList.remove('d-none');
                } else {
                    warning.classList.add('d-none');
                }

            });

        // CEK UMUR
        document.getElementById('tanggal_lahir')
            .addEventListener('change', function () {

                let lahir = new Date(this.value);
                let sekarang = new Date();

                let umur =
                    sekarang.getFullYear() -
                    lahir.getFullYear();

                let bulan =
                    sekarang.getMonth() -
                    lahir.getMonth();

                if (
                    bulan < 0 ||
                    (
                        bulan === 0 &&
                        sekarang.getDate() < lahir.getDate()
                    )
                ) {
                    umur--;
                }

                let warning =
                    document.getElementById('warningUmur');

                if (lahir > sekarang) {

                    warning.innerHTML =
                        'Tanggal lahir tidak boleh melebihi tanggal hari ini.';

                    warning.classList.remove('d-none');

                }
                else if (umur >= 18) {

                    warning.innerHTML =
                        'Usia telah mencapai 18 tahun sehingga tidak memenuhi syarat bantuan Yatim-Piatu.';

                    warning.classList.remove('d-none');

                }
                else {

                    warning.classList.add('d-none');

                }

            });
        // SUBMIT
        document.getElementById('formYatimPiatu')
            .addEventListener('submit', function (e) {

                let domisili = document.querySelector(
                    'input[name="domisili_deparis"]:checked'
                );

                if (!domisili) {

                    e.preventDefault();

                    alert('Silakan pilih domisili Anda.');

                    return;

                }

                if (domisili.value === 'Ya') {

                    let alamat = document.querySelector('input[name="alamat_sesuai_kk"]:checked');

                    if (domisili.value === "Ya") {

                        if (!alamat) {

                            e.preventDefault();

                            alert('Silakan pilih apakah alamat KK sama dengan domisili.');

                            return;

                        }

                        if (alamat.value === "Tidak" && alamatSekarang.value.trim() === "") {

                            e.preventDefault();

                            alert('Alamat domisili wajib diisi.');

                            return;

                        }

                    }
                }

                let nama =
                    document.getElementById('nama_lengkap').value;

                let hp =
                    document.getElementById('no_hp').value;

                let status =
                    document.getElementById('status_pernikahan').value;

                let tanggal =
                    document.getElementById('tanggal_lahir').value;

                if (!/^[A-Za-z\s]+$/.test(nama)) {
                    e.preventDefault();
                    alert('Nama hanya boleh huruf.');
                    return;
                }

                if (hp.length < 10) {
                    e.preventDefault();
                    alert('Nomor WhatsApp tidak valid.');
                    return;
                }

                let lahir = new Date(tanggal);
                let sekarang = new Date();

                let umur =
                    sekarang.getFullYear() -
                    lahir.getFullYear();

                let bulan =
                    sekarang.getMonth() -
                    lahir.getMonth();

                if (
                    bulan < 0 ||
                    (
                        bulan === 0 &&
                        sekarang.getDate() < lahir.getDate()
                    )
                ) {
                    umur--;
                }

                if (lahir > sekarang) {

                    e.preventDefault();

                    alert(
                        'Tanggal lahir tidak boleh melebihi tanggal hari ini.'
                    );

                    return;
                }

                if (umur >= 18) {

                    e.preventDefault();

                    alert(
                        'Usia telah mencapai 18 tahun sehingga tidak memenuhi syarat bantuan Yatim-Piatu.'
                    );

                    return;
                }

                if (status === 'Menikah') {
                    e.preventDefault();
                    alert('Pemohon harus belum menikah.');
                    return;
                }

            });

        const domisiliYa = document.getElementById('domisili_ya');
        const domisiliTidak = document.getElementById('domisili_tidak');

        const alamatSamaBox = document.getElementById('alamatSamaBox');
        const alamatDomisiliBox = document.getElementById('alamatDomisiliBox');

        const alamatSama = document.getElementById('alamat_sama');
        const alamatTidak = document.getElementById('alamat_tidak');

        const alamatSekarang = document.getElementById('alamat_sekarang');

        domisiliYa.addEventListener('change', () => {

            document.querySelector('.btn-next').disabled = false;

            alamatSama.checked = false;
            alamatTidak.checked = false;

            alamatSekarang.value = "";
            alamatSekarang.required = false;

            alamatDomisiliBox.classList.add('d-none');

            alamatSamaBox.classList.remove('d-none');

        });

        domisiliTidak.addEventListener('change', () => {
            alamatSama.checked = false;
            alamatTidak.checked = false;

            alamatSekarang.value = "";
            alamatSekarang.required = false;

            alert('Mohon maaf, bantuan ini hanya diperuntukkan bagi warga yang berdomisili di Deparis Residence.');

            document.querySelector('.btn-next').disabled = true;

            alamatSamaBox.classList.add('d-none');

            alamatDomisiliBox.classList.add('d-none');

        });

        alamatSama.addEventListener('change', () => {

            alamatDomisiliBox.classList.add('d-none');

            alamatSekarang.required = false;

            alamatSekarang.value = '';

        });

        alamatTidak.addEventListener('change', () => {

            alamatDomisiliBox.classList.remove('d-none');

            alamatSekarang.required = true;

        });
    </script>
@endsection