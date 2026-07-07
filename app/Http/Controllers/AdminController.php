<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Donasi;
use App\Models\Laporan;
use App\Models\LaporanTahunan;
use App\Services\DuafaAnakDecisionTree;
use App\Services\DuafaLansiaDecisionTree;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->resetStatusPerbaikan();
        $this->resetStatusPembaruan();
        Carbon::setLocale('id');
        $totalWarga = User::where(
            'role',
            'warga'
        )->count();

        $totalPengajuan = Pengajuan::count();

        $belumMengajukan = $totalWarga - $totalPengajuan;

        $totalAktif = Pengajuan::where(
            'status_penerima',
            'Aktif'
        )->count();

        $diterima =
            Pengajuan::where(
                'status_pengajuan',
                'Diterima'
            )->count();

        $menunggu =
            Pengajuan::where(
                'status_pengajuan',
                'Menunggu'
            )->count();

        $ditolak =
            Pengajuan::where(
                'status_pengajuan',
                'Ditolak'
            )->count();

        $aktivitas =
            Pengajuan::latest()
                ->take(5)
                ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalWarga',
                'totalPengajuan',
                'belumMengajukan',
                'totalAktif',
                'diterima',
                'menunggu',
                'ditolak',
                'aktivitas'
            )
        );
    }

    public function website()
    {
        $website = WebsiteSetting::first();

        $programs = Program::latest()->get();
        $kegiatans = Kegiatan::latest()
            ->take(5)
            ->get();

        return view(
            'admin.website',
            compact(
                'website',
                'programs',
                'kegiatans'
            )
        );
    }
    public function updateWebsite(Request $request)
    {
        $website = WebsiteSetting::firstOrCreate([
            'id' => 1
        ]);

        /*
        ===========================
        BANNER HERO
        ===========================
        */

        if ($request->has('hero_title')) {

            $website->hero_title = $request->hero_title;

        }

        if ($request->has('hero_subtitle')) {

            $website->hero_subtitle = $request->hero_subtitle;

        }

        if ($request->hasFile('banner')) {

            $website->banner = $request
                ->file('banner')
                ->store('banner', 'public');

        }

        /*
        ===========================
        VISI MISI
        ===========================
        */

        if ($request->has('visi')) {

            $website->visi = $request->visi;

        }

        if ($request->has('misi')) {

            $website->misi = $request->misi;

        }

        /*
        ===========================
        KONTAK
        ===========================
        */

        if ($request->has('telepon')) {

            $website->telepon = $request->telepon;

        }

        if ($request->has('email')) {

            $website->email = $request->email;

        }

        if ($request->has('alamat')) {

            $website->alamat = $request->alamat;

        }

        /*
        ===========================
        SOSIAL MEDIA
        ===========================
        */

        if ($request->has('instagram')) {

            $website->instagram = $request->instagram;

        }

        if ($request->has('youtube')) {

            $website->youtube = $request->youtube;

        }

        $website->save();

        return back()->with(
            'success',
            'Data berhasil disimpan.'
        );
    }

    public function statusProgram($id)
    {

        $program = Program::findOrFail($id);

        $program->status =
            $program->status == 1 ? 0 : 1;

        $program->save();

        return back();
    }

    public function hapusProgram($id)
    {
        Program::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Program berhasil dihapus.'
        );
    }

    public function updateProgram(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $program->nama = $request->nama;

        $program->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {

            $program->foto =
                $request->file('foto')
                    ->store('program', 'public');
        }

        $program->save();

        return back()->with(
            'success',
            'Program berhasil diubah.'
        );
    }

    public function storeProgram(Request $request)
    {
        $foto = null;

        if ($request->hasFile('foto')) {

            $foto = $request->file('foto')
                ->store('program', 'public');
        }

        Program::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'status' => 1,
        ]);

        return back()->with(
            'success',
            'Program berhasil ditambahkan.'
        );
    }

    public function storeKegiatan(Request $request)
    {
        $request->validate(

            [

                'judul' => [
                    'required',
                    'min:5',
                    'max:100',
                    'regex:/^[A-Za-z0-9\s.,()\-]+$/'
                ],

                'deskripsi' => [
                    'required',
                    'min:20',
                    'max:500'
                ],

                'tanggal' => [
                    'required',
                    'date',
                    'before_or_equal:today'
                ],

                'foto' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048'
                ],

            ],

            [

                'judul.required' => 'Judul kegiatan wajib diisi.',
                'judul.min' => 'Judul minimal 5 karakter.',
                'judul.max' => 'Judul maksimal 100 karakter.',
                'judul.regex' => 'Judul hanya boleh berisi huruf, angka, spasi, titik, koma, tanda kurung, dan tanda hubung.',

                'deskripsi.required' => 'Keterangan wajib diisi.',
                'deskripsi.min' => 'Keterangan minimal 20 karakter.',
                'deskripsi.max' => 'Keterangan maksimal 500 karakter.',

                'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
                'tanggal.date' => 'Tanggal tidak valid.',
                'tanggal.before_or_equal' => 'Tanggal kegiatan tidak boleh melebihi hari ini.',

                'foto.required' => 'Foto kegiatan wajib dipilih.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Foto hanya boleh JPG, JPEG, PNG, atau WEBP.',
                'foto.max' => 'Ukuran foto maksimal 2 MB.',

            ]

        );

        if ($request->hasFile('foto')) {

            $foto = $request->file('foto')
                ->store('kegiatan', 'public');
        }

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'foto' => $foto
        ]);

        return back()->with(
            'success',
            'Kegiatan berhasil ditambahkan.'
        );
    }

    public function updateKegiatan(Request $request, $id)
    {
        $request->validate(

            [

                'judul' => [
                    'required',
                    'min:5',
                    'max:100',
                    'regex:/^[A-Za-z0-9\s.,()\-]+$/'
                ],

                'deskripsi' => [
                    'required',
                    'min:20',
                    'max:500'
                ],

                'tanggal' => [
                    'required',
                    'date',
                    'before_or_equal:today'
                ],

                'foto' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048'
                ],

            ],

            [

                'judul.required' => 'Judul kegiatan wajib diisi.',
                'judul.regex' => 'Judul hanya boleh huruf, angka, spasi dan tanda baca.',

                'deskripsi.required' => 'Keterangan wajib diisi.',

                'tanggal.required' => 'Tanggal wajib diisi.',
                'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',

                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Foto hanya boleh JPG, JPEG, PNG, atau WEBP.',
                'foto.max' => 'Ukuran foto maksimal 2 MB.'

            ]

        );

        $kegiatan = Kegiatan::findOrFail($id);

        $kegiatan->judul = $request->judul;
        $kegiatan->deskripsi = $request->deskripsi;
        $kegiatan->tanggal = $request->tanggal;

        if ($request->hasFile('foto')) {

            $kegiatan->foto = $request
                ->file('foto')
                ->store('kegiatan', 'public');

        }

        $kegiatan->save();

        return back()->with(
            'success',
            'Kegiatan berhasil diperbarui.'
        );
    }

    public function hapusKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if (
            $kegiatan->foto &&
            \Storage::disk('public')->exists($kegiatan->foto)
        ) {
            \Storage::disk('public')->delete($kegiatan->foto);
        }

        $kegiatan->delete();

        return back()->with(
            'success',
            'Kegiatan berhasil dihapus.'
        );
    }

    public function detailPengajuan(Request $request, $id)
    {
        $this->resetStatusPerbaikan();
        $this->resetStatusPembaruan();
        $query = Pengajuan::query();

        if ($request->nama) {
            $query->where(
                'nama_lengkap',
                'like',
                '%' . $request->nama . '%'
            );
        }

        if ($request->kategori) {
            $query->where(
                'kategori_bantuan',
                $request->kategori
            );
        }

        if ($request->status) {
            $query->where(
                'status_pengajuan',
                $request->status
            );
        }

        $pengajuan = $query->latest()->get();

        $detail = Pengajuan::findOrFail($id);

        return view(
            'admin.pengajuan',
            compact(
                'pengajuan',
                'detail'
            )
        );
    }



    public function pengajuan(Request $request)
    {
        $this->resetStatusPerbaikan();
        $this->resetStatusPembaruan();
        $query = Pengajuan::query();

        // Cari nama
        if ($request->nama) {
            $query->where(
                'nama_lengkap',
                'like',
                '%' . $request->nama . '%'
            );
        }

        // Filter kategori
        if ($request->kategori) {
            $query->where(
                'kategori_bantuan',
                $request->kategori
            );
        }

        // Filter status
        if ($request->status) {
            $query->where(
                'status_pengajuan',
                $request->status
            );
        }

        $pengajuan = $query
            ->latest()
            ->get();

        $detail = null;

        return view(
            'admin.pengajuan',
            compact(
                'pengajuan',
                'detail'
            )
        );
    }

    public function terima($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        /*
        ===========================
        NONAKTIFKAN BANTUAN LAMA
        ===========================
        */

        if ($pengajuan->kategori_sebelumnya) {

            Pengajuan::where(
                'user_id',
                $pengajuan->user_id
            )
                ->where(
                    'kategori_bantuan',
                    $pengajuan->kategori_sebelumnya
                )
                ->where(
                    'status_penerima',
                    'Aktif'
                )
                ->update([
                    'status_penerima' => 'Tidak Aktif',
                    'tanggal_selesai' => now(),
                ]);
        }

        /*
        ===========================
        DATA UMUM
        ===========================
        */

        $pengajuan->catatan_admin = null;

        $pengajuan->tanggal_keputusan = now();

        $pengajuan->tanggal_mulai = now();

        /*
        ===========================
        YATIM / PIATU / YATIM PIATU
        ===========================
        */

        if (
            $pengajuan->kategori_bantuan == 'Yatim' ||
            $pengajuan->kategori_bantuan == 'Piatu' ||
            $pengajuan->kategori_bantuan == 'Yatim-Piatu'
        ) {
            $pengajuan->status_pengajuan = 'Diterima';
            $pengajuan->status_penerima = 'Aktif';

            $pengajuan->status_kelayakan = 'Layak';

            $pengajuan->tanggal_selesai =
                Carbon::parse(
                    $pengajuan->tanggal_lahir
                )->addYears(18);
        }

        /*
        ===========================
        DUAFA ANAK
        ===========================
        */

        if ($pengajuan->kategori_bantuan == 'Duafa Anak') {

            $hasil = app(DuafaAnakDecisionTree::class)
                ->klasifikasi($pengajuan);

            $pengajuan->status_kelayakan = $hasil;

            if ($hasil == 'Layak') {

                $pengajuan->status_pengajuan = 'Diterima';
                $pengajuan->status_penerima = 'Aktif';

                $pengajuan->tanggal_selesai = now()->addMonths(6);

                $pengajuan->tanggal_pembaruan_dokumen =
                    now()->addMonths(6)->subDays(30);

                $pengajuan->status_pembaruan = 'Belum';

            } else {

                $pengajuan->status_pengajuan = 'Ditolak';
                $pengajuan->status_penerima = 'Tidak Aktif';
                $pengajuan->catatan_admin = 'Tidak memenuhi hasil klasifikasi Sistem.';

                $pengajuan->tanggal_keputusan = now();
                $pengajuan->tanggal_boleh_ajukan = now()->addDays(30);

            }

        }



        /*
        ===========================
        DUAFA LANSIA
        ===========================
        */

        if ($pengajuan->kategori_bantuan == 'Duafa Lansia') {

            $hasil = app(DuafaLansiaDecisionTree::class)
                ->klasifikasi($pengajuan);

            $pengajuan->status_kelayakan = $hasil;

            if ($hasil == 'Layak') {

                $pengajuan->status_pengajuan = 'Diterima';
                $pengajuan->status_penerima = 'Aktif';

                $pengajuan->tanggal_selesai = now()->addMonths(6);

                $pengajuan->tanggal_pembaruan_dokumen =
                    now()->addMonths(6)->subDays(30);

                $pengajuan->status_pembaruan = 'Belum';

            } else {

                $pengajuan->status_pengajuan = 'Ditolak';
                $pengajuan->status_penerima = 'Tidak Aktif';

                $pengajuan->catatan_admin =
                    'Tidak memenuhi hasil klasifikasi Sistem.';
                $pengajuan->tanggal_keputusan = now();
                $pengajuan->tanggal_boleh_ajukan = now()->addDays(30);
            }
        }

        $pengajuan->save();

        if ($pengajuan->status_pengajuan == 'Diterima') {

            return back()->with(
                'success',
                'Pengajuan diterima.'
            );

        }

        return back()->with(
            'error',
            'Pengajuan ditolak berdasarkan hasil klasifikasi Decision Tree.'
        );
    }

    public function perbaikan(Request $request, $id)
    {
        $request->validate(
            [
                'catatan_admin' => 'required|min:20|max:255'
            ],
            [
                'catatan_admin.required' =>
                    'Catatan perbaikan wajib diisi.',

                'catatan_admin.min' =>
                    'Catatan perbaikan minimal 20 karakter.',

                'catatan_admin.max' =>
                    'Catatan perbaikan maksimal 255 karakter.'
            ]
        );

        $pengajuan = Pengajuan::findOrFail($id);

        /*
        ===================================
        CEK HASIL ALGORITMA
        ===================================
        */

        if (
            in_array($pengajuan->kategori_bantuan, ['Duafa Anak', 'Duafa Lansia'])
        ) {

            if ($pengajuan->kategori_bantuan == 'Duafa Anak') {

                $hasil = app(DuafaAnakDecisionTree::class)
                    ->klasifikasi($pengajuan);

            } else {

                $hasil = app(DuafaLansiaDecisionTree::class)
                    ->klasifikasi($pengajuan);

            }

            if ($hasil == 'Tidak Layak') {

                $pengajuan->status_pengajuan = 'Ditolak';
                $pengajuan->status_penerima = 'Tidak Aktif';
                $pengajuan->status_kelayakan = 'Tidak Layak';
                $pengajuan->catatan_admin = 'Tidak memenuhi hasil klasifikasi Sistem.';
                $pengajuan->tanggal_keputusan = now();
                $pengajuan->tanggal_boleh_ajukan = now()->addDays(30);

                $pengajuan->save();

                return back()->with(
                    'error',
                    'Pengajuan otomatis ditolak karena hasil algoritma.'
                );
            }
        }

        if (is_null($pengajuan->kategori_sebelumnya)) {
            $pengajuan->status_penerima = 'Tidak Aktif';
        }

        $pengajuan->catatan_admin =
            $request->catatan_admin;

        if ($pengajuan->jumlah_perbaikan >= 2) {

            return back()->with(
                'error',
                'Batas perbaikan telah tercapai.'
            );
        }

        $pengajuan->jumlah_perbaikan++;

        $pengajuan->status_pengajuan = 'Perbaikan';

        $pengajuan->tanggal_perbaikan = now();

        $pengajuan->tanggal_selesai =
            Carbon::parse(
                $pengajuan->tanggal_perbaikan
            )->addDays(15);

        $pengajuan->save();

        return back()->with(
            'success',
            'Status pengajuan berhasil diperbarui.'
        );
    }
    private function resetStatusPerbaikan()
{
    Pengajuan::where('status_pengajuan', 'Perbaikan')
        ->whereDate('tanggal_selesai', '<', today())
        ->update([
            'status_pengajuan'   => 'Ditolak',
            'status_penerima'    => 'Tidak Aktif',
            'tanggal_keputusan'  => now(),
            'tanggal_boleh_ajukan' => now()->addDays(30),
            'catatan_admin'      => 'Perbaikan dokumen melewati batas waktu.'
        ]);
}

    public function tolak(Request $request, $id)
    {
        $request->validate(
            [
                'alasan_penolakan' =>
                    'required|min:20|max:255'
            ],

            [
                'alasan_penolakan.required' =>
                    'Alasan penolakan wajib diisi.',

                'alasan_penolakan.min' =>
                    'Alasan penolakan minimal 20 karakter.',

                'alasan_penolakan.max' =>
                    'Alasan penolakan maksimal 255 karakter.'
            ]
        );

        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->status_pengajuan = 'Ditolak';

        if (is_null($pengajuan->kategori_sebelumnya)) {
            $pengajuan->status_penerima = 'Tidak Aktif';
        }

        $pengajuan->tanggal_boleh_ajukan =
            now()->addDays(30);

        $pengajuan->catatan_admin =
            $request->alasan_penolakan;

        $pengajuan->tanggal_keputusan = now();

        $pengajuan->save();

        return back()->with(
            'success',
            'Pengajuan ditolak.'
        );
    }

    public function arsipPengajuan(Request $request)
    {
        $this->resetStatusPerbaikan();
        $this->resetStatusPembaruan();
        $query = Pengajuan::where(function ($q) {

            // Semua yang ditolak tetap menjadi arsip
            $q->where('status_pengajuan', 'Ditolak')

                // Yang diterima tetapi sudah selesai
                ->orWhere(function ($x) {

                    $x->where('status_pengajuan', 'Diterima')
                        ->where('status_penerima', 'Tidak Aktif');

                })

                // Yang diterima dan masih aktif
                ->orWhere(function ($x) {

                    $x->where('status_pengajuan', 'Diterima')
                        ->where('status_penerima', 'Aktif')

                        // Bukan Duafa
                        ->whereNotIn('kategori_bantuan', [
                            'Duafa Anak',
                            'Duafa Lansia'
                        ]);

                })

                // Duafa yang BELUM masuk masa pembaruan
                ->orWhere(function ($x) {

                    $x->whereIn('kategori_bantuan', [
                        'Duafa Anak',
                        'Duafa Lansia'
                    ])
                        ->where('status_pengajuan', 'Diterima')
                        ->where('status_penerima', 'Aktif')
                        ->whereDate('tanggal_pembaruan_dokumen', '>', now());

                });

        });
        if ($request->filled('cari')) {

            $query->where(function ($q) use ($request) {

                $q->where('nama_lengkap', 'like', '%' . $request->cari . '%')
                    ->orWhere('id_pengajuan', 'like', '%' . $request->cari . '%');

            });

        }

        if ($request->filled('kategori')) {

            $query->where(
                'kategori_bantuan',
                $request->kategori
            );

        }

        if ($request->filled('status')) {

    if ($request->status == 'Selesai') {

    $query->where('status_pengajuan', 'Diterima')
          ->where('status_penerima', 'Tidak Aktif')
          ->where(function ($q) {

              $q->whereIn('kategori_bantuan', [
                  'Yatim',
                  'Piatu',
                  'Yatim-Piatu'
              ])

              ->orWhere(function ($x) {

                  $x->where('kategori_bantuan', 'Duafa Anak')
                    ->whereNotNull('tanggal_lahir')
                    ->whereDate('tanggal_lahir', '<=', now()->subYears(18));

              });

          });
    } else {

        $query->where('status_pengajuan', $request->status);

    }

}

        $arsip = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // ===============================
// STATISTIK (menggunakan query yang sama dengan tabel)
// ===============================

$baseQuery = clone $query;

$totalArsip = $baseQuery->count();

$totalDisetujui = (clone $query)
    ->where('status_pengajuan', 'Diterima')
    ->count();

$totalDitolak = (clone $query)
    ->where('status_pengajuan', 'Ditolak')
    ->count();

$totalSelesai = Pengajuan::where('status_pengajuan', 'Diterima')
    ->where('status_penerima', 'Tidak Aktif')
    ->where(function ($q) {

        $q->whereIn('kategori_bantuan', [
            'Yatim',
            'Piatu',
            'Yatim-Piatu'
        ])

        ->orWhere(function ($x) {

            $x->where('kategori_bantuan', 'Duafa Anak')
              ->whereDate('tanggal_lahir', '<=', now()->subYears(18));

        });

    })
    ->count();

$persenDisetujui = $totalArsip
    ? round(($totalDisetujui / $totalArsip) * 100)
    : 0;

$persenDitolak = $totalArsip
    ? round(($totalDitolak / $totalArsip) * 100)
    : 0;

$persenSelesai = $totalArsip
    ? round(($totalSelesai / $totalArsip) * 100)
    : 0;

        return view(
            'admin.arsippengajuan',
            compact(
                'arsip',
                'totalArsip',
                'totalDisetujui',
                'totalDitolak',
                'totalSelesai',
                'persenDisetujui',
                'persenDitolak',
                'persenSelesai'
            )
        );
    }

    public function donasi()
    {
        // Data Donatur (tetap)
        $donatur = User::where('role', 'donatur')
            ->latest()
            ->take(5)
            ->get();

        $totalDonatur = User::where('role', 'donatur')->count();

        // Data Donasi
        $donasis = Donasi::with(['user', 'program'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.donasi',
            compact(
                'donatur',
                'totalDonatur',
                'donasis'
            )
        );
    }

    public function storeDonatur(Request $request)
    {
        $request->validate(

            [

                'name' => [
                    'required',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-z\s]+$/',
                    'unique:users,name'
                ],

                'phone' => [
                    'required',
                    'regex:/^08[0-9]{8,13}$/',
                    'unique:users,phone'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:100',
                    'unique:users,email'
                ],

                'password' => [

                    'required',
                    'min:8',
                    'max:20',
                    'confirmed'

                ],
            ],

            [

                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'name.regex' => 'Nama hanya boleh huruf dan spasi',
                'name.min' => 'Nama minimal 3 karakter.',
                'name.max' => 'Nama maksimal 100 karakter.',

                'phone.required' => 'Nomor HP wajib diisi.',
                'phone.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-15 digit.',
                'phone.unique' => 'Nomor HP sudah digunakan.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email maksimal 100 karakter.',
                'email.unique' => 'Email sudah digunakan.',

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.max' => 'Password maksimal 20 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sama.',
            ]

        );

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'donatur',
            'password' => bcrypt($request->password),
        ]);

        return back()->with(

            'success',
            'Akun donatur berhasil ditambahkan.'

        );
    }

    public function updateDonatur(Request $request, $id)
    {
        $request->validate([

            'name' => [
                'required',
                'min:3',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email,' . $id
            ],

            'phone' => [
                'required',
                'digits_between:10,15',
                'unique:users,phone,' . $id
            ],

        ]);

        $donatur = User::findOrFail($id);

        $donatur->name = $request->name;
        $donatur->email = $request->email;
        $donatur->phone = $request->phone;
        $donatur->save();

        return back()->with(
            'success',
            'Data donatur berhasil diperbarui.'
        );
    }

    public function resetPasswordDonatur(Request $request, $id)
    {
        $request->validate(

            [

                'password' => [

                    'required',
                    'min:8',
                    'max:20',
                    'confirmed'

                ],

            ],

            [

                'password.required' =>
                    'Password wajib diisi.',

                'password.min' =>
                    'Password minimal 8 karakter.',

                'password.max' =>
                    'Password maksimal 20 karakter.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',

            ]

        );

        $donatur = User::findOrFail($id);

        if (\Hash::check($request->password, $donatur->password)) {

            return back()->withErrors([

                'password' =>
                    'Password baru tidak boleh sama dengan password lama.'

            ]);

        }

        $donatur->password = bcrypt($request->password);

        $donatur->save();

        return back()->with(

            'success',

            'Password donatur berhasil direset.'

        );
    }


    public function semuaDonatur(Request $request)
    {
        $query = User::where('role', 'donatur');

        if ($request->filled('cari')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->cari . '%'
                )
                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $request->cari . '%'
                    );

            });

        }

        if ($request->filled('status')) {

            $query->where(
                'status_donatur',
                $request->status
            );

        }

        $donatur = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.semua-donatur',
            compact('donatur')
        );
    }

    public function laporan(Request $request)
    {
        $query = Laporan::query();

        if ($request->filled('cari')) {

            $query->where(
                'periode',
                'like',
                '%' . $request->cari . '%'
            );

        }

        $laporans = $query
            ->latest()
            ->paginate(10);

        foreach ($laporans as $laporan) {

            $tahun = date('Y', strtotime($laporan->periode));
            $bulan = date('m', strtotime($laporan->periode));

            $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            $laporan->dana_masuk = Donasi::where('status', 'Terverifikasi')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->sum('nominal');

            $laporan->jumlah_yatim = Pengajuan::where('kategori_bantuan', 'Yatim')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirBulan)
                ->where(function ($q) use ($awalBulan) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalBulan);
                })
                ->count();

            $laporan->jumlah_piatu = Pengajuan::where('kategori_bantuan', 'Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirBulan)
                ->where(function ($q) use ($awalBulan) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalBulan);
                })
                ->count();

            $laporan->jumlah_yatim_piatu = Pengajuan::where('kategori_bantuan', 'Yatim-Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirBulan)
                ->where(function ($q) use ($awalBulan) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalBulan);
                })
                ->count();

            $laporan->jumlah_duafa_anak = Pengajuan::where('kategori_bantuan', 'Duafa Anak')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirBulan)
                ->where(function ($q) use ($awalBulan) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalBulan);
                })
                ->count();

            $laporan->jumlah_duafa_lansia = Pengajuan::where('kategori_bantuan', 'Duafa Lansia')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirBulan)
                ->where(function ($q) use ($awalBulan) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalBulan);
                })
                ->count();
        }

        return view(
            'admin.laporan',
            compact('laporans')
        );
    }

    public function laporanTahunan(Request $request)
    {
        $query = LaporanTahunan::query();

        if ($request->filled('cari')) {

            $query->where(
                'periode',
                'like',
                '%' . $request->cari . '%'
            );

        }

        $laporans = $query
            ->latest()
            ->paginate(10);

        foreach ($laporans as $laporan) {

            $tahun = $laporan->periode;

            $awalTahun = Carbon::create($tahun, 1, 1)->startOfYear();

            $akhirTahun = Carbon::create($tahun, 12, 31)->endOfYear();

            $laporan->dana_masuk = Donasi::where('status', 'Terverifikasi')
                ->whereYear('created_at', $tahun)
                ->sum('nominal');

            $tahun = $laporan->periode;

            $laporan->dana_keluar = Laporan::where(
                'periode',
                'like',
                $tahun . '-%'
            )->sum('dana_keluar');

            $laporan->jumlah_yatim = Pengajuan::where('kategori_bantuan', 'Yatim')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {

                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);

                })
                ->count();

            $laporan->jumlah_piatu = Pengajuan::where('kategori_bantuan', 'Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {

                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);

                })
                ->count();

            $laporan->jumlah_yatim_piatu = Pengajuan::where('kategori_bantuan', 'Yatim-Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {

                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);

                })
                ->count();

            $laporan->jumlah_duafa_anak = Pengajuan::where('kategori_bantuan', 'Duafa Anak')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {

                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);

                })
                ->count();

            $laporan->jumlah_duafa_lansia = Pengajuan::where('kategori_bantuan', 'Duafa Lansia')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {

                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);

                })
                ->count();
        }

        return view(
            'admin.laporan-tahunan',
            compact('laporans')
        );
    }

    public function storeLaporanTahunan(Request $request)
    {
        $request->validate([

            'periode' => 'required|unique:laporan_tahunans,periode',

            'dokumen' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',

            'keterangan' => 'nullable|max:500',

        ]);

        $path = $request->file('dokumen')
            ->store('laporan-tahunan', 'public');

        \App\Models\LaporanTahunan::create([

            'periode' => $request->periode,

            'dokumen' => $path,

            'keterangan' => $request->keterangan,

            'status' => 'Draft',

        ]);

        return back()->with(
            'success',
            'Laporan tahunan berhasil ditambahkan.'
        );
    }

    public function updateLaporanTahunan(Request $request, $id)
    {
        $request->validate(

            [

                'periode' => [
                    'required',
                    'unique:laporan_tahunans,periode,' . $id
                ],

                'dokumen' => [
                    'nullable',
                    'mimes:pdf,jpg,jpeg,png',
                    'max:5120'
                ],

                'keterangan' => [
                    'nullable',
                    'max:500'
                ],

            ],

            [

                'periode.required' => 'Tahun wajib diisi.',
                'periode.unique' => 'Laporan tahun tersebut sudah ada.',

                'dokumen.mimes' =>
                    'Dokumen harus berupa PDF, JPG, JPEG, atau PNG.',

                'dokumen.max' =>
                    'Ukuran dokumen maksimal 5 MB.',

                'keterangan.max' =>
                    'Keterangan maksimal 500 karakter.',

            ]

        );

        $laporan = LaporanTahunan::findOrFail($id);

        $laporan->periode = $request->periode;

        $laporan->keterangan = $request->keterangan;

        if ($request->hasFile('dokumen')) {

            if (
                $laporan->dokumen &&
                \Storage::disk('public')->exists($laporan->dokumen)
            ) {

                \Storage::disk('public')->delete($laporan->dokumen);

            }

            $laporan->dokumen = $request
                ->file('dokumen')
                ->store('laporan_tahunan', 'public');

        }

        $laporan->save();

        return back()->with(
            'success',
            'Laporan tahunan berhasil diperbarui.'
        );
    }

    public function hapusLaporanTahunan($id)
    {
        $laporan = LaporanTahunan::findOrFail($id);

        if (
            $laporan->dokumen &&
            \Storage::disk('public')->exists($laporan->dokumen)
        ) {

            \Storage::disk('public')->delete($laporan->dokumen);

        }

        $laporan->delete();

        return back()->with(
            'success',
            'Laporan tahunan berhasil dihapus.'
        );
    }

    public function publishLaporanTahunan($id)
    {
        $laporan = LaporanTahunan::findOrFail($id);

        $laporan->status = 'Published';

        $laporan->published_at = now();

        $laporan->save();

        return back()->with(
            'success',
            'Laporan tahunan berhasil dipublish.'
        );
    }

    public function storeLaporan(Request $request)
    {
        $request->validate(

            [

                'periode' => [

                    'required',

                    'unique:laporans,periode'

                ],

                'dana_keluar' => [

                    'required',

                    'numeric',

                    'min:0'

                ],

                'dokumen' => [

                    'required',

                    'mimes:pdf,jpg,jpeg,png',

                    'max:5120'

                ],

                'keterangan' => [

                    'nullable',

                    'max:500'

                ],

            ],

            [

                'periode.required' =>
                    'Periode wajib dipilih.',

                'periode.unique' =>
                    'Laporan periode tersebut sudah ada.',

                'dana_keluar.required' =>
                    'Dana keluar wajib diisi.',

                'dana_keluar.numeric' =>
                    'Dana keluar harus berupa angka.',

                'dana_keluar.min' =>
                    'Dana keluar tidak boleh kurang dari 0.',

                'dokumen.required' =>
                    'Dokumen wajib diunggah.',

                'dokumen.mimes' =>
                    'Dokumen hanya boleh PDF, JPG, JPEG, atau PNG.',

                'dokumen.max' =>
                    'Ukuran dokumen maksimal 5 MB.',

                'keterangan.max' =>
                    'Keterangan maksimal 500 karakter.',

            ]

        );

        $path = $request->file('dokumen')
            ->store('laporan', 'public');

        $tahun = date('Y', strtotime($request->periode));
        $bulan = date('m', strtotime($request->periode));

        $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();

        $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $danaMasuk = Donasi::where('status', 'Terverifikasi')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('nominal');

        $jumlahYatim = Pengajuan::where('kategori_bantuan', 'Yatim')
            ->where('status_penerima', 'Aktif')
            ->where('tanggal_mulai', '<=', $akhirBulan)
            ->where(function ($q) use ($awalBulan) {

                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $awalBulan);

            })
            ->count();

        $jumlahPiatu = Pengajuan::where('kategori_bantuan', 'Piatu')
            ->where('status_penerima', 'Aktif')
            ->where('tanggal_mulai', '<=', $akhirBulan)
            ->where(function ($q) use ($awalBulan) {

                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $awalBulan);

            })
            ->count();

        $jumlahYatimPiatu = Pengajuan::where('kategori_bantuan', 'Yatim-Piatu')
            ->where('status_penerima', 'Aktif')
            ->where('tanggal_mulai', '<=', $akhirBulan)
            ->where(function ($q) use ($awalBulan) {

                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $awalBulan);

            })
            ->count();

        $jumlahDuafaAnak = Pengajuan::where('kategori_bantuan', 'Duafa Anak')
            ->where('status_penerima', 'Aktif')
            ->where('tanggal_mulai', '<=', $akhirBulan)
            ->where(function ($q) use ($awalBulan) {

                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $awalBulan);

            })
            ->count();

        $jumlahDuafaLansia = Pengajuan::where('kategori_bantuan', 'Duafa Lansia')
            ->where('status_penerima', 'Aktif')
            ->where('tanggal_mulai', '<=', $akhirBulan)
            ->where(function ($q) use ($awalBulan) {

                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $awalBulan);

            })
            ->count();

        Laporan::create([
            'periode' => $request->periode,
            'dana_keluar' => $request->dana_keluar,
            'dokumen' => $path,
            'keterangan' => $request->keterangan,
            'status' => 'Draft',
        ]);

        return back()->with(

            'success',

            'Laporan berhasil ditambahkan.'

        );
    }

    public function publishLaporan($id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->status = 'Published';

        $laporan->published_at = now();

        $laporan->save();

        return back()->with(
            'success',
            'Laporan berhasil dipublish.'
        );
    }

    public function updateLaporan(Request $request, $id)
    {
        $request->validate(

            [

                'periode' => [

                    'required',

                    'unique:laporans,periode,' . $id

                ],

                'dana_keluar' => [

                    'required',

                    'numeric',

                    'min:0'

                ],

                'dokumen' => [

                    'nullable',

                    'mimes:pdf,jpg,jpeg,png',

                    'max:5120'

                ],

                'keterangan' => [

                    'nullable',

                    'max:500'

                ],

            ]

        );

        $laporan = Laporan::findOrFail($id);

        $laporan->periode = $request->periode;

        $laporan->dana_keluar = $request->dana_keluar;

        $laporan->keterangan = $request->keterangan;

        if ($request->hasFile('dokumen')) {

            if (
                $laporan->dokumen &&
                \Storage::disk('public')->exists($laporan->dokumen)
            ) {

                \Storage::disk('public')->delete($laporan->dokumen);

            }

            $laporan->dokumen = $request
                ->file('dokumen')
                ->store('laporan', 'public');

        }

        $laporan->save();

        return back()->with(

            'success',

            'Laporan berhasil diperbarui.'

        );
    }

    public function hapusLaporan($id)
    {
        $laporan = Laporan::findOrFail($id);

        // hapus file dokumen
        if (
            $laporan->dokumen &&
            \Storage::disk('public')->exists($laporan->dokumen)
        ) {

            \Storage::disk('public')->delete($laporan->dokumen);

        }

        $laporan->delete();

        return back()->with(

            'success',

            'Laporan berhasil dihapus.'

        );
    }



    public function semuaKegiatan(Request $request)
    {
        $query = Kegiatan::query();

        if ($request->filled('cari')) {

            $query->where(
                'judul',
                'like',
                '%' . $request->cari . '%'
            );

        }

        $kegiatans = $query
            ->latest()
            ->paginate(10);

        return view(
            'admin.semua-kegiatan',
            compact('kegiatans')
        );
    }

    public function verifikasiDonasi(Request $request, $id)
    {
        $request->validate(
            [
                'nominal' => [
                    'required',
                    'numeric',
                    'min:1000'
                ]
            ],
            [
                'nominal.required' => 'Nominal donasi wajib diisi.',
                'nominal.numeric' => 'Nominal harus berupa angka.',
                'nominal.min' => 'Nominal minimal Rp1.000.'
            ]
        );

        $donasi = Donasi::findOrFail($id);

        $donasi->update([

            'nominal' => $request->nominal,

            'status' => 'Terverifikasi',

        ]);

        $user = User::find($donasi->user_id);

        $user->update([
            'status_donatur' => 'Aktif',
            'last_donation_at' => now(),
        ]);

        return back()->with(
            'success',
            'Donasi berhasil diverifikasi.'
        );
    }
    public function terimaPembaruan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status_pembaruan != 'Menunggu') {
            return back()->with(
                'warning',
                'Pembaruan dokumen sudah diproses.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PINDAHKAN FILE PEMBARUAN MENJADI FILE UTAMA
        |--------------------------------------------------------------------------
        */

        $files = [
    'file_kk',
    'file_sktm',
    'file_domisili',
    'file_bukti_rumah',
];
        foreach ($files as $file) {

            $fieldPembaruan = $file . '_pembaruan';

            if (!empty($pengajuan->$fieldPembaruan)) {

                $pengajuan->$file = $pengajuan->$fieldPembaruan;

                $pengajuan->$fieldPembaruan = null;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | PERPANJANG MASA BANTUAN
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| PERPANJANG MASA BANTUAN
|--------------------------------------------------------------------------
| Periode baru dihitung dari tanggal selesai bantuan sebelumnya,
| bukan dari tanggal admin menyetujui pembaruan.
|--------------------------------------------------------------------------
*/

        $mulaiBaru = Carbon::parse($pengajuan->tanggal_selesai)->addDay();

        $selesaiBaru = $mulaiBaru->copy()->addMonths(6)->subDay();

        $pengajuan->tanggal_mulai = $mulaiBaru;
        $pengajuan->tanggal_selesai = $selesaiBaru;

        /*
        |--------------------------------------------------------------------------
        | Jadwal pembaruan berikutnya
        |--------------------------------------------------------------------------
        | Tetap 30 hari sebelum masa bantuan berakhir
        |--------------------------------------------------------------------------
        */

        $pengajuan->tanggal_pembaruan_dokumen =
            $selesaiBaru->copy()->subDays(30);

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $pengajuan->status_pengajuan = 'Diterima';
        $pengajuan->status_pembaruan = 'Diterima';
        $pengajuan->status_penerima = 'Aktif';

        $pengajuan->tanggal_pengajuan_pembaruan = null;

        $pengajuan->tanggal_keputusan = now();

        $pengajuan->catatan_admin = null;

        $pengajuan->save();

        return redirect()
            ->route('admin.pengajuan')
            ->with(
                'success',
                'Pembaruan dokumen berhasil disetujui.'
            );
    }
    public function tolakPembaruan(Request $request, $id)
    {
        $request->validate(
            [
                'alasan_penolakan' => 'required|min:20|max:255'
            ],
            [
                'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
                'alasan_penolakan.min' => 'Alasan minimal 20 karakter.',
                'alasan_penolakan.max' => 'Alasan maksimal 255 karakter.'
            ]
        );

        $pengajuan = Pengajuan::findOrFail($id);


        if ($pengajuan->status_pembaruan != 'Menunggu') {
            return back()->with(
                'warning',
                'Pembaruan dokumen sudah diproses.'
            );
        }

        // pembaruan ditolak
        $pengajuan->status_pembaruan = 'Ditolak';

        // bantuan selesai
        $pengajuan->status_penerima = 'Tidak Aktif';

        // status utama tetap selesai, bukan aktif lagi
        $pengajuan->tanggal_selesai = now();

        // wajib menunggu 30 hari
        $pengajuan->tanggal_boleh_ajukan = now()->addDays(30);

        $pengajuan->tanggal_keputusan = now();

        $pengajuan->catatan_admin = $request->alasan_penolakan;

        // reset proses pembaruan
        $pengajuan->tanggal_pengajuan_pembaruan = null;
        $files = [
    'file_kk',
    'file_sktm',
    'file_domisili',
    'file_bukti_rumah',
];

        foreach ($files as $file) {

            $field = $file . '_pembaruan';

            $pengajuan->$field = null;

        }

        $pengajuan->save();


        return redirect()
            ->route('admin.pengajuan')
            ->with(
                'success',
                'Pembaruan dokumen berhasil ditolak.'
            );
    }

    private function resetStatusPembaruan()
{
    Pengajuan::whereIn('kategori_bantuan', [
            'Duafa Anak',
            'Duafa Lansia'
        ])
        ->where('status_penerima', 'Aktif')
        ->where('status_pembaruan', 'Diterima')
        ->whereDate('tanggal_pembaruan_dokumen', '<=', now())
        ->update([
            'status_pembaruan' => 'Belum'
        ]);
}
}