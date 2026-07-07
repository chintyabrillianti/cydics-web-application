<link rel="stylesheet" href="{{ asset('css/pengajuan-dokumen.css') }}">

@extends('layouts.warga')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="fw-bold mt-2">
            Formulir Pendaftaran Bantuan Yatim-Piatu
        </h1>

        <p class="text-muted">
            Upload dokumen pendukung pengajuan bantuan yatim-piatu.
        </p>

        <div class="document-card mt-4">

            <div class="card-body p-4">

                <!-- STEP -->
                <div class="step-form">
                    <div class="step active">
                        <div class="step-circle">✓</div>
                        <p>Data Diri</p>
                    </div>
                    <div class="step-line active"></div>
                    <div class="step active">
                        <div class="step-circle">2</div>
                        <p>Dokumen</p>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-circle">3</div>
                        <p>Konfirmasi</p>
                    </div>
                </div>
                <hr>

                <h5 class="fw-bold mb-4">
                    Unggah Dokumen Persyaratan
                </h5>

                <form id="formDokumen" action="{{ route('pengajuan.yatim-piatu.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @foreach($data as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <!-- WARNING DI LETAKKAN DI ATAS DI SINI -->
                    <div id="warningDokumen" class="alert alert-danger d-none mb-4"></div>

                    <div class="row g-4 justify-content-center">

                        <!-- AKTA -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="document-item">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <h6 class="document-title">Akta Kelahiran</h6>
                                        <div class="document-desc">Format PDF, JPG, PNG (Opsional Maks. 5MB)</div>
                                    </div>
                                    <i class="bi bi-file-earmark-text fs-3 text-primary"></i>
                                </div>
                                <div class="upload-box">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <p>Tarik dan letakkan file di sini <br> atau klik untuk mencari</p>
                                    <input type="file" name="file_akta_kelahiran" id="file_akta" hidden>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('file_akta').click()">
                                        Pilih File
                                    </button>
                                    <div id="preview_akta" class="file-preview d-none"></div>
                                    <small id="warning_akta" class="text-warning-upload d-none">File sebelumnya telah
                                        diganti.</small>
                                </div>
                            </div>
                        </div>

                        <!-- KK -->
                        <div class="col-lg-6">
                            <div class="document-item">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <h6 class="document-title">Kartu Keluarga</h6>
                                        <div class="document-desc">Format PDF, JPG, PNG (Maks. 5MB)</div>
                                    </div>
                                    <i class="bi bi-people fs-3 text-primary"></i>
                                </div>
                                <div class="upload-box">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <p>Tarik dan letakkan file di sini <br> atau klik untuk mencari</p>
                                    <input type="file" name="file_kk" id="file_kk" hidden>
                                    <button type="button" onclick="document.getElementById('file_kk').click()"
                                        class="btn btn-outline-primary btn-sm mt-3">
                                        Pilih File
                                    </button>
                                    <div id="preview_kk" class="file-preview d-none"></div>
                                    <small id="warning_kk" class="text-warning-upload d-none">File sebelumnya telah
                                        diganti.</small>
                                </div>
                            </div>
                        </div>

                        <!-- KEMATIAN ORANGTUA -->
                        <div class="col-lg-6">
                            <div class="document-item">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <h6 class="document-title">Surat Kematian Orang Tua</h6>
                                        <div class="document-desc">Format PDF, JPG, PNG (Maks. 5MB)</div>
                                    </div>
                                    <i class="bi bi-file-earmark-medical fs-3 text-primary"></i>
                                </div>
                                <div class="upload-box">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    Tarik dan letakkan file di sini <br> atau klik untuk mencari
                                    <input type="file" name="file_kematian_orangtua" id="file_kematian_orangtua" hidden>
                                    <button type="button" onclick="document.getElementById('file_kematian_orangtua').click()"
                                        class="btn btn-outline-primary btn-sm mt-3">
                                        Pilih File
                                    </button>
                                    <div id="preview_kematian" class="file-preview d-none"></div>
                                    <small id="warning_kematian" class="text-warning-upload d-none">File sebelumnya telah
                                        diganti.</small>
                                </div>
                            </div>
                        </div>

                        <!-- BELUM MENIKAH -->
                        @php
                            $umur = \Carbon\Carbon::parse($data['tanggal_lahir'])->age;
                        @endphp

                        @if($umur >= 15)

                            <div class="col-lg-6">

                                <div class="document-item">

                                    <div class="d-flex justify-content-between mb-3">

                                        <div>

                                            <h6 class="document-title">
                                                Surat Belum Menikah
                                            </h6>

                                            <div class="document-desc">
                                                Format PDF, JPG, PNG (Opsional Maks. 5MB)
                                            </div>

                                        </div>

                                        <i class="bi bi-file-earmark-check fs-3 text-primary"></i>

                                    </div>

                                    <div class="upload-box">

                                        <i class="bi bi-cloud-upload upload-icon"></i>

                                        Tarik dan letakkan file di sini
                                        <br>
                                        atau klik untuk mencari

                                        <input type="file" name="file_tidak_menikah" id="file_tidak_menikah" hidden>

                                        <button type="button" onclick="document.getElementById('file_tidak_menikah').click()"
                                            class="btn btn-outline-primary btn-sm mt-3">

                                            Pilih File

                                        </button>

                                        <div id="preview_menikah" class="file-preview d-none">
                                        </div>

                                        <small id="warning_menikah" class="text-warning-upload d-none">

                                            File sebelumnya telah diganti.

                                        </small>

                                    </div>

                                </div>

                            </div>

                        @endif

                        <!-- SURAT DOMISILI -->
                        @if(($data['alamat_sesuai_kk'] ?? null) == 'Tidak')
                            <div class="col-lg-6">
                                <div class="document-item">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <h6 class="document-title">Surat Domisili</h6>
                                            <div class="document-desc">
                                                Wajib jika alamat pada KK berbeda dengan alamat domisili saat ini.
                                            </div>
                                        </div>
                                        <i class="bi bi-geo-alt fs-3 text-primary"></i>
                                    </div>
                                    <div class="upload-box">
                                        <i class="bi bi-cloud-upload upload-icon"></i>
                                        Tarik dan letakkan file di sini <br> atau klik untuk mencari
                                        <input type="file" name="file_domisili" id="file_domisili" hidden>
                                        <button type="button" onclick="document.getElementById('file_domisili').click()"
                                            class="btn btn-outline-primary btn-sm mt-3">
                                            Pilih File
                                        </button>
                                        <div id="preview_domisili" class="file-preview d-none"></div>
                                        <small id="warning_domisili" class="text-warning-upload d-none">File sebelumnya telah
                                            diganti.</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="bottom-action d-flex justify-content-between mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Kirim Pengajuan</button>
                    </div>

                </form>

                <script>
                    // Batasi format pilihan file saat memilih dokumen
                    document.querySelectorAll('input[type="file"]').forEach(input => {
                        input.setAttribute('accept', '.jpg,.jpeg,.png,.pdf');
                    });

                    document.getElementById('formDokumen').addEventListener('submit', function (e) {
                        let warning = document.getElementById('warningDokumen');
                        warning.classList.add('d-none');
                        warning.innerHTML = '';

                        if (document.getElementById('file_kk').files.length === 0) {
                            e.preventDefault();
                            warning.classList.remove('d-none');
                            warning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Kartu Keluarga wajib diupload.';
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                            return;
                        }

                        if (document.getElementById('file_kematian_orangtua').files.length === 0) {
                            e.preventDefault();
                            warning.classList.remove('d-none');
                            warning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Surat Kematian Orang Tua wajib diupload.';
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                            return;
                        }

                        @if(($data['alamat_sesuai_kk'] ?? null) == 'Tidak')
                            if (document.getElementById('file_domisili').files.length === 0) {
                                e.preventDefault();
                                warning.classList.remove('d-none');
                                warning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Surat Domisili wajib diupload.';
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                                return;
                            }
                        @endif
                                            });

                    function previewFile(inputId, previewId, warningId) {
                        const input = document.getElementById(inputId);
                        const preview = document.getElementById(previewId);
                        const warning = document.getElementById(warningId);
                        const warningDokumen = document.getElementById('warningDokumen');

                        if (!input) return;

                        input.addEventListener('change', function () {
                            if (this.files.length === 0) return;

                            const file = this.files[0];
                            const extension = file.name.split('.').pop().toLowerCase();
                            const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                            const maxSize = 5 * 1024 * 1024; // 5MB

                            // Validasi format & ukuran file secara real-time
                            if (!allowedExtensions.includes(extension) || file.size > maxSize) {
                                this.value = ''; // Reset file jika salah
                                preview.classList.add('d-none');
                                preview.innerHTML = '';
                                if (warning) warning.classList.add('d-none');

                                warningDokumen.classList.remove('d-none');
                                if (!allowedExtensions.includes(extension)) {
                                    warningDokumen.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mengunggah "${file.name}". Format file tidak didukung! Hanya JPG, JPEG, PNG, dan PDF yang diperbolehkan.`;
                                } else {
                                    warningDokumen.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mengunggah "${file.name}". Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.`;
                                }

                                window.scrollTo({ top: 0, behavior: 'smooth' });
                                return;
                            }

                            // Jika file valid, hilangkan warning
                            warningDokumen.classList.add('d-none');
                            warningDokumen.innerHTML = '';

                            if (preview.innerHTML.trim() !== '') {
                                if (warning) warning.classList.remove('d-none');
                            } else {
                                if (warning) warning.classList.add('d-none');
                            }

                            preview.classList.remove('d-none');
                            preview.innerHTML = `<i class="bi bi-file-earmark-text"></i> ${file.name}`;
                        });
                    }

                    previewFile('file_akta', 'preview_akta', 'warning_akta');
                    previewFile('file_kk', 'preview_kk', 'warning_kk');
                    previewFile('file_kematian_orangtua', 'preview_kematian', 'warning_kematian');
                    if (document.getElementById('file_tidak_menikah')) {
                        previewFile(
                            'file_tidak_menikah',
                            'preview_menikah',
                            'warning_menikah'
                        );
                    }

                    @if(($data['alamat_sesuai_kk'] ?? null)=='Tidak')
                        previewFile('file_domisili', 'preview_domisili', 'warning_domisili');
                    @endif
                </script>
@endsection