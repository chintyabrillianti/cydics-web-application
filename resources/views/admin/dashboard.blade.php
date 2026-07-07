@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Dashboard Admin
            </h2>

            <p class="text-muted mb-0">
                Ringkasan operasional dan aktivitas terkini Yayasan CYDICS.
            </p>
        </div>

        <div>
            <span class="fw-semibold">
                👤 {{ Auth::user()->name }}
            </span>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-title">
                    TOTAL WARGA TERDAFTAR
                </div>

                <div class="stat-number">
                    {{ $totalWarga }}
                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-title">
                    TOTAL MENGAJUKAN
                </div>

                <div class="stat-number">
                    {{ $totalPengajuan }}
                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-title">
                    BELUM MENGAJUKAN
                </div>

                <div class="stat-number">
                    {{ $belumMengajukan }}
                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-title">
                    TOTAL PENERIMA AKTIF
                </div>

                <div class="stat-number">
                    {{ $totalAktif }}
                </div>

            </div>

        </div>

    </div>

    <div class="row g-4" style="align-items:stretch;">

        <div class="col-lg-8">

            <div class="bottom-card">

                <div class="d-flex justify-content-between mb-4">

                    <h5 class="fw-bold">
                        Status Pengajuan Bantuan
                    </h5>

                </div>

                <div class="row align-items-center mt-4">

                    <!-- Chart -->

                    <div class="col-md-4 text-center">

                        <canvas id="pengajuanChart"></canvas>

                    </div>

                    <!-- Status -->

                    <div class="col-md-8">

                        <div class="status-item">
                            <span>🔵 Disetujui</span>
                            <strong>{{ $diterima }}</strong>
                        </div>

                        <div class="status-item">
                            <span>🟡 Menunggu</span>
                            <strong>{{ $menunggu }}</strong>
                        </div>

                        <div class="status-item">
                            <span>🔴 Ditolak</span>
                            <strong>{{ $ditolak }}</strong>
                        </div>


                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="bottom-card">

                <h5 class="fw-bold mb-4">
                    Aktivitas Terkini
                </h5>

                <div class="timeline">

                    @foreach($aktivitas as $item)

                        <div class="timeline-item">

                            <div class="timeline-dot blue"></div>

                            <div class="timeline-content">

                                <small class="text-muted">
                                    {{ $item->created_at->diffForHumans() }}
                                </small>

                                <div class="activity-box">

                                    <h6>Pengajuan Baru</h6>

                                    <p>
                                        {{ $item->nama_lengkap }}
                                        mengajukan bantuan
                                        {{ $item->kategori_bantuan }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>
    <script>

        new Chart(
            document.getElementById('pengajuanChart'),
            {
                type: 'doughnut',

                data: {

                    labels: [
                        'Disetujui',
                        'Menunggu',
                        'Ditolak'
                    ],

                    datasets: [{

                        data: [
                            {{ $diterima }},
                            {{ $menunggu }},
                            {{ $ditolak }}
                        ],

                        backgroundColor: [
                            '#2563eb',
                            '#f59e0b',
                            '#ef4444'
                        ],

                        borderWidth: 0

                    }]
                },

                options: {

                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    cutout: '70%'
                }
            });

    </script>
@endsection