@extends('layouts.warga')

@section('content')

    <div class="form-page">

        <!-- BREADCRUMB -->

        <div class="form-header">

            <div>

                <h1>
                    Formulir Pengajuan Bantuan Duafa Anak
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

                <form id="formDuafaAnak" action="{{ route('pengajuan.duafa-anak.dokumen') }}" method="POST">
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

                        {{-- DOMISILI DEPARIS --}}
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

                        {{-- JUMLAH SAUDARA KANDUNG --}}

                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Jumlah Saudara Kandung Selain Anda*
                            </label>

                            <div class="input-box">

                                <i class="bi bi-people-fill"></i>

                                <input type="number" id="jumlah_saudara" name="jumlah_saudara" min="0" max="10" step="1"
                                    required>

                            </div>

                        </div>
                        {{-- STATUS ORANG TUA / WALI --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Status Orang Tua / Wali *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-people-fill"></i>

                                <select id="status_wali" name="status_wali" required>

                                    <option value="">
                                        Pilih Status
                                    </option>

                                    <option value="Lengkap">
                                        Lengkap
                                    </option>

                                    <option value="Tidak Lengkap">
                                        Tidak Lengkap
                                    </option>

                                </select>

                            </div>

                        </div>
                        <div class="col-12">

                            <div id="warningWali" class="alert alert-danger d-none">

                                Pengajuan Duafa Anak hanya diperuntukkan bagi anak yang masih memiliki kedua orang tua/wali.

                                <br><br>

                                Apabila ayah atau ibu telah meninggal dunia, silakan mengajukan bantuan:

                                <strong>
                                    Yatim, Piatu, atau Yatim-Piatu.
                                </strong>

                            </div>

                        </div>

                        {{-- PENGHASILAN ORANG TUA --}}

                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Penghasilan Orang Tua *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-cash"></i>

                                <select name="penghasilan_orang_tua" required>

                                    <option value="">
                                        Pilih Penghasilan
                                    </option>

                                    <option value="0-<3 Juta">
                                        0 - <3 Juta </option>

                                    <option value="3-<4 Juta">
                                        3 - <4 Juta </option>

                                    <option value="4-<5 Juta">
                                        4 - <5 Juta </option>

                                    <option value=">=5 Juta">
                                        ≥5 Juta
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

                                Kategori bantuan Duafa Anak hanya diperuntukkan bagi pemohon yang belum menikah.

                            </div>

                        </div>

                        {{-- STATUS PEKERJAAN --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Status Pekerjaan *
                            </label>

                            <div class="input-box">

                                <i class="bi bi-briefcase"></i>

                                <select id="status_pekerjaan" name="status_pekerjaan" required>

                                    <option value="">
                                        Pilih Status Pekerjaan
                                    </option>

                                    <option value="Belum Bekerja">
                                        Belum Bekerja
                                    </option>

                                    <option value="Sudah Bekerja">
                                        Sudah Bekerja
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="col-12">

                            <div id="warningKerja" class="alert alert-danger d-none">

                                Bantuan Duafa Anak hanya diperuntukkan bagi pemohon yang belum bekerja.

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
                        <i class="bi bi-people"></i>
                    </div>

                    <h5>
                        Ringkasan Pengajuan
                    </h5>

                    <div class="summary-item">
                        <span>Kategori</span>
                        <strong>Duafa Anak</strong>
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

        // JUMLAH SAUDARA
        const jumlahSaudara = document.getElementById('jumlah_saudara');

        jumlahSaudara.addEventListener('input', function () {

            // Hilangkan semua selain angka
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value !== '') {

                let nilai = parseInt(this.value);

                if (nilai < 0) {
                    this.value = 0;
                }

                if (nilai > 10) {
                    this.value = 10;
                }
            }

        });
        jumlahSaudara.addEventListener('keydown', function (e) {

            if (
                e.key === '-' ||
                e.key === '+' ||
                e.key === 'e' ||
                e.key === 'E'
            ) {
                e.preventDefault();
            }

        });



        //STATUS ORANGTUA/WALI
        document.getElementById('status_wali').addEventListener('change', function () {

            let warning = document.getElementById('warningWali');

            if (this.value == 'Tidak Lengkap') {

                warning.classList.remove('d-none');

            } else {

                warning.classList.add('d-none');

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

        //STATUS PEKERJAAN
        document.getElementById('status_pekerjaan')
            .addEventListener('change', function () {

                let warning =
                    document.getElementById('warningKerja');

                if (this.value === 'Sudah Bekerja') {
                    warning.classList.remove('d-none');
                } else {
                    warning.classList.add('d-none');
                }

            });

        // PILIHAN ALAMAT
        function toggleDomisili() {

            const domisiliYa = document.getElementById('domisili_ya');
            const domisiliTidak = document.getElementById('domisili_tidak');

            const alamatSamaBox = document.getElementById('alamatSamaBox');
            const alamatDomisiliBox = document.getElementById('alamatDomisiliBox');

            const textarea = document.getElementById('alamat_sekarang');
            const btnNext = document.querySelector('.btn-next');

            if (!domisiliYa.checked && !domisiliTidak.checked) {
                return;
            }

            if (domisiliYa.checked) {

                btnNext.disabled = false;

                alamatSamaBox.classList.remove('d-none');

            } else {

                alert('Mohon maaf, bantuan ini hanya diperuntukkan bagi warga yang berdomisili di Deparis Residence.');

                btnNext.disabled = true;

                alamatSamaBox.classList.add('d-none');
                alamatDomisiliBox.classList.add('d-none');

                textarea.value = '';
                textarea.required = false;

                document.querySelectorAll('input[name="alamat_sesuai_kk"]').forEach(radio => {
                    radio.checked = false;
                });
            }
        }
        document.getElementById('domisili_ya')
            .addEventListener('change', toggleDomisili);

        document.getElementById('domisili_tidak')
            .addEventListener('change', toggleDomisili);

        document.querySelectorAll(
            'input[name="alamat_sesuai_kk"]'
        ).forEach(function (radio) {

            radio.addEventListener('change', function () {

                let box = document.getElementById('alamatDomisiliBox');
                let textarea = document.getElementById('alamat_sekarang');

                if (this.value == 'Tidak') {

                    box.classList.remove('d-none');
                    textarea.required = true;

                } else {

                    box.classList.add('d-none');
                    textarea.value = '';
                    textarea.required = false;

                }

            });

        });

        // SUBMIT
        document.getElementById('formDuafaAnak')
            .addEventListener('submit', function (e) {

                let nama =
                    document.getElementById('nama_lengkap').value;

                let hp =
                    document.getElementById('no_hp').value;

                let status =
                    document.getElementById('status_pernikahan').value;

                let pekerjaan =
                    document.getElementById('status_pekerjaan').value;

                let wali =
                    document.getElementById('status_wali').value;

                let jumlahSaudara =
                    parseInt(document.getElementById('jumlah_saudara').value);

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

                if (
                    isNaN(jumlahSaudara) ||
                    jumlahSaudara < 0 ||
                    jumlahSaudara > 10
                ) {
                    e.preventDefault();

                    alert('Jumlah saudara kandung harus diisi antara 0 sampai 10.');

                    return;
                }

                if (wali === 'Tidak Lengkap') {

                    e.preventDefault();

                    alert(
                        'Pengajuan Duafa Anak hanya untuk anak yang masih memiliki kedua orang tua/wali.\n\nSilakan ajukan bantuan Yatim, Piatu, atau Yatim-Piatu sesuai kondisi Anda.'
                    );

                    return;

                }

                if (status === 'Menikah') {
                    e.preventDefault();
                    alert('Pemohon harus belum menikah.');
                    return;
                }

                if (pekerjaan === 'Sudah Bekerja') {

                    e.preventDefault();

                    alert(
                        'Pemohon harus belum bekerja.'
                    );

                    return;
                }

            });
    </script>
@endsection