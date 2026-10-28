<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\PembaruanDokumenController;
use App\Http\Controllers\Auth\PasswordController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'warga'])
    ->name('dashboard');

Route::get(
    '/kegiatan',
    [HomeController::class, 'kegiatan']
)->name('kegiatan');

Route::middleware('auth')->group(function () {

    Route::get('/pengajuan', [PengajuanController::class, 'index'])
        ->name('pengajuan.index');

    Route::get(
        '/profil',
        [ProfileController::class, 'warga']
    )->name('warga.profile');

    Route::post('/profil/update', [ProfileController::class, 'updateWarga'])
        ->name('warga.profil.update');

    Route::post('/profil/foto/update', [ProfileController::class, 'updateFotoWarga'])
        ->name('warga.foto.update');

    Route::get('/pengajuan/yatim', [PengajuanController::class, 'yatim'])
        ->name('pengajuan.yatim');

    Route::get(
        '/pengajuan/yatim/dokumen',
        [PengajuanController::class, 'yatimDokumen']
    )->name('pengajuan.yatim.dokumen');

    Route::post(
        '/pengajuan/yatim/store',
        [PengajuanController::class, 'storeYatim']
    )->name('pengajuan.yatim.store');

    Route::get(
        '/pengajuan/piatu',
        [PengajuanController::class, 'piatu']
    )->name('pengajuan.piatu');

    Route::get(
        '/pengajuan/piatu/dokumen',
        [PengajuanController::class, 'piatuDokumen']
    )->name('pengajuan.piatu.dokumen');

    Route::post(
        '/pengajuan/piatu/store',
        [PengajuanController::class, 'storePiatu']
    )->name('pengajuan.piatu.store');

    Route::get(
        '/pengajuan/yatim-piatu',
        [PengajuanController::class, 'yatimPiatu']
    )->name('pengajuan.yatim-piatu');

    Route::get(
        '/pengajuan/yatim-piatu/dokumen',
        [PengajuanController::class, 'yatimPiatuDokumen']
    )->name('pengajuan.yatim-piatu.dokumen');

    Route::post(
        '/pengajuan/yatim-piatu/store',
        [PengajuanController::class, 'storeYatimPiatu']
    )->name('pengajuan.yatim-piatu.store');

    Route::get(
        '/pengajuan/duafa-anak',
        [PengajuanController::class, 'duafaAnak']
    )->name('pengajuan.duafa-anak');

    Route::post('/pengajuan/duafa-anak/dokumen', [PengajuanController::class, 'duafaAnakDokumen'])
        ->name('pengajuan.duafa-anak.dokumen');

    Route::post(
        '/pengajuan/duafa-anak/store',
        [PengajuanController::class, 'storeDuafaAnak']
    )->name('pengajuan.duafa-anak.store');

    Route::get(
        '/pengajuan/duafa-lansia',
        [PengajuanController::class, 'duafaLansia']
    )->name('pengajuan.duafa-lansia');

    Route::get(
        '/pengajuan/duafa-lansia/dokumen',
        [PengajuanController::class, 'duafaLansiaDokumen']
    )->name('pengajuan.duafa-lansia.dokumen');

    Route::post(
        '/pengajuan/duafa-lansia/store',
        [PengajuanController::class, 'storeDuafaLansia']
    )->name('pengajuan.duafa-lansia.store');

    Route::get(
        '/pembaruan-dokumen',
        [PembaruanDokumenController::class, 'index']
    )->name('pembaruan.dokumen');

    Route::get(
        '/perbaikan-dokumen',
        [PengajuanController::class, 'perbaikanDokumen']
    )->name('perbaikan.dokumen');

    Route::post(
        '/perbaikan-dokumen',
        [PengajuanController::class, 'storePerbaikan']
    )->name('perbaikan.store');

    Route::get(
        '/perubahan-kategori/{kategori}',
        [
            PengajuanController::class,
            'perubahanKategori'
        ]
    )->name('perubahan.kategori');

    Route::post(
        '/pembaruan-dokumen',
        [PembaruanDokumenController::class, 'store']
    )->name('pembaruan.store');


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get(
        '/admin/dashboard',
        [AdminController::class, 'dashboard']
    )->name('admin.dashboard');

    Route::get(
        '/admin/website',
        [AdminController::class, 'website']
    )->name('admin.website');

    Route::post(
        '/admin/website/update',
        [AdminController::class, 'updateWebsite']
    )->name('admin.website.update');

    Route::post(
        '/admin/program/store',
        [AdminController::class, 'storeProgram']
    )->name('admin.program.store');

    Route::post(
        '/admin/program/{id}/status',
        [AdminController::class, 'statusProgram']
    )->name('admin.program.status');

    Route::post(
        '/admin/kegiatan/store',
        [AdminController::class, 'storeKegiatan']
    )->name('admin.kegiatan.store');

    Route::put(
        '/admin/kegiatan/{id}',
        [AdminController::class, 'updateKegiatan']
    )->name('admin.kegiatan.update');

    Route::delete(
        '/admin/kegiatan/{id}',
        [AdminController::class, 'hapusKegiatan']
    )->name('admin.kegiatan.hapus');

    Route::delete(
        '/admin/program/{id}',
        [AdminController::class, 'hapusProgram']
    )->name('admin.program.hapus');

    Route::put(
        '/admin/program/{id}',
        [AdminController::class, 'updateProgram']
    )->name('admin.program.update');

    Route::get(
        '/admin/pengajuan',
        [AdminController::class, 'pengajuan']
    )->name('admin.pengajuan');

    Route::get(
        '/admin/pengajuan/{id}',
        [AdminController::class, 'detailPengajuan']
    )->name('admin.pengajuan.detail');

    Route::post(
        '/admin/pengajuan/{id}/terima',
        [AdminController::class, 'terima']
    )->name('admin.pengajuan.terima');

    Route::post(
        '/admin/pengajuan/{id}/perbaikan',
        [AdminController::class, 'perbaikan']
    )->name('admin.pengajuan.perbaikan');

    Route::post(
        '/admin/pengajuan/{id}/tolak',
        [AdminController::class, 'tolak']
    )->name('admin.pengajuan.tolak');

    Route::post(
        '/admin/pembaruan/{id}/terima',
        [AdminController::class, 'terimaPembaruan']
    )->name('admin.pembaruan.terima');

    Route::post(
        '/admin/pembaruan/{id}/tolak',
        [AdminController::class, 'tolakPembaruan']
    )->name('admin.pembaruan.tolak');

    Route::post(
    '/admin/pengajuan/{id}/selesai-meninggal',
    [AdminController::class, 'selesaiMeninggal']
)->name('admin.pengajuan.selesai.meninggal');

    Route::get(
        '/admin/kegiatan',
        [AdminController::class, 'semuaKegiatan']
    )->name('admin.semua.kegiatan');

    Route::get(
        '/admin/arsip-pengajuan',
        [AdminController::class, 'arsipPengajuan']
    )->name('admin.arsip');

    Route::get(
        '/admin/donasi',
        [AdminController::class, 'donasi']
    )->name('admin.donasi');

    Route::put(
        '/admin/donasi/verifikasi/{id}',
        [AdminController::class, 'verifikasiDonasi']
    )->name('admin.donasi.verifikasi');

    Route::get(
        '/admin/donatur',
        [AdminController::class, 'semuaDonatur']
    )->name('admin.semua.donatur');

    Route::post(
        '/admin/donasi/store',
        [AdminController::class, 'storeDonatur']
    )
        ->name('admin.donasi.store');

    Route::post(
        '/admin/donasi/update/{id}',
        [AdminController::class, 'updateDonatur']
    )
        ->name('admin.donasi.update');

    Route::post(
        '/admin/donasi/reset-password/{id}',
        [AdminController::class, 'resetPasswordDonatur']
    )->name('admin.donasi.reset.password');

    Route::get(
        '/admin/laporan',
        [AdminController::class, 'laporan']
    )->name('admin.laporan');

    Route::get(
        '/admin/laporan-tahunan',
        [AdminController::class, 'laporanTahunan']
    )->name('admin.laporan.tahunan');

    Route::post(
        '/admin/laporan-tahunan',
        [AdminController::class, 'storeLaporanTahunan']
    )->name('admin.laporan.tahunan.store');

    Route::post(
        '/admin/laporan-tahunan/{id}/publish',
        [AdminController::class, 'publishLaporanTahunan']
    )->name('admin.laporan.tahunan.publish');

    Route::post(
        '/admin/laporan-tahunan/{id}/update',
        [AdminController::class, 'updateLaporanTahunan']
    )->name('admin.laporan.tahunan.update');

    Route::delete(
        '/admin/laporan-tahunan/{id}',
        [AdminController::class, 'hapusLaporanTahunan']
    )->name('admin.laporan.tahunan.hapus');

    Route::post(
        '/admin/laporan',
        [AdminController::class, 'storeLaporan']
    )->name('admin.laporan.store');

    Route::post(
        '/admin/laporan/{id}/publish',
        [AdminController::class, 'publishLaporan']
    )->name('admin.laporan.publish');

    Route::delete(
        '/admin/laporan/{id}',
        [AdminController::class, 'hapusLaporan']
    )->name('admin.laporan.hapus');

    Route::post(
        '/admin/laporan/{id}/update',
        [AdminController::class, 'updateLaporan']
    )->name('admin.laporan.update');

});




