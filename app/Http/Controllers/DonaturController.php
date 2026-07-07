<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Donasi;
use App\Models\Program;
use App\Models\User;
use App\Models\Pengajuan;
use Carbon\Carbon;
use App\Models\Laporan;
use App\Models\LaporanTahunan;

class DonaturController extends Controller
{
    public function profil()
    {
        $user = auth()->user();

        return view(
            'donatur.profil',
            compact('user')
        );
    }

    public function update(Request $request)
    {
        $request->validate(

            [

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
                    'unique:users,email,' . auth()->id()
                ],

                'phone' => [
                    'required',
                    'regex:/^08[0-9]{8,13}$/',
                    'unique:users,phone,' . auth()->id()
                ],

                'kota' => [
                    'required',
                    'min:3',
                    'max:50',
                    'regex:/^[A-Za-z0-9\s]+$/'
                ],

            ],

            [

                'name.required' => 'Nama wajib diisi.',
                'name.regex' => 'Nama hanya boleh huruf.',
                'name.min' => 'Nama minimal 3 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',

                'phone.required' => 'Nomor HP wajib diisi.',
                'phone.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10-15 digit.',
                'phone.unique' => 'Nomor HP sudah digunakan.',

                'kota.required' => 'Kota wajib diisi.',
                'kota.regex' => 'Kota hanya boleh huruf dan angka.',
                'kota.min' => 'Kota minimal 3 karakter.',
                'kota.max' => 'Kota maksimal 50 karakter.',
            ]

        );

        $user = auth()->user();

        $user->name = $request->name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->kota = $request->kota;

