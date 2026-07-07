@extends('layouts.warga')

@section('content')

    <h2 class="fw-bold mb-2">
        Perbaikan Dokumen
    </h2>

    <p class="text-muted mb-4">
        Harap memperbaiki dokumen sesuai catatan admin.
    </p>

    <div class="alert info-alert border-0 shadow-sm">

        <strong>
            <i class="bi bi-info-circle-fill me-2"></i>
            Informasi Perbaikan
        </strong>

        <p class="mt-3 mb-2">
            Perbaikan dokumen dapat dilakukan maksimal
            <strong>2 kali.</strong>
        </p>

        <p class="mb-2">
            Apabila dokumen yang diunggah masih belum sesuai setelah batas revisi tersebut,
            pengajuan akan <strong>ditolak.</strong>
        </p>

        <p class="mb-0">
            Pastikan seluruh dokumen telah diperbaiki sesuai catatan admin sebelum mengirim kembali.
        </p>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Catatan Admin
            </h5>

            <div class="alert alert-warning">

                {{ $pengajuan->catatan_admin }}

            </div>

            <div class="row">

                <div class="col-md-6">

                    <small class="text-muted">
                        Kategori Bantuan
                    </small>

                    <h6>
                        {{ $pengajuan->kategori_bantuan }}
                    </h6>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">
                        Revisi Ke
                    </small>

                    <h6>
                        {{ $pengajuan->jumlah_perbaikan }} dari 2
                    </h6>

                </div>

            </div>

        </div>

    </div>

    <form action="{{ route('perbaikan.store') }}" method="POST" enctype="multipart/form-data" id="form-perbaikan">

        @csrf

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h5 class="fw-bold mb-4">
                    Upload Dokumen Baru
                </h5>

                {{-- DUAFA LANSIA --}}
                @if($pengajuan->kategori_bantuan == 'Duafa Lansia')

                    <div class="mb-3">
                        <label>Kartu Keluarga</label>
                        <input type="file" name="file_kk" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Keterangan Tidak Mampu</label>
                        <input type="file" name="file_sktm" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Bukti Kondisi Rumah</label>
                        <input type="file" name="file_bukti_rumah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Kematian Pasangan</label>
                        <input type="file" name="file_kematian_pasangan" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Bukti Perceraian</label>
                        <input type="file" name="file_bukti_cerai" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Domisili</label>
                        <input type="file" name="file_domisili" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                @endif


                {{-- DUAFA ANAK --}}
                @if($pengajuan->kategori_bantuan == 'Duafa Anak')

                    <div class="mb-3">
                        <label>Akta Kelahiran</label>
                        <input type="file" name="file_akta_kelahiran" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Kartu Keluarga</label>
                        <input type="file" name="file_kk" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Keterangan Tidak Mampu</label>
                        <input type="file" name="file_sktm" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Belum Menikah</label>
                        <input type="file" name="file_tidak_menikah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Tidak Bekerja</label>
                        <input type="file" name="file_tidak_bekerja" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Domisili</label>
                        <input type="file" name="file_domisili" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                @endif


                {{-- YATIM --}}
                @if($pengajuan->kategori_bantuan == 'Yatim')

                    <div class="mb-3">
                        <label>Akta Kelahiran</label>
                        <input type="file" name="file_akta_kelahiran" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Kartu Keluarga</label>
                        <input type="file" name="file_kk" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Kematian Ayah</label>
                        <input type="file" name="file_kematian_ayah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Belum Menikah</label>
                        <input type="file" name="file_tidak_menikah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Domisili</label>
                        <input type="file" name="file_domisili" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                @endif


                {{-- PIATU --}}
                @if($pengajuan->kategori_bantuan == 'Piatu')

                    <div class="mb-3">
                        <label>Akta Kelahiran</label>
                        <input type="file" name="file_akta_kelahiran" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Kartu Keluarga</label>
                        <input type="file" name="file_kk" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Kematian Ibu</label>
                        <input type="file" name="file_kematian_ibu" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Belum Menikah</label>
                        <input type="file" name="file_tidak_menikah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Domisili</label>
                        <input type="file" name="file_domisili" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                @endif


                {{-- YATIM PIATU --}}
                @if($pengajuan->kategori_bantuan == 'Yatim-Piatu')

                    <div class="mb-3">
                        <label>Akta Kelahiran</label>
                        <input type="file" name="file_akta_kelahiran" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Kartu Keluarga</label>
                        <input type="file" name="file_kk" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Kematian Orang Tua</label>
                        <input type="file" name="file_kematian_orangtua" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Belum Menikah</label>
                        <input type="file" name="file_tidak_menikah" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label>Surat Domisili</label>
                        <input type="file" name="file_domisili" class="form-control" onchange="validateFile(this)">
                        <div class="text-danger small mt-1 error-msg" style="display: none;"></div>
                    </div>

                @endif



                <button type="submit" id="btn-submit" class="btn btn-warning">
                    Kirim Perbaikan
                </button>

            </div>

        </div>

    </form>

    <script>
        // Daftarkan pembatasan input file secara otomatis saat halaman dibuka
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.setAttribute('accept', '.jpg,.jpeg,.png,.pdf');
        });

        function validateFile(input) {
            const errorDiv = input.parentNode.querySelector('.error-msg');
            const submitBtn = document.getElementById('btn-submit');

            if (input.files.length > 0) {
                const file = input.files[0];
                const extension = file.name.split('.').pop().toLowerCase();
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                const maxSize = 5 * 1024 * 1024; // 5MB dalam bytes

                // Cek ekstensi file
                if (!allowedExtensions.includes(extension)) {
                    alert('Format file tidak didukung! Hanya diperbolehkan format: .jpg, .jpeg, .png, .pdf');
                    errorDiv.textContent = 'Format file harus berupa JPG, JPEG, PNG, atau PDF.';
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    input.value = ''; // Reset input agar file tidak terpilih
                    checkFormValidity();
                    return;
                }

                // Cek ukuran file
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
                    errorDiv.textContent = 'Ukuran file maksimal adalah 5 MB.';
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    input.value = ''; // Reset input agar file tidak terpilih
                    checkFormValidity();
                    return;
                }
            }

            // Jika file lolos validasi
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
            checkFormValidity();
        }

        function checkFormValidity() {
            const submitBtn = document.getElementById('btn-submit');
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let isValid = true;

            fileInputs.forEach(input => {
                if (input.classList.contains('is-invalid')) {
                    isValid = false;
                }
            });

            if (isValid) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'true');
            }
        }
    </script>

@endsection