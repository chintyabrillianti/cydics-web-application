<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    // Fungsi pembantu untuk memblokir akses jika bantuan aktif
    private function checkActive()
    {
        // 1. Cek apakah sedang masa tunggu setelah ditolak
        $ditolak = Pengajuan::where('user_id', auth()->id())
            ->where(function ($q) {
                $q->where('status_pengajuan', 'Ditolak')
                    ->orWhere('status_pembaruan', 'Ditolak');
            })
            ->latest()
            ->first();

        if (
            $ditolak &&
            $ditolak->tanggal_boleh_ajukan &&
            now()->lt($ditolak->tanggal_boleh_ajukan)
        ) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                redirect()->route('dashboard')->with(
                    'warning',
                    'Anda dapat mengajukan kembali pada '
                    . \Carbon\Carbon::parse($ditolak->tanggal_boleh_ajukan)->translatedFormat('d F Y')
                )
            );
        }

        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->latest()
            ->first();

        // Tidak punya bantuan aktif
        if (!$aktif) {
            return;
        }

        // Sedang perubahan kategori → boleh lanjut
        if (session()->has('kategori_perubahan')) {
            return;
        }



        // Pengajuan baru diblokir
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            redirect()->route('dashboard')->with(
                'warning',
                'Anda masih memiliki bantuan yang aktif.'
            )
        );
    }

    private function pengajuanAktif()
    {
        return Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->latest()
            ->first();
    }

    public function index()
    {
        $this->checkActive(); // Proteksi halaman

        $pengajuanAktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->latest()
            ->first();

        // Jika sudah menjadi penerima bantuan aktif,
        // arahkan perubahan kategori melalui Dashboard.
        if ($pengajuanAktif) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'warning',
                    'Anda sudah menjadi penerima bantuan. Apabila ingin mengubah kategori bantuan, silakan gunakan menu Perubahan Kategori pada Dashboard.'
                );
        }

        $pengajuanTerakhir = Pengajuan::where('user_id', auth()->id())
            ->latest()
            ->first();

        $sisaTunggu = null;

        if (
            $pengajuanTerakhir &&
            $pengajuanTerakhir->status_pengajuan == 'Ditolak' &&
            $pengajuanTerakhir->tanggal_boleh_ajukan &&
            now()->lt($pengajuanTerakhir->tanggal_boleh_ajukan)
        ) {
            $sisaTunggu = now()->diffInDays($pengajuanTerakhir->tanggal_boleh_ajukan);
        }

        return view('warga.pengajuan.index', [
            'pengajuan' => $pengajuanAktif,
            'pengajuanTerakhir' => $pengajuanTerakhir,
            'sisaTunggu' => $sisaTunggu,
        ]);
    }

    public function yatim()
    {
        // Jika berasal dari perubahan kategori, langsung tampilkan form
        if (session()->has('kategori_perubahan')) {
            return view('warga.pengajuan.yatim');
        }

        // Pengajuan baru
        $this->checkActive();

        return view('warga.pengajuan.yatim');
    }

    public function storeYatim(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        // Gunakan data request jika session bernilai null
        $data = session('pengajuan_yatim') ?? $request->all();

        $umur = \Carbon\Carbon::parse($data['tanggal_lahir'])->age;

        if ($umur >= 18) {
            return back()->withErrors([
                'tanggal_lahir' => 'Usia maksimal 18 tahun.'
            ])->withInput();
        }

        if ($data['status_pernikahan'] != 'Belum Menikah') {
            return back()->withErrors([
                'status_pernikahan' => 'Bantuan yatim hanya untuk yang belum menikah.'
            ])->withInput();
        }

        if (
            ($data['alamat_sesuai_kk'] ?? '') == 'Tidak'
            && empty($data['alamat_sekarang'])
        ) {
            return back()->withErrors([
                'alamat_sekarang' => 'Alamat domisili wajib diisi.'
            ]);
        }

        $rules = [
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kematian_ayah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if (($data['alamat_sesuai_kk'] ?? null) == 'Tidak') {
            $rules['file_domisili'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $request->validate($rules, [
            'file_kk.required' => 'Kartu keluarga wajib diupload.',
            'file_kematian_ayah.required' => 'Surat kematian ayah wajib diupload.',
            'file_domisili.required' => 'Surat domisili wajib diupload.',
        ]);
        $aktaKelahiran = null;

        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiran = $request
                ->file('file_akta_kelahiran')
                ->store('pengajuan', 'public');
        }

        $kk = $request->file('file_kk')->store('pengajuan', 'public');

        $kematianAyah = $request
            ->file('file_kematian_ayah')
            ->store('pengajuan', 'public');

        $belumMenikah = null;

        if ($request->hasFile('file_tidak_menikah')) {
            $belumMenikah = $request
                ->file('file_tidak_menikah')
                ->store('pengajuan', 'public');
        }

        $domisili = null;
        if ($request->hasFile('file_domisili')) {
            $domisili = $request
                ->file('file_domisili')
                ->store('pengajuan', 'public');
        }
        $jumlah = Pengajuan::max('id') + 1;
        $cek = Pengajuan::where('user_id', auth()->id())
            ->whereIn('status_pengajuan', ['Menunggu', 'Perbaikan', 'Diterima'])
            ->first();
        if ($cek) {
            return redirect()
                ->route('pengajuan.index')
                ->with('warning', 'Anda masih memiliki pengajuan ' . $cek->kategori_bantuan . ' dengan status ' . $cek->status_pengajuan . '.');
        }
        $idPengajuan = 'CYDICS-YT-' . str_pad($jumlah, 4, '0', STR_PAD_LEFT);

        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->first();
        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'user_id' => auth()->id(),
            'kategori_sebelumnya' => session()->has('kategori_perubahan')
                ? $aktif?->kategori_bantuan
                : null,
            'kategori_bantuan' => 'Yatim',
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_sesuai_kk' => $data['alamat_sesuai_kk'],
            'alamat_sekarang' => $data['alamat_sekarang'] ?? null,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'status_pernikahan' => $data['status_pernikahan'],
            'file_akta_kelahiran' => $aktaKelahiran,
            'file_kk' => $kk,
            'file_domisili' => $domisili,
            'file_kematian_ayah' => $kematianAyah,
            'file_tidak_menikah' => $belumMenikah,
            'status_pengajuan' => 'Menunggu',
            'status_penerima' => 'Tidak Aktif',
        ]);
        session()->forget('pengajuan_yatim');
        session()->forget('kategori_perubahan');
        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan bantuan berhasil dikirim.');
    }

    public function yatimDokumen(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }

        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:100',
            'alamat_sesuai_kk' => 'required|in:Ya,Tidak',
            'no_hp' => 'required|digits_between:10,13',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
        ]);

        $data = $request->all();

        session([
            'pengajuan_yatim' => $data
        ]);

        return view('warga.pengajuan.yatim-dokumen', [
            'data' => $data
        ]);
    }

    public function piatu()
    {
        // Jika berasal dari perubahan kategori
        if (session()->has('kategori_perubahan')) {
            return view('warga.pengajuan.piatu');
        }

        // Pengajuan baru
        $this->checkActive();

        return view('warga.pengajuan.piatu');
    }

    public function piatuDokumen(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }

        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:100',
            'alamat_sesuai_kk' => 'required|in:Ya,Tidak',
            'no_hp' => 'required|digits_between:10,13',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
        ]);

        $data = $request->all();

        session([
            'pengajuan_piatu' => $data
        ]);

        return view('warga.pengajuan.piatu-dokumen', [
            'data' => $data
        ]);
    }

    public function storePiatu(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        // Gunakan data request jika session bernilai null
        $data = session('pengajuan_piatu') ?? $request->all();
        $umur = \Carbon\Carbon::parse($data['tanggal_lahir'])->age;

        if ($umur >= 18) {
            return back()->withErrors([
                'tanggal_lahir' => 'Usia maksimal 18 tahun.'
            ])->withInput();
        }

        if ($data['status_pernikahan'] != 'Belum Menikah') {
            return back()->withErrors([
                'status_pernikahan' => 'Bantuan piatu hanya untuk yang belum menikah.'
            ])->withInput();
        }
        $rules = [
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kematian_ibu' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
        if (($data['alamat_sesuai_kk'] ?? '') == 'Tidak') {
            if (
                ($data['alamat_sesuai_kk'] ?? '') == 'Tidak'
                && empty($data['alamat_sekarang'])
            ) {
                return back()->withErrors([
                    'alamat_sekarang' => 'Alamat domisili wajib diisi.'
                ]);
            }
            $rules['file_domisili'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        $request->validate($rules, [
            'file_kk.required' => 'Kartu keluarga wajib diupload.',
            'file_kematian_ibu.required' => 'Surat kematian ibu wajib diupload.',
            'file_domisili.required' => 'Surat domisili wajib diupload.',
        ]);
        $aktaKelahiran = null;

        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiran = $request
                ->file('file_akta_kelahiran')
                ->store('pengajuan', 'public');
        }
        $kk = $request->file('file_kk')->store('pengajuan', 'public');
        $kematianIbu = $request->file('file_kematian_ibu')->store('pengajuan', 'public');
        $belumMenikah = null;

        if ($request->hasFile('file_tidak_menikah')) {
            $belumMenikah = $request
                ->file('file_tidak_menikah')
                ->store('pengajuan', 'public');
        }
        $domisili = null;
        if ($request->hasFile('file_domisili')) {
            $domisili = $request->file('file_domisili')->store('pengajuan', 'public');
        }
        $lastId = Pengajuan::max('id') + 1;
        $cek = Pengajuan::where('user_id', auth()->id())
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Diterima'
            ])
            ->first();

        if ($cek) {
            return redirect()
                ->route('pengajuan.index')
                ->with(
                    'warning',
                    'Anda masih memiliki pengajuan ' .
                    $cek->kategori_bantuan .
                    ' dengan status ' .
                    $cek->status_pengajuan .
                    '.'
                );
        }
        $idPengajuan = 'CYDICS-PT-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->first();
        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'user_id' => auth()->id(),
            'kategori_sebelumnya' => session()->has('kategori_perubahan')
                ? $aktif?->kategori_bantuan
                : null,
            'kategori_bantuan' => 'Piatu',
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_sesuai_kk' => $data['alamat_sesuai_kk'],
            'alamat_sekarang' => $data['alamat_sekarang'] ?? null,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'status_pernikahan' => $data['status_pernikahan'],
            'file_akta_kelahiran' => $aktaKelahiran,
            'file_kk' => $kk,
            'file_domisili' => $domisili,
            'file_kematian_ibu' => $kematianIbu,
            'file_tidak_menikah' => $belumMenikah,
            'status_pengajuan' => 'Menunggu',
            'status_penerima' => 'Tidak Aktif',
        ]);
        session()->forget('pengajuan_piatu');
        session()->forget('kategori_perubahan');
        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan bantuan berhasil dikirim.');
    }

    public function yatimPiatu()
    {
        // Jika berasal dari perubahan kategori
        if (session()->has('kategori_perubahan')) {
            return view('warga.pengajuan.yatim-piatu');
        }

        // Pengajuan baru
        $this->checkActive();

        return view('warga.pengajuan.yatim-piatu');
    }

    public function yatimPiatuDokumen(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }

        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:100',
            'alamat_sesuai_kk' => 'required|in:Ya,Tidak',
            'no_hp' => 'required|digits_between:10,13',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
        ]);

        $data = $request->all();

        session([
            'pengajuan_yatim_piatu' => $data
        ]);

        return view(
            'warga.pengajuan.yatim-piatu-dokumen',
            [
                'data' => $data
            ]
        );
    }

    public function storeYatimPiatu(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        // Gunakan data request jika session bernilai null
        $data = session('pengajuan_yatim_piatu') ?? $request->all();
        $umur = \Carbon\Carbon::parse($data['tanggal_lahir'])->age;

        if ($umur >= 18) {
            return back()->withErrors([
                'tanggal_lahir' => 'Usia maksimal 18 tahun.'
            ])->withInput();
        }
        if ($data['status_pernikahan'] != 'Belum Menikah') {
            return back()->withErrors([
                'status_pernikahan' => 'Bantuan yatim-piatu hanya untuk yang belum menikah.'
            ])->withInput();
        }
        $rules = [
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kematian_orangtua' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
        if (
            ($data['alamat_sesuai_kk'] ?? '') == 'Tidak'
            && empty($data['alamat_sekarang'])
        ) {
            return back()->withErrors([
                'alamat_sekarang' => 'Alamat domisili wajib diisi.'
            ]);
        }

        if (($data['alamat_sesuai_kk'] ?? '') == 'Tidak') {
            $rules['file_domisili'] =
                'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        $request->validate($rules, [
            'file_kk.required' => 'Kartu keluarga wajib diupload.',
            'file_kematian_orangtua.required' => 'Surat kematian orang tua wajib diupload.',
            'file_domisili.required' => 'Surat domisili wajib diupload.',
        ]);
        $aktaKelahiran = null;

        if ($request->hasFile('file_akta_kelahiran')) {
            $aktaKelahiran = $request
                ->file('file_akta_kelahiran')
                ->store('pengajuan', 'public');
        }
        $kk = $request->file('file_kk')->store('pengajuan', 'public');
        $kematianOrangtua = $request->file('file_kematian_orangtua')->store('pengajuan', 'public');
        $belumMenikah = null;

        if ($request->hasFile('file_tidak_menikah')) {
            $belumMenikah = $request
                ->file('file_tidak_menikah')
                ->store('pengajuan', 'public');
        }
        $domisili = null;
        if ($request->hasFile('file_domisili')) {
            $domisili = $request->file('file_domisili')->store('pengajuan', 'public');
        }
        $lastId = Pengajuan::max('id') + 1;
        $cek = Pengajuan::where('user_id', auth()->id())
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Diterima'
            ])
            ->first();

        if ($cek) {
            return redirect()
                ->route('pengajuan.index')
                ->with(
                    'warning',
                    'Anda masih memiliki pengajuan '
                    . $cek->kategori_bantuan .
                    ' dengan status ' .
                    $cek->status_pengajuan .
                    '.'
                );
        }
        $idPengajuan = 'CYDICS-YP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->first();
        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'user_id' => auth()->id(),
            'kategori_sebelumnya' => null,
            'kategori_bantuan' => 'Yatim-Piatu',
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_sesuai_kk' => $data['alamat_sesuai_kk'],
            'alamat_sekarang' => $data['alamat_sekarang'] ?? null,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'status_pernikahan' => $data['status_pernikahan'],
            'file_akta_kelahiran' => $aktaKelahiran,
            'file_kk' => $kk,
            'file_domisili' => $domisili,
            'file_kematian_orangtua' => $kematianOrangtua,
            'file_tidak_menikah' => $belumMenikah,
            'status_pengajuan' => 'Menunggu',
            'status_penerima' => 'Tidak Aktif',
        ]);
        session()->forget('pengajuan_yatim_piatu');
        session()->forget('kategori_perubahan');
        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan bantuan berhasil dikirim.');
    }


    public function pembaruanDokumen()
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())->latest()->first();
        $wajibUpdate = false;

        if ($pengajuan->tanggal_keputusan) {
            $wajibUpdate = now()->diffInMonths($pengajuan->tanggal_keputusan) >= 6;
        }

        return view('warga.pengajuan.pembaruan-dokumen', compact('pengajuan', 'wajibUpdate'));
    }

    public function duafaAnak()
    {
        // Jika berasal dari perubahan kategori
        if (session()->has('kategori_perubahan')) {
            return view('warga.pengajuan.duafa-anak');
        }

        // Pengajuan baru
        $this->checkActive();

        return view('warga.pengajuan.duafa-anak');
    }

    public function duafaAnakDokumen(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:100',
            'alamat_sesuai_kk' => 'required|in:Ya,Tidak',
            'no_hp' => 'required|digits_between:10,13',
            'tanggal_lahir' => 'required|date',

            'jumlah_saudara' => 'required|integer|min:0|max:10',

            'status_wali' => 'required|in:Lengkap,Tidak Lengkap',
            'penghasilan_orang_tua' => 'required|string',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
            'status_pekerjaan' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        if (
            $request->domisili_deparis == 'Ya' &&
            !$request->filled('alamat_sesuai_kk')
        ) {
            return back()
                ->withErrors([
                    'alamat_sesuai_kk' => 'Pilih apakah alamat pada KK sama dengan domisili.'
                ])
                ->withInput();
        }

        if (
            $request->domisili_deparis == 'Ya' &&
            $request->alamat_sesuai_kk == 'Tidak' &&
            !$request->filled('alamat_sekarang')
        ) {
            return back()
                ->withErrors([
                    'alamat_sekarang' => 'Alamat domisili wajib diisi.'
                ])
                ->withInput();
        }

        $data = $request->all();

        session([
            'pengajuan_duafa_anak' => $data
        ]);

        return view('warga.pengajuan.duafa-anak-dokumen', [
            'data' => $data
        ]);
    }

    public function storeDuafaAnak(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        // Gunakan data request jika session bernilai null
        $data = session('pengajuan_duafa_anak') ?? $request->all();

        if ($data['status_pernikahan'] != 'Belum Menikah') {
            return back()->withErrors([
                'status_pernikahan' => 'Bantuan Duafa Anak hanya untuk yang belum menikah.'
            ])->withInput();
        }

        if ($data['status_pekerjaan'] != 'Belum Bekerja') {
            return back()->withErrors([
                'status_pekerjaan' => 'Bantuan Duafa Anak hanya untuk yang belum bekerja.'
            ])->withInput();
        }

        if ($data['status_wali'] != 'Lengkap') {
            return back()->withErrors([
                'status_wali' => 'Silakan mengajukan kategori Yatim, Piatu, atau Yatim-Piatu.'
            ])->withInput();
        }
        if (
            ($data['domisili_deparis'] ?? '') == 'Ya' &&
            ($data['alamat_sesuai_kk'] ?? '') == 'Tidak' &&
            empty($data['alamat_sekarang'])
        ) {
            return back()->withErrors([
                'alamat_sekarang' => 'Alamat domisili wajib diisi.'
            ]);
        }
        $request->validate([
            'jumlah_saudara' => 'required|integer|min:0|max:10',
            'status_wali' => 'required|in:Lengkap,Tidak Lengkap',
            'penghasilan_orang_tua' => 'required|string',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
            'status_pekerjaan' => 'required|string',
        ]);
        $rules = [
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sktm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_tidak_menikah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_tidak_bekerja' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
        if (
            ($data['domisili_deparis'] ?? '') == 'Ya' &&
            ($data['alamat_sesuai_kk'] ?? '') == 'Tidak'
        ) {
            $rules['file_domisili'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        $request->validate($rules, [
            'file_kk.required' => 'Kartu keluarga wajib diupload.',
            'file_sktm.required' => 'Surat Keterangan Tidak Mampu wajib diupload.',
            'file_domisili.required' => 'Surat domisili wajib diupload.',
        ]);
        $akta = null;
        if ($request->hasFile('file_akta_kelahiran')) {
            $akta = $request->file('file_akta_kelahiran')->store('pengajuan', 'public');
        }
        $kk = $request->file('file_kk')->store('pengajuan', 'public');
        $sktm = $request->file('file_sktm')->store('pengajuan', 'public');
        $belumMenikah = null;
        if ($request->hasFile('file_tidak_menikah')) {
            $belumMenikah = $request->file('file_tidak_menikah')->store('pengajuan', 'public');
        }
        $belumBekerja = null;
        if ($request->hasFile('file_tidak_bekerja')) {
            $belumBekerja = $request->file('file_tidak_bekerja')->store('pengajuan', 'public');
        }
        $domisili = null;
        if ($request->hasFile('file_domisili')) {
            $domisili = $request->file('file_domisili')->store('pengajuan', 'public');
        }
        $lastId = Pengajuan::max('id') + 1;
        $cek = Pengajuan::where('user_id', auth()->id())
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Diterima'
            ])
            ->first();

        if ($cek) {
            return redirect()
                ->route('pengajuan.index')
                ->with(
                    'warning',
                    'Anda masih memiliki pengajuan ' .
                    $cek->kategori_bantuan .
                    ' dengan status ' .
                    $cek->status_pengajuan .
                    '.'
                );
        }
        $idPengajuan = 'CYDICS-DA-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->first();


        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'user_id' => auth()->id(),
            'kategori_sebelumnya' => session()->has('kategori_perubahan')
                ? $aktif?->kategori_bantuan
                : null,
            'kategori_bantuan' => 'Duafa Anak',
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_sesuai_kk' => $data['alamat_sesuai_kk'],
            'alamat_sekarang' => $data['alamat_sekarang'] ?? null,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'status_pernikahan' => $data['status_pernikahan'],
            'jumlah_saudara' => $data['jumlah_saudara'],
            'orangtua_lengkap' => $data['status_wali'] == 'Lengkap'
                ? 'Ya'
                : 'Tidak',
            'penghasilan_orang_tua' => $data['penghasilan_orang_tua'],
            'status_pekerjaan' => $data['status_pekerjaan'],
            'file_akta_kelahiran' => $akta,
            'file_kk' => $kk,
            'file_sktm' => $sktm,
            'file_tidak_menikah' => $belumMenikah,
            'file_tidak_bekerja' => $belumBekerja,
            'file_domisili' => $domisili,
            'status_pengajuan' => 'Menunggu',
            'status_kelayakan' => 'Menunggu',
            'status_penerima' => 'Tidak Aktif',
        ]);
        session()->forget('pengajuan_duafa_anak');
        session()->forget('kategori_perubahan');
        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan bantuan berhasil dikirim.');
    }

    public function duafaLansia()
    {

        // Pengajuan baru
        $this->checkActive();

        return view('warga.pengajuan.duafa-lansia');
    }

    public function duafaLansiaDokumen(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }

        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:100',
            'alamat_sesuai_kk' => 'required|in:Ya,Tidak',
            'no_hp' => 'required|digits_between:10,13',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_rumah' => 'required|in:Milik Sendiri/Menumpang,Sewa/Kontrak/Kredit',
            'jumlah_tanggungan' => 'required|integer|min:0|max:99',
            'penghasilan' => 'required|in:0-<1 Juta,1-<2 Juta,2-<3 Juta,>=3 Juta',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah,Janda,Duda,Janda Cerai,Duda Cerai',
        ]);

        if (
            $request->alamat_sesuai_kk == 'Tidak' &&
            !$request->filled('alamat_sekarang')
        ) {
            return back()
                ->withErrors([
                    'alamat_sekarang' => 'Alamat domisili wajib diisi.'
                ])
                ->withInput();
        }

        $data = $request->all();

        session([
            'pengajuan_duafa_lansia' => $data
        ]);

        return view('warga.pengajuan.duafa-lansia-dokumen', [
            'data' => $data
        ]);
    }

    public function storeDuafaLansia(Request $request)
    {
        if (!session()->has('kategori_perubahan')) {
            $this->checkActive();
        }
        // Gunakan data request jika session bernilai null
        $data = session('pengajuan_duafa_lansia') ?? $request->all();
        $rules = [
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sktm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
        if (($data['alamat_sesuai_kk'] ?? '') == 'Tidak') {
            $rules['file_domisili'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        if (in_array($data['status_pernikahan'] ?? '', ['Janda', 'Duda'])) {
            $rules['file_kematian_pasangan'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        if (in_array($data['status_pernikahan'] ?? '', ['Janda Cerai', 'Duda Cerai'])) {
            $rules['file_bukti_cerai'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        if (($data['status_rumah'] ?? '') == 'Sewa/Kontrak/Kredit') {
            $rules['file_bukti_rumah'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        $request->validate($rules);
        $kk = $request->file('file_kk')->store('pengajuan', 'public');
        $sktm = $request->file('file_sktm')->store('pengajuan', 'public');
        $domisili = null;
        if ($request->hasFile('file_domisili')) {
            $domisili = $request->file('file_domisili')->store('pengajuan', 'public');
        }
        $kematianPasangan = null;
        if ($request->hasFile('file_kematian_pasangan')) {
            $kematianPasangan = $request->file('file_kematian_pasangan')->store('pengajuan', 'public');
        }
        $buktiCerai = null;
        if ($request->hasFile('file_bukti_cerai')) {
            $buktiCerai = $request->file('file_bukti_cerai')->store('pengajuan', 'public');
        }
        $buktiRumah = null;
        if ($request->hasFile('file_bukti_rumah')) {
            $buktiRumah = $request->file('file_bukti_rumah')->store('pengajuan', 'public');
        }
        $lastId = Pengajuan::max('id') + 1;
        $cek = Pengajuan::where('user_id', auth()->id())
            ->whereIn('status_pengajuan', [
                'Menunggu',
                'Perbaikan',
                'Diterima'
            ])
            ->first();

        if ($cek) {
            return redirect()
                ->route('pengajuan.index')
                ->with(
                    'warning',
                    'Anda masih memiliki pengajuan ' .
                    $cek->kategori_bantuan .
                    ' dengan status ' .
                    $cek->status_pengajuan .
                    '.'
                );
        }
        $idPengajuan = 'CYDICS-DL-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $aktif = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->first();


        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'user_id' => auth()->id(),
            'kategori_sebelumnya' => null,
            'kategori_bantuan' => 'Duafa Lansia',
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_sesuai_kk' => $data['alamat_sesuai_kk'],
            'alamat_sekarang' => $data['alamat_sekarang'] ?? null,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'status_pernikahan' => $data['status_pernikahan'],
            'status_rumah' => $data['status_rumah'],
            'jumlah_tanggungan' => $data['jumlah_tanggungan'],
            'penghasilan' => $data['penghasilan'],
            'file_kk' => $kk,
            'file_sktm' => $sktm,
            'file_domisili' => $domisili,
            'file_kematian_pasangan' => $kematianPasangan,
            'file_bukti_cerai' => $buktiCerai,
            'file_bukti_rumah' => $buktiRumah,
            'status_pengajuan' => 'Menunggu',
            'status_kelayakan' => 'Menunggu',
            'status_penerima' => 'Tidak Aktif',
        ]);
        session()->forget('pengajuan_duafa_lansia');
        session()->forget('kategori_perubahan');
        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan berhasil dikirim.');
    }
    public function storePembaruan(Request $request)
    {

        $request->validate([
            'file_kk' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_domisili' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_ayah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_ibu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_orangtua' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_tidak_menikah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_tidak_bekerja' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_sktm' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_bukti_rumah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_bukti_cerai' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_pasangan' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
        ]);
        $pengajuan = Pengajuan::where('user_id', auth()->id())->latest()->first();
        if (!$pengajuan) {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan->status_pembaruan == 'Menunggu') {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Dokumen pembaruan sedang menunggu verifikasi admin.');
        }

        if ($request->hasFile('file_kk')) {
            $pengajuan->file_kk_perbaikan = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_domisili')) {
            $pengajuan->file_domisili = $request->file('file_domisili')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_akta_kelahiran')) {
            $pengajuan->file_akta_kelahiran = $request->file('file_akta_kelahiran')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kematian_ayah')) {
            $pengajuan->file_kematian_ayah = $request->file('file_kematian_ayah')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kematian_ibu')) {
            $pengajuan->file_kematian_ibu = $request->file('file_kematian_ibu')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kematian_orangtua')) {
            $pengajuan->file_kematian_orangtua = $request->file('file_kematian_orangtua')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_tidak_menikah')) {
            $pengajuan->file_tidak_menikah = $request->file('file_tidak_menikah')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_tidak_bekerja')) {
            $pengajuan->file_tidak_bekerja = $request->file('file_tidak_bekerja')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_sktm')) {
            $pengajuan->file_sktm = $request->file('file_sktm')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_bukti_rumah')) {
            $pengajuan->file_bukti_rumah = $request->file('file_bukti_rumah')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_bukti_cerai')) {
            $pengajuan->file_bukti_cerai = $request->file('file_bukti_cerai')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kematian_pasangan')) {
            $pengajuan->file_kematian_pasangan = $request->file('file_kematian_pasangan')->store('pengajuan', 'public');
        }

        $pengajuan->status_pembaruan = 'Menunggu';

        $pengajuan->tanggal_pengajuan_pembaruan = now();

        $pengajuan->catatan_admin = null;

        $pengajuan->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function perbaikanDokumen()
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$pengajuan) {
            return redirect()->route('dashboard');
        }

        // Hanya boleh jika status masih Perbaikan
        if ($pengajuan->status_pengajuan != 'Perbaikan') {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Pengajuan sudah tidak dapat diperbaiki.');
        }

        // Deadline sudah lewat
        if (
            $pengajuan->tanggal_selesai &&
            now()->gt(
                \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->endOfDay()
            )
        ) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'warning',
                    'Batas waktu perbaikan telah berakhir.'
                );
        }

        return view(
            'warga.pengajuan.perbaikan-dokumen',
            compact('pengajuan')
        );
    }

    public function storePerbaikan(Request $request)
    {
        $request->validate([
            'file_kk' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_domisili' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_ayah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_ibu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_orangtua' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_tidak_menikah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_sktm' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_tidak_bekerja' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_bukti_rumah' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_kematian_pasangan' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'file_bukti_cerai' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
        ], [
            'mimes' => 'Format file harus berupa pdf, png, jpg, atau jpeg.',
            'max' => 'Ukuran file maksimal adalah 5 MB.',
            'file' => 'Input harus berupa dokumen valid.',
        ]);

        $pengajuan = Pengajuan::where('user_id', auth()->id())->latest()->first();

        if (!$pengajuan) {
            return redirect()->route('dashboard');
        }

        // Hanya boleh upload jika status masih Perbaikan
        if ($pengajuan->status_pengajuan != 'Perbaikan') {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Pengajuan sudah tidak dapat diperbaiki.');
        }

        // Deadline sudah lewat
        if (
            $pengajuan->tanggal_selesai &&
            now()->gt(
                \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->endOfDay()
            )
        ) {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Batas waktu perbaikan telah berakhir.');
        }
        if ($request->hasFile('file_kk')) {
            $pengajuan->file_kk_perbaikan = $request->file('file_kk')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_domisili')) {
            $pengajuan->file_domisili_perbaikan = $request->file('file_domisili')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_akta_kelahiran')) {
            $pengajuan->file_akta_kelahiran_perbaikan = $request->file('file_akta_kelahiran')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_kematian_ayah')) {
            $pengajuan->file_kematian_ayah_perbaikan = $request->file('file_kematian_ayah')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_kematian_ibu')) {
            $pengajuan->file_kematian_ibu_perbaikan = $request->file('file_kematian_ibu')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_kematian_orangtua')) {
            $pengajuan->file_kematian_orangtua_perbaikan = $request->file('file_kematian_orangtua')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_tidak_menikah')) {
            $pengajuan->file_tidak_menikah_perbaikan = $request->file('file_tidak_menikah')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_sktm')) {
            $pengajuan->file_sktm_perbaikan = $request->file('file_sktm')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_tidak_bekerja')) {
            $pengajuan->file_tidak_bekerja_perbaikan = $request->file('file_tidak_bekerja')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_bukti_rumah')) {
            $pengajuan->file_bukti_rumah_perbaikan = $request->file('file_bukti_rumah')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_kematian_pasangan')) {
            $pengajuan->file_kematian_pasangan_perbaikan = $request->file('file_kematian_pasangan')->store('perbaikan', 'public');
        }

        if ($request->hasFile('file_bukti_cerai')) {
            $pengajuan->file_bukti_cerai_perbaikan = $request->file('file_bukti_cerai')->store('perbaikan', 'public');
        }

        $pengajuan->status_pengajuan = 'Menunggu';
        $pengajuan->status_penerima = 'Tidak Aktif';
        $pengajuan->tanggal_perbaikan = null;
        $pengajuan->catatan_admin = null;
        $pengajuan->tanggal_selesai = null;
        $pengajuan->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Dokumen berhasil dikirim.');
    }

    public function perubahanKategori($kategori)
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->where('status_penerima', 'Aktif')
            ->latest()
            ->first();

        if (!$pengajuan) {
            return redirect()->route('dashboard');
        }

        $kategoriDiizinkan = [];

        switch ($pengajuan->kategori_bantuan) {

            case 'Yatim':
                $kategoriDiizinkan = ['Yatim-Piatu'];
                break;

            case 'Piatu':
                $kategoriDiizinkan = ['Yatim-Piatu'];
                break;

            case 'Duafa Anak':
                $kategoriDiizinkan = [
                    'Yatim',
                    'Piatu',
                    'Yatim-Piatu'
                ];
                break;

            case 'Yatim-Piatu':
            case 'Duafa Lansia':
                $kategoriDiizinkan = [];
                break;
        }

        if (!in_array($kategori, $kategoriDiizinkan)) {
            return redirect()->route('dashboard')
                ->with('warning', 'Kategori perubahan tidak tersedia.');
        }

        session(['kategori_perubahan' => $kategori]);

        switch ($kategori) {

            case 'Yatim':
                return redirect()->route('pengajuan.yatim');

            case 'Piatu':
                return redirect()->route('pengajuan.piatu');

            case 'Yatim-Piatu':
                return redirect()->route('pengajuan.yatim-piatu');

            default:
                return redirect()->route('dashboard');
        }
    }
}