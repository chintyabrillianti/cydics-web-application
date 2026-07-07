<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Donasi;


class HomeController extends Controller
{
    public function index()
    {
        $yatim = Pengajuan::where('kategori_bantuan', 'Yatim')
            ->where('status_penerima', 'Aktif')
            ->count();

        $piatu = Pengajuan::where('kategori_bantuan', 'Piatu')
            ->where('status_penerima', 'Aktif')
            ->count();

        $yatimPiatu = Pengajuan::where('kategori_bantuan', 'Yatim-Piatu')
            ->where('status_penerima', 'Aktif')
            ->count();

        $duafaAnak = Pengajuan::where('kategori_bantuan', 'Duafa Anak')
            ->where('status_penerima', 'Aktif')
            ->count();

        $duafaLansia = Pengajuan::where('kategori_bantuan', 'Duafa Lansia')
            ->where('status_penerima', 'Aktif')
            ->count();

        $totalPenerima =
            $yatim +
            $piatu +
            $yatimPiatu +
            $duafaAnak +
            $duafaLansia;

        $totalDonatur = User::where(
            'role',
            'donatur'
        )->count();

        $totalDonasi = Donasi::where('status', 'Terverifikasi')
    ->sum('nominal');

        $programAktif =
            Program::where('status', 1)
                ->count();

        $programs =
            Program::where('status', 1)
                ->get();

        $kegiatans = Kegiatan::latest()
            ->take(2)
            ->get();

        $website = WebsiteSetting::first();

        return view('landing.home', compact(
            'yatim',
            'piatu',
            'yatimPiatu',
            'duafaAnak',
            'duafaLansia',
            'totalPenerima',
            'totalDonatur',
            'totalDonasi',
            'programAktif',
            'website',
            'programs',
            'kegiatans'
        ));
    }

    public function kegiatan()
    {
        $kegiatans = Kegiatan::latest()->get();

        return view(
            'landing.kegiatan',
            compact('kegiatans')
        );
    }
}