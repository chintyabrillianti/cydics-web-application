<?php

namespace App\Services;

use Carbon\Carbon;

class DuafaLansiaDecisionTree
{
    public function klasifikasi($pengajuan)
    {
        $umur = Carbon::parse($pengajuan->tanggal_lahir)->age;

        if ($umur < 55) {
            return 'Tidak Layak';
        }

        switch ($pengajuan->penghasilan) {

            case '0-<1 Juta':
                return 'Layak';

            case '>=3 Juta':
                return 'Tidak Layak';

            case '1-<2 Juta':

                if ($pengajuan->status_rumah == 'Sewa/Kontrak/Kredit') {
                    return 'Layak';
                }

                if ($pengajuan->jumlah_tanggungan == 0) {
                    return 'Tidak Layak';
                }

                return 'Layak';

            case '2-<3 Juta':

                if ($pengajuan->status_rumah == 'Milik Sendiri/Menumpang') {
                    return 'Tidak Layak';
                }

                if ($pengajuan->jumlah_tanggungan == 0) {
                    return 'Tidak Layak';
                }

                return 'Layak';
        }

        return 'Tidak Layak';
    }
}