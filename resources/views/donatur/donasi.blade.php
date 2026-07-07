@extends('layouts.donatur')

@section('content')
    <div class="mb-4">

        <h2 class="page-title">Donasi</h2>

        <p class="donasi-subtitle">
            Pilih program yang ingin didukung dan lakukan donasi untuk membantu masyarakat yang membutuhkan.
        </p>

    </div>

    <div class="donasi-layout">

        <!-- KIRI -->
        <div class="program-section">

            <div class="donasi-card">

                <h4 class="section-title">
                    Program Bantuan
                </h4>

                @foreach($programs as $program)

                    <div class="program-item">

                        <img src="{{ asset('storage/' . $program->foto) }}" class="program-image">

                        <div class="program-info">

                            <span class="program-badge">

                                Program Donasi

                            </span>

                            <h6>

                                {{ $program->nama }}

                            </h6>

                            <p>

                                {{ $program->deskripsi }}

                            </p>

                        </div>

                        <button type="button" class="btn-pilih pilih-program" data-id="{{ $program->id }}"
                            data-nama="{{ $program->nama }}">

                            Pilih

                        </button>

                    </div>

                @endforeach

            </div>

        </div>

        <!-- KANAN -->
        <div class="form-section">
            <form id="formDonasi" action="{{ route('donatur.donasi.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <input type="hidden" name="program_id" id="program_id">

                <div class="donasi-card">

                    <h4 class="form-title">
                        ♡ Form Donasi
                    </h4>

                    <label class="mb-2">
                        Program yang Dipilih
                    </label>

                    <input type="text" id="program_nama" class="form-control mb-3"
                        placeholder="Silakan pilih program di sebelah kiri" readonly>

                    <label class="mb-2">
                        Nama Donatur
                    </label>

                    <input type="text" class="form-control mb-3" value="{{ auth()->user()->name }}" readonly>

                    <label class="mb-2 mt-3">

                        Upload Bukti Transfer

                    </label>

                    <input type="file" name="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>

                    @error('bukti_transfer')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="upload-note">
                        Format: JPG, JPEG, PNG, WEBP • Maksimal 2 MB
                    </small>

                    <label class="mb-2 mt-3">

                        Catatan (Opsional)

                    </label>

                    <textarea name="catatan" class="form-control" rows="3"
                        placeholder="Contoh: Semoga bermanfaat"></textarea>
                    <div id="warningDonasi" class="alert alert-danger d-none mt-3 mb-3">
                    </div>

                    @error('program_id')
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="button-group">

                        <button type="reset" class="btn-reset">
                            Reset Form
                        </button>

                        <button type="submit" id="btnSubmitDonasi" class="btn-submit">

                            Kirim Bukti Donasi →

                        </button>

                    </div>

                </div>
            </form>

            <script>

                const form = document.getElementById('formDonasi');
                const programInput = document.getElementById('program_id');
                const programNama = document.getElementById('program_nama');
                const warningBox = document.getElementById('warningDonasi');
                const btnSubmit = document.getElementById('btnSubmitDonasi');

                document.querySelectorAll('.pilih-program').forEach(btn => {

                    btn.addEventListener('click', function () {

                        // Reset semua tombol
                        document.querySelectorAll('.pilih-program').forEach(item => {
                            item.classList.remove('active');
                            item.innerHTML = 'Pilih';
                        });

                        // Reset semua card
                        document.querySelectorAll('.program-item').forEach(card => {
                            card.classList.remove('active');
                        });

                        // Aktifkan card yang dipilih
                        this.classList.add('active');
                        this.innerHTML = '✓ Dipilih';

                        this.closest('.program-item').classList.add('active');

                        // Isi form
                        programInput.value = this.dataset.id;
                        programNama.value = this.dataset.nama;

                        // Hilangkan warning
                        warningBox.classList.add('d-none');
                        warningBox.innerHTML = '';

                    });

                });

                form.addEventListener('submit', function (e) {

                    warningBox.classList.add('d-none');
                    warningBox.innerHTML = '';

                    // Validasi program
                    const buktiTransfer = document.querySelector('input[name="bukti_transfer"]');

                    // Validasi program
                    if (programInput.value === '') {

                        e.preventDefault();

                        warningBox.innerHTML =
                            '<i class="bi bi-exclamation-circle-fill me-2"></i>Silakan pilih salah satu program bantuan terlebih dahulu.';

                        warningBox.classList.remove('d-none');

                        return;
                    }

                    // Validasi bukti transfer
                    if (buktiTransfer.files.length === 0) {

                        e.preventDefault();

                        warningBox.innerHTML =
                            '<i class="bi bi-exclamation-circle-fill me-2"></i>Silakan unggah bukti transfer terlebih dahulu.';

                        warningBox.classList.remove('d-none');

                        return;
                    }

                    btnSubmit.disabled = true;
                    btnSubmit.style.pointerEvents = 'none';
                    btnSubmit.innerHTML = 'Mengirim...';

                });

            </script>
        </div>


    </div>
@endsection