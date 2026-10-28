<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PembaruanDokumenController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->whereIn('kategori_bantuan', [
                'Duafa Anak',
                'Duafa Lansia'
            ])
            ->firstOrFail();
            
        if (
    $pengajuan->status_pembaruan == 'Diterima' &&
    now()->greaterThanOrEqualTo($pengajuan->tanggal_pembaruan_dokumen)
) {

    $pengajuan->update([
        'status_pembaruan' => 'Belum',
        'tanggal_pengajuan_pembaruan' => null,
    ]);

}

        $wajibUpdate =
            now()->greaterThanOrEqualTo($pengajuan->tanggal_pembaruan_dokumen);
        $lewatDeadline = now()->gt($pengajuan->tanggal_selesai);

        return view(
            'warga.pengajuan.pembaruan-dokumen',
            compact(
                'pengajuan',
                'wajibUpdate',
                'lewatDeadline'
            )
        );
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->whereIn('kategori_bantuan', [
                'Duafa Anak',
                'Duafa Lansia'
            ])
            ->firstOrFail();
        /*
|--------------------------------------------------------------------------
| Belum masuk jadwal pembaruan
|--------------------------------------------------------------------------
*/
        if (now()->lt($pengajuan->tanggal_pembaruan_dokumen)) {

            return back()->with(
                'warning',
                'Belum memasuki jadwal pembaruan dokumen.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Deadline sudah lewat
        |--------------------------------------------------------------------------
        */
        if (now()->gt($pengajuan->tanggal_selesai)) {

            return back()->with(
                'warning',
                'Masa pembaruan dokumen telah berakhir.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Masih menunggu verifikasi admin
        |--------------------------------------------------------------------------
        */
        if ($pengajuan->status_pembaruan == 'Menunggu') {

            return back()->with(
                'warning',
                'Dokumen pembaruan masih menunggu verifikasi admin.'
            );

        }

        $rules = [
            'file_kk' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sktm' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if (!empty($pengajuan->file_domisili)) {
            $rules['file_domisili'] =
                'required|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        if ($pengajuan->kategori_bantuan == 'Duafa Lansia') {
            $rules['file_bukti_rumah'] =
                'nullable|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $request->validate($rules);

        // ===========================
        // DUAFA ANAK
        // ===========================
        if ($pengajuan->kategori_bantuan == 'Duafa Anak') {

            $pengajuan->file_kk_pembaruan =
                $request->file('file_kk')->store('pembaruan', 'public');

            $pengajuan->file_sktm_pembaruan =
                $request->file('file_sktm')->store('pembaruan', 'public');

            if (!empty($pengajuan->file_domisili)) {

                $pengajuan->file_domisili_pembaruan =
                    $request->file('file_domisili')->store('pembaruan', 'public');

            }
        }

        // ===========================
        // DUAFA LANSIA
        // ===========================
        if ($pengajuan->kategori_bantuan == 'Duafa Lansia') {

            $pengajuan->file_kk_pembaruan =
                $request->file('file_kk')->store('pembaruan', 'public');

            $pengajuan->file_sktm_pembaruan =
                $request->file('file_sktm')->store('pembaruan', 'public');

            if (!empty($pengajuan->file_domisili)) {

                $pengajuan->file_domisili_pembaruan =
                    $request->file('file_domisili')->store('pembaruan', 'public');

            }

            if ($request->hasFile('file_bukti_rumah')) {

                $pengajuan->file_bukti_rumah_pembaruan =
                    $request->file('file_bukti_rumah')->store('pembaruan', 'public');

            }
        }

        $pengajuan->status_pembaruan = 'Menunggu';
        $pengajuan->tanggal_pengajuan_pembaruan = now();
        $pengajuan->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Dokumen pembaruan berhasil dikirim dan sedang menunggu verifikasi admin.');
    }
}