Route::middleware([
    'auth',
    'donatur'
])->group(function () {

    Route::get(
        '/donatur/dashboard',
        [DonaturController::class, 'dashboard']
    )->name('donatur.dashboard');

    Route::get(
        '/donatur/donasi',
        [DonaturController::class, 'donasi']
    )->name('donatur.donasi');

    Route::post(
        '/donatur/donasi/store',
        [DonaturController::class, 'storeDonasi']
    )->name('donatur.donasi.store');

    Route::get(
        '/donatur/riwayat',
        [DonaturController::class, 'riwayat']
    )->name('donatur.riwayat');

    Route::get(
        '/donatur/laporan',
        [DonaturController::class, 'laporan']
    )->name('donatur.laporan');

    Route::get(
        '/donatur/laporan-bulanan',
        [DonaturController::class, 'laporanBulanan']
    )->name('donatur.laporan.bulanan');

    Route::get(
        '/donatur/laporan-tahunan',
        [DonaturController::class, 'laporanTahunanSemua']
    )->name('donatur.laporan.tahunan');

    Route::get(
        '/donatur/profil',
        [DonaturController::class, 'profil']
    )->name('donatur.profil');

    Route::post(
        '/donatur/profil/update',
        [DonaturController::class, 'update']
    )->name('donatur.profil.update');

    Route::post(
        '/donatur/foto/update',
        [DonaturController::class, 'updateFoto']
    )->name('donatur.foto.update');

    Route::post(
        '/donatur/password/update',
        [DonaturController::class, 'updatePassword']
    )->name('donatur.password.update');

});
    Route::view('/syarat-ketentuan-registrasi', 'auth.terms')
    ->name('terms');

require __DIR__ . '/auth.php';