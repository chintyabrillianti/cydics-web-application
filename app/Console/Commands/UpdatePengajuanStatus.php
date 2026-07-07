<?php

namespace App\Console\Commands;

use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:update-pengajuan-status')]
#[Description('Update otomatis status pengajuan bantuan')]
class UpdatePengajuanStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pengajuans = Pengajuan::all();

        foreach ($pengajuans as $pengajuan) {

            // Berlaku untuk kategori yang memiliki batas usia
            if (
                in_array(
                    $pengajuan->kategori_bantuan,
                    [
                        'Yatim',
                        'Piatu',
                        'Yatim-Piatu',
                        'Duafa Anak'
                    ]
                )
            ) {

                $batasUsia = Carbon::parse($pengajuan->tanggal_lahir)
                    ->addYears(18)
                    ->startOfDay();

                if (
                    $pengajuan->status_penerima == 'Aktif' &&
                    now()->greaterThanOrEqualTo($batasUsia)
                ) {

                    $pengajuan->status_penerima = 'Tidak Aktif';

                    $pengajuan->tanggal_selesai = now();

                    $pengajuan->catatan_admin =
                        'Bantuan ' .
                        $pengajuan->kategori_bantuan .
                        ' telah berakhir karena Anda telah mencapai batas usia maksimal penerima bantuan (18 tahun).';

                    $pengajuan->save();
                }
            }
            // ======================================
            // Deadline Perbaikan Pengajuan Baru
            // ======================================

            if (
                is_null($pengajuan->kategori_sebelumnya) &&
                $pengajuan->status_pengajuan == 'Perbaikan' &&
                $pengajuan->tanggal_selesai &&
                $pengajuan->tanggal_boleh_ajukan == null &&
                now()->greaterThan(
                    Carbon::parse($pengajuan->tanggal_selesai)->endOfDay()
                )
            ) {

                $pengajuan->status_pengajuan = 'Ditolak';

                $pengajuan->status_penerima = 'Tidak Aktif';

                $pengajuan->catatan_admin =
                    'Pengajuan ditolak karena Anda tidak melakukan perbaikan dokumen hingga batas waktu yang telah ditentukan.';

                $pengajuan->tanggal_boleh_ajukan =
                    Carbon::today()->addDays(30);

                $pengajuan->save();
            }

            // ======================================
            // Deadline Perbaikan Perubahan Kategori
            // ======================================

            if (
                !is_null($pengajuan->kategori_sebelumnya) &&
                $pengajuan->status_pengajuan == 'Perbaikan' &&
                $pengajuan->tanggal_selesai &&
                $pengajuan->tanggal_boleh_ajukan == null &&
                now()->greaterThan(
                    Carbon::parse($pengajuan->tanggal_selesai)->endOfDay()
                )
            ) {

                $pengajuan->status_pengajuan = 'Ditolak';

                $pengajuan->status_penerima = 'Tidak Aktif';

                $pengajuan->catatan_admin =
                    'Pengajuan perubahan kategori ditolak karena Anda tidak melakukan perbaikan dokumen hingga batas waktu yang telah ditentukan.';

                $pengajuan->tanggal_boleh_ajukan =
                    Carbon::today()->addDays(30);

                $pengajuan->save();
            }

            // ======================================
// Deadline Pembaruan Dokumen
// ======================================

            if (
                in_array(
                    $pengajuan->kategori_bantuan,
                    [
                        'Duafa Anak',
                        'Duafa Lansia'
                    ]
                ) &&
                $pengajuan->status_penerima == 'Aktif' &&
                $pengajuan->status_pembaruan == 'Belum' &&
                $pengajuan->tanggal_selesai &&
                now()->greaterThan(
                    Carbon::parse($pengajuan->tanggal_selesai)->endOfDay()
                )
            ) {

                $pengajuan->status_penerima = 'Tidak Aktif';

                $pengajuan->status_pengajuan = 'Ditolak';

                $pengajuan->status_pembaruan = 'Belum';

                $pengajuan->catatan_admin =
                    'Bantuan dinonaktifkan karena Anda tidak melakukan pembaruan dokumen hingga batas waktu yang telah ditentukan.';

                $pengajuan->tanggal_boleh_ajukan =
                    Carbon::today()->addDays(30);

                $pengajuan->save();
            }
        }



        $this->info('Status bantuan berhasil diperbarui.');

        return self::SUCCESS;
    }
}