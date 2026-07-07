<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $this->resetStatusPerbaikan();
        $this->resetStatusPembaruan();
        /*
        |--------------------------------------------------------------------------
        | 1. AMBIL DATA AWAL PENGAJUAN
        |--------------------------------------------------------------------------
        */

        // Bantuan Aktif
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->latest()
            ->first();

        // Pengajuan Bantuan Baru
        $pengajuanBaru = Pengajuan::where('user_id', auth()->id())
            ->whereNull('kategori_sebelumnya')
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Ditolak'
            ])
            ->latest()
            ->first();

        // Perubahan Kategori
        $perubahan = Pengajuan::where('user_id', auth()->id())
            ->whereNotNull('kategori_sebelumnya')
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Ditolak'
            ])
            ->latest()
            ->first();
        /*
        |--------------------------------------------------------------------------
        | 3. AMBIL DATA RIWAYAT TERBARU (SETELAH UPDATE STATUS SELESAI)
        |--------------------------------------------------------------------------
        */
        $pembaruanDitolak = Pengajuan::where('user_id', auth()->id())
            ->where('status_pembaruan', 'Ditolak')
            ->latest()
            ->first();
        $riwayat = Pengajuan::where('user_id', auth()->id())
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 4. HITUNG SISA WAKTU & TANGGAL PENGAJUAN ULANG
        |--------------------------------------------------------------------------
        */
        $notifPembaruan = false;
        $sisaHari = null;
        $pembaruanMenunggu = false;

        if (
            $pengajuan &&
            in_array($pengajuan->kategori_bantuan, [
                'Duafa Anak',
                'Duafa Lansia'
            ]) &&
            $pengajuan->status_penerima == 'Aktif'
        ) {

            $tanggalNotif = Carbon::parse($pengajuan->tanggal_pembaruan_dokumen);

            // Sudah mengirim pembaruan
            if ($pengajuan->status_pembaruan == 'Menunggu') {

                $pembaruanMenunggu = true;

            } else {

                // Belum mengirim pembaruan
                if (now()->greaterThanOrEqualTo($tanggalNotif)) {

                    $notifPembaruan = true;

                    if (Carbon::today()->gt(Carbon::parse($pengajuan->tanggal_selesai))) {

                        $sisaHari = 'Expired';

                    } else {

                        $sisaHari = Carbon::today()->diffInDays(
                            Carbon::parse($pengajuan->tanggal_selesai)
                        ) . ' hari';

                    }

                }

            }

        }

        $sisaPerbaikan = null;
        $sisaPerbaikanKategori = null;

        if ($perubahan && in_array($perubahan->status_pengajuan, ['Perbaikan', 'Menunggu'])) {
            $sisaPerbaikanKategori = (int) max(0, now()->startOfDay()->diffInDays(Carbon::parse($perubahan->tanggal_selesai), false));
        }

        if ($pengajuanBaru && in_array($pengajuanBaru->status_pengajuan, ['Perbaikan', 'Menunggu'])) {
            $sisaPerbaikan = (int) max(0, now()->startOfDay()->diffInDays(Carbon::parse($pengajuanBaru->tanggal_selesai), false));
        }

        // Untuk pengajuan bantuan baru
        $sisaTungguPengajuan = null;
        $tanggalPengajuan = null;

        // Untuk perubahan kategori
        $sisaTungguPerubahan = null;
        $tanggalPerubahan = null;

        if (
            $pengajuanBaru &&
            $pengajuanBaru->tanggal_boleh_ajukan &&
            now()->lt($pengajuanBaru->tanggal_boleh_ajukan)
        ) {
            $sisaTungguPengajuan = max(
                0,
                now()->startOfDay()->diffInDays(
                    Carbon::parse($pengajuanBaru->tanggal_boleh_ajukan),
                    false
                )
            );
            $tanggalPengajuan = Carbon::parse($pengajuanBaru->tanggal_boleh_ajukan)->translatedFormat('d F Y');
        }

        if (
            $perubahan &&
            $perubahan->status_pengajuan == 'Ditolak' &&
            $perubahan->tanggal_boleh_ajukan &&
            now()->lt($perubahan->tanggal_boleh_ajukan)
        ) {
            $sisaTungguPerubahan = max(
                0,
                now()->startOfDay()->diffInDays(
                    Carbon::parse($perubahan->tanggal_boleh_ajukan),
                    false
                )
            );
            $tanggalPerubahan = Carbon::parse($perubahan->tanggal_boleh_ajukan)->translatedFormat('d F Y');
        }

        /*
        |--------------------------------------------------------------------------
        | FIX LOGIKA BOLEH AJUKAN LAGI
        |--------------------------------------------------------------------------
        */
        $bolehAjukanLagi = false;

        if (!$pengajuan) {

            if ($pembaruanDitolak) {

                if (
                    now()->greaterThanOrEqualTo(
                        Carbon::parse($pembaruanDitolak->tanggal_boleh_ajukan)
                    )
                ) {

                    $bolehAjukanLagi = true;

                }

            } elseif (!$pengajuanBaru) {

                $bolehAjukanLagi = true;

            } elseif (
                $pengajuanBaru->status_pengajuan == 'Ditolak'
            ) {

                if (
                    !$pengajuanBaru->tanggal_boleh_ajukan ||
                    now()->greaterThanOrEqualTo(
                        Carbon::parse($pengajuanBaru->tanggal_boleh_ajukan)
                    )
                ) {

                    $bolehAjukanLagi = true;

                }

            }

        }

        // Tidak sedang menerima bantuan aktif
