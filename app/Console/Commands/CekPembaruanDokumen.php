<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengajuan;
use Carbon\Carbon;

class CekPembaruanDokumen extends Command
{
    protected $signature = 'app:cek-pembaruan-dokumen';

    protected $description =
        'Mengecek otomatis pembaruan dokumen yang melewati tanggal selesai.';

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Tidak mengirim pembaruan sampai deadline
        |--------------------------------------------------------------------------
        */

        $terlambatPembaruan = Pengajuan::whereIn('kategori_bantuan', [
            'Duafa Anak',
            'Duafa Lansia'
        ])
            ->where('status_penerima', 'Aktif')
            ->where('status_pembaruan', 'Belum')
            ->whereDate('tanggal_selesai', '<', Carbon::today())
            ->get();

        foreach ($terlambatPembaruan as $pengajuan) {

            $pengajuan->status_penerima = 'Tidak Aktif';
            $pengajuan->status_pembaruan = 'Terlambat';

            $pengajuan->status_pengajuan = 'Ditolak';

            $pengajuan->tanggal_keputusan = now();

            $pengajuan->tanggal_boleh_ajukan = now()->addDays(30);

            $pengajuan->catatan_admin =
                'Bantuan dihentikan otomatis karena tidak melakukan pembaruan dokumen sampai batas waktu.';

            $pengajuan->save();
        }

        $this->info('Pengecekan pembaruan dokumen selesai.');
    }
}