        $user->save();

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }

    public function updateFoto(Request $request)
    {
        $request->validate(

            [

                'foto' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048'
                ]

            ],

            [

                'foto.required' => 'Silakan pilih foto.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Foto hanya boleh JPG, JPEG, PNG, atau WEBP.',
                'foto.max' => 'Ukuran foto maksimal 2 MB.',

            ]

        );

        $user = auth()->user();

        // hapus foto lama
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {

            Storage::disk('public')->delete($user->foto);

        }

        // simpan foto baru
        $namaFile = Str::uuid() . '.' . $request->foto->extension();

        $path = $request->foto->storeAs(
            'profil',
            $namaFile,
            'public'
        );

        $user->foto = $path;

        $user->save();

        return back()->with(
            'success',
            'Foto profil berhasil diperbarui.'
        );
    }

    public function updatePassword(Request $request)
    {
        $request->validate(

            [

                'current_password' => [
                    'required'
                ],

                'password' => [
                    'required',
                    'min:8',
                    'max:20',
                    'confirmed',
                    'different:current_password'
                ],

            ],

            [

                'current_password.required' =>
                    'Password lama wajib diisi.',

                'password.required' =>
                    'Password baru wajib diisi.',

                'password.min' =>
                    'Password minimal 8 karakter.',

                'password.max' =>
                    'Password maksimal 20 karakter.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',

                'password.different' =>
                    'Password baru tidak boleh sama dengan password lama.',

            ]

        );

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([

                'current_password' =>
                    'Password lama salah.'

            ]);

        }

        $user->password = Hash::make($request->password);

        $user->save();

        return back()->with(

            'success',

            'Password berhasil diubah.'

        );
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if (!$user->last_donation_at) {

            $user->update([
                'status_donatur' => 'Tidak Aktif',
            ]);

        } elseif (
            Carbon::parse($user->last_donation_at)
                ->addDays(30)
                ->isPast()
        ) {

            $user->update([
                'status_donatur' => 'Tidak Aktif',
            ]);

        } else {

            $user->update([
                'status_donatur' => 'Aktif',
            ]);

        }
        $query = Donasi::with('program')
            ->where('user_id', auth()->id());

        // pencarian
        if ($request->filled('cari')) {

            $query->whereHas('program', function ($q) use ($request) {

                $q->where(
                    'nama',
                    'like',
                    '%' . $request->cari . '%'
                );

            });

        }

        $donasis = $query
            ->latest()
            ->take(5)
            ->get();

        $totalDonasi = Donasi::where('user_id', auth()->id())
            ->where('status', 'Terverifikasi')
            ->sum('nominal');

        $donasiTerakhir = Donasi::where('user_id', auth()->id())
            ->latest()
            ->first();

        $programs = Program::where('status', 1)
            ->latest()
            ->get();

        $totalProgram = Program::where('status', 1)->count();

        $totalDonatur = User::where('role', 'donatur')->count();

        $totalPenerima = Pengajuan::where(
            'status_penerima',
            'Aktif'
        )->count();

        return view(
            'donatur.dashboard',
            compact(
                'donasis',
                'totalDonasi',
                'donasiTerakhir',
                'programs',
                'totalProgram',
                'totalDonatur',
                'totalPenerima'
            )
        );
    }

    public function donasi()
    {
        $programs = Program::where('status', 1)->get();

        return view(
            'donatur.donasi',
            compact('programs')
        );
    }

    public function storeDonasi(Request $request)
    {
        $request->validate(

            [

                'program_id' => [
                    'required',
                    'exists:programs,id'
                ],

                'bukti_transfer' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048'
                ],

                'catatan' => [
                    'nullable',
                    'max:255'
                ],

            ],

            [

                'program_id.required' =>
                    'Silakan pilih program.',

                'program_id.exists' =>
                    'Program tidak ditemukan.',

                'bukti_transfer.required' =>
                    'Bukti transfer wajib diunggah.',

                'bukti_transfer.image' =>
                    'File harus berupa gambar.',

                'bukti_transfer.mimes' =>
                    'Format gambar harus JPG, JPEG, PNG, atau WEBP.',

                'bukti_transfer.max' =>
                    'Ukuran gambar maksimal 2 MB.',

                'catatan.max' =>
                    'Catatan maksimal 255 karakter.',

            ]

        );

        $path = $request->file('bukti_transfer')
            ->store('bukti-transfer', 'public');

        Donasi::create([

            'user_id' => auth()->id(),

            'program_id' => $request->program_id,

            'bukti_transfer' => $path,

            'catatan' => $request->catatan,

            'status' => 'Menunggu',

        ]);

        return back()->with(

            'success',

            'Bukti donasi berhasil dikirim.'

        );
    }

    public function riwayat(Request $request)
    {
        $query = Donasi::with('program')
            ->where('user_id', auth()->id());

        // Pencarian
        if ($request->filled('cari')) {

            $query->whereHas('program', function ($q) use ($request) {

                $q->where('nama', 'like', '%' . $request->cari . '%');

            });

        }

        // Filter Tahun
        if ($request->filled('tahun')) {

            $query->whereYear(
                'created_at',
                $request->tahun
            );

        }

        // Filter Status
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $donasis = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Ringkasan
        $totalDonasi = Donasi::where('user_id', auth()->id())->count();

        $donasiTerakhir = Donasi::where('user_id', auth()->id())
            ->latest()
            ->first();

        $totalNominal = Donasi::where('user_id', auth()->id())
            ->where('status', 'Terverifikasi')
            ->sum('nominal');

        $tahunList = Donasi::where('user_id', auth()->id())
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view(
            'donatur.riwayat',
            compact(
                'donasis',
                'totalDonasi',
                'totalNominal',
                'donasiTerakhir',
                'tahunList'
            )
        );
    }

    public function laporan()
    {
        $user = auth()->user();
        $user->refresh();

        if ($user->status_donatur !== 'Aktif') {

            return redirect()
                ->route('donatur.dashboard')
                ->with(
                    'error',
                    'Status donatur Anda tidak aktif. Silakan lakukan donasi kembali dan menunggu hasil verifikasi admin untuk mengakses laporan transparansi.'
                );
        }

        $laporanBulanan = Laporan::where(
            'status',
            'Published'
        )
            ->latest()
            ->take(5)
            ->get();

        foreach ($laporanBulanan as $laporan) {

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

        $laporanTahunan = LaporanTahunan::where(
            'status',
            'Published'
        )
            ->latest()
            ->take(5)
            ->get();

        foreach ($laporanTahunan as $laporan) {

            $tahun = $laporan->periode;

            $awalTahun = Carbon::create($tahun, 1, 1)->startOfYear();

            $akhirTahun = Carbon::create($tahun, 12, 31)->endOfYear();

            // Dana Masuk
            $laporan->dana_masuk = Donasi::where('status', 'Terverifikasi')
                ->whereYear('created_at', $tahun)
                ->sum('nominal');

            // Dana Keluar
            $laporan->dana_keluar = Laporan::where(
                'periode',
                'like',
                $tahun . '-%'
            )->sum('dana_keluar');

            // Yatim
            $laporan->jumlah_yatim = Pengajuan::where('kategori_bantuan', 'Yatim')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);
                })
                ->count();

            // Piatu
            $laporan->jumlah_piatu = Pengajuan::where('kategori_bantuan', 'Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);
                })
                ->count();

            // Yatim Piatu
            $laporan->jumlah_yatim_piatu = Pengajuan::where('kategori_bantuan', 'Yatim-Piatu')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);
                })
                ->count();

            // Duafa Anak
            $laporan->jumlah_duafa_anak = Pengajuan::where('kategori_bantuan', 'Duafa Anak')
                ->where('status_penerima', 'Aktif')
                ->where('tanggal_mulai', '<=', $akhirTahun)
                ->where(function ($q) use ($awalTahun) {
                    $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $awalTahun);
                })
                ->count();

            // Duafa Lansia
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
            'donatur.laporan',
            compact(
                'laporanBulanan',
                'laporanTahunan'
            )
        );
    }

    public function laporanBulanan(Request $request)
    {
        $user = auth()->user();
        $user->refresh();

        if ($user->status_donatur !== 'Aktif') {

            return redirect()
                ->route('donatur.dashboard')
                ->with(
                    'error',
                    'Status donatur Anda tidak aktif.'
                );
        }

        $query = Laporan::where(
            'status',
            'Published'
        );

        if ($request->filled('cari')) {

            $query->where(
                'periode',
                'like',
                '%' . $request->cari . '%'
            );

        }

        $laporanBulanan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalLaporan = Laporan::where(
            'status',
            'Published'
        )->count();

        $totalDanaMasuk = Donasi::where(
            'status',
            'Terverifikasi'
        )->sum('nominal');

        $totalDanaKeluar = Laporan::where(
            'status',
            'Published'
        )->sum('dana_keluar');

        foreach ($laporanBulanan as $laporan) {

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
            'donatur.laporan-bulanan',
            compact(
                'laporanBulanan',
                'totalLaporan',
                'totalDanaMasuk',
                'totalDanaKeluar'
            )
        );
    }

    public function laporanTahunanSemua()
    {
        $laporanTahunan = LaporanTahunan::where(
            'status',
            'Published'
        )
            ->latest()
            ->paginate(10);

        foreach ($laporanTahunan as $laporan) {

            $tahun = $laporan->periode;

            $awalTahun = Carbon::create($tahun, 1, 1)->startOfYear();

            $akhirTahun = Carbon::create($tahun, 12, 31)->endOfYear();

            $laporan->dana_masuk = Donasi::where('status', 'Terverifikasi')
                ->whereYear('created_at', $tahun)
                ->sum('nominal');

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
            'donatur.laporan-tahunan',
            compact('laporanTahunan')
        );
    }


}