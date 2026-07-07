<?php

namespace App\Services;

use Carbon\Carbon;

class DuafaAnakDecisionTree
{
    public function klasifikasi($pengajuan)
    {
        $umur = Carbon::parse($pengajuan->tanggal_lahir)->age;

        // Rule 1
        if ($umur >= 18) {
            return 'Tidak Layak';
        }

        switch ($pengajuan->penghasilan_orang_tua) {

            // Rule 2
            case '0-<3 Juta':
                return 'Layak';

            // Rule 3
            case '>=5 Juta':
                return 'Tidak Layak';

            // Rule 4 & 5
            case '3-<4 Juta':

                if ($pengajuan->jumlah_saudara == 0) {
                    return 'Tidak Layak';
                }

                return 'Layak';

            // Rule 6 & 7
            case '4-<5 Juta':

                if ($pengajuan->jumlah_saudara <= 1) {
                    return 'Tidak Layak';
                }

                return 'Layak';
        }

        return 'Tidak Layak';
    }
}