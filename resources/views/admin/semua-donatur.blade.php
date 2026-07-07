@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Semua Donatur
            </h2>

            <small class="text-muted">

                Total Donatur :
                {{ $donatur->total() }}

            </small>

        </div>

        <a href="{{ route('admin.donasi') }}" class="btn btn-cancel">
            Kembali
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <form method="GET" action="{{ route('admin.semua.donatur') }}">

                <div class="row">

                    <div class="col-md-7">

                        <input type="text" name="cari" class="form-control" placeholder="Cari nama atau nomor HP..."
                            value="{{ request('cari') }}">

                    </div>

                    <div class="col-md-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Aktif"
                                {{ request('status')=='Aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="Tidak Aktif"
                                {{ request('status')=='Tidak Aktif' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-cydics w-100">

                            Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <div class="card shadow-sm mt-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Donasi Terakhir</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($donatur as $item)

                            <tr>

                                <td>{{ $item->name }}</td>

                                <td>{{ $item->phone }}</td>

                                <td>

                                    @if($item->status_donatur == 'Aktif')

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($item->last_donation_at)

                                        {{ \Carbon\Carbon::parse($item->last_donation_at)->translatedFormat('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    {{ $item->created_at->translatedFormat('d M Y') }}

                                </td>

                                <td class="text-center">

    <i class="bi bi-pencil-square text-primary me-3"
        role="button"
        title="Edit Donatur"
        data-bs-toggle="modal"
        data-bs-target="#modalDonatur{{ $item->id }}">
    </i>

    <i class="bi bi-key-fill text-warning"
        role="button"
        title="Reset Password"
        data-bs-toggle="modal"
        data-bs-target="#resetPassword{{ $item->id }}">
    </i>

</td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-4">

                                    Belum ada data donatur.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
        <div class="p-3">

            {{ $donatur->links() }}

        </div>

    </div>

        @foreach($donatur as $item)

        <div class="modal fade" id="modalDonatur{{ $item->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.donasi.update', $item->id) }}" method="POST">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Edit Donatur

                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Nama</label>

                                <input type="text" name="name" class="form-control" minlength="3" maxlength="100" value="{{ $item->name }}"
                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" required>

                            </div>

                            <div class="mb-3">

                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ $item->email }}"
                                    maxlength="100"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>No HP</label>

                                <input type="text" name="phone" class="form-control" minlength="10" maxlength="15" value="{{ $item->phone }}" maxlength="15"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-cydics">

                                Simpan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

    @foreach($donatur as $item)

        <div class="modal fade" id="resetPassword{{ $item->id }}" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="{{ route('admin.donasi.reset.password', $item->id) }}" method="POST" autocomplete="off">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Reset Password

                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Password Baru</label>

                                <input type="password" name="password" class="form-control" minlength="8" maxlength="20"
                                    autocomplete="new-password" required>

                            </div>

                            <div class="mb-3">

                                <label>Konfirmasi Password</label>

                                <input type="password" name="password_confirmation" class="form-control" minlength="8"
                                    maxlength="20" autocomplete="new-password" required>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-cydics">

                                Reset Password

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

@endsection