if (
    !$pengajuan &&
    !$pembaruanDitolak
) {

    // Cek apakah bantuan terakhir berakhir karena usia
    $bantuanBerakhirKarenaUsia = false;

    if (
        $riwayat->isNotEmpty() &&
        $riwayat->first()->catatan_admin &&
        str_contains(
            strtolower($riwayat->first()->catatan_admin),
            'usia'
        )
    ) {
        $bantuanBerakhirKarenaUsia = true;
    }

    // Kalau bukan karena usia, gunakan logika lama
    if (!$bantuanBerakhirKarenaUsia) {

        if (!$pengajuanBaru) {

            $bolehAjukanLagi = true;

        } elseif ($pengajuanBaru->status_pengajuan == 'Ditolak') {

            if (
                !$pengajuanBaru->tanggal_boleh_ajukan ||
                now()->greaterThanOrEqualTo(
                    Carbon::parse($pengajuanBaru->tanggal_boleh_ajukan)
                )
            ) {

                $bolehAjukanLagi = true;

            }

        }

    }

}
        return view('warga.dashboard', compact(
            'pengajuan',
            'pengajuanBaru',
            'perubahan',
            'riwayat',
            'notifPembaruan',
            'sisaHari',
            'sisaPerbaikan',
            'sisaPerbaikanKategori',
            'bolehAjukanLagi',
            'sisaTungguPengajuan',
            'tanggalPengajuan',
            'sisaTungguPerubahan',
            'pembaruanMenunggu',
            'tanggalPerubahan',
            'pembaruanDitolak'
        ));
    }

    private function resetStatusPerbaikan()
{
    Pengajuan::where('status_pengajuan', 'Perbaikan')
        ->whereDate('tanggal_selesai', '<=', today())
        ->update([
            'status_pengajuan'      => 'Ditolak',
            'status_penerima'       => 'Tidak Aktif',
            'tanggal_keputusan'     => now(),
            'tanggal_boleh_ajukan'  => now()->addDays(30),
            'catatan_admin'         => 'Perbaikan dokumen melewati batas waktu.'
        ]);
}

private function resetStatusPembaruan()
{
    Pengajuan::whereIn('kategori_bantuan', [
            'Duafa Anak',
            'Duafa Lansia'
        ])
        ->where('status_pengajuan', 'Diterima')
        ->where('status_penerima', 'Aktif')
        ->where('status_pembaruan', 'Belum')
        ->whereDate('tanggal_selesai', '<=', today())
        ->update([
            'status_penerima'      => 'Tidak Aktif',
            'status_pembaruan'     => 'Ditolak',
            'tanggal_keputusan'    => now(),
            'tanggal_boleh_ajukan' => now()->addDays(30),
            'catatan_admin'        => 'Pembaruan dokumen melewati batas waktu.'
        ]);
}
}