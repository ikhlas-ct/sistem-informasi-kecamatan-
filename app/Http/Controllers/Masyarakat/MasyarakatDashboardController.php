<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;
use App\Models\Mading;
use App\Models\DokumenBersama;

class MasyarakatDashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $masyarakat = $user->masyarakat;
        $subRole   = $user->sekolah; // null | 'admin' | 'siswa'

        // ── Data bersama semua sub-role ─────────────────────────
        $dokumenDiterima = $user->dokumenDiterima()
            ->with('dokumen')
            ->latest()
            ->take(5)
            ->get();

        $totalDokumenDiterima  = $user->dokumenDiterima()->count();
        $dokumenBelumDibaca    = $user->dokumenDiterima()->where('sudah_dibaca', false)->count();

        // ── Data khusus per sub-role ────────────────────────────
        if ($subRole === 'admin') {
            return $this->dashboardAdminSekolah($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca);
        }

        if ($subRole === 'siswa') {
            return $this->dashboardSiswa($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca);
        }

        return $this->dashboardMasyarakatBiasa($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca);
    }

    // ──────────────────────────────────────────────────────────
    // MASYARAKAT BIASA
    // ──────────────────────────────────────────────────────────
    private function dashboardMasyarakatBiasa($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca)
    {
        $totalPengaduan   = Pengaduan::where('id_masyarakat', $masyarakat?->id_masyarakat)->count();
        $pengaduanProses  = Pengaduan::where('id_masyarakat', $masyarakat?->id_masyarakat)
                                ->where('status', 'proses')->count();
        $pengaduanSelesai = Pengaduan::where('id_masyarakat', $masyarakat?->id_masyarakat)
                                ->where('status', 'selesai')->count();

        $pengaduanTerbaru = Pengaduan::where('id_masyarakat', $masyarakat?->id_masyarakat)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('pages.masyarakat.dashboard', compact(
            'user', 'masyarakat',
            'totalPengaduan', 'pengaduanProses', 'pengaduanSelesai',
            'pengaduanTerbaru',
            'dokumenDiterima', 'totalDokumenDiterima', 'dokumenBelumDibaca',
        ));
    }

    // ──────────────────────────────────────────────────────────
    // ADMIN SEKOLAH
    // ──────────────────────────────────────────────────────────
    private function dashboardAdminSekolah($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca)
    {
        // Ambil data sekolah yang diasosiasikan lewat nagari/masyarakat
        // Sesuaikan relasi jika ada id_sekolah di masyarakat
        $totalMading        = Mading::count();
        $madingPending      = Mading::where('approval_status', 'pending')->count();
        $madingPublik       = Mading::where('status', 'publish')->where('approval_status', 'approved')->count();

        $madingTerbaru = Mading::with(['user', 'sekolah'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.masyarakat.dashboard', compact(
            'user', 'masyarakat',
            'totalMading', 'madingPending', 'madingPublik',
            'madingTerbaru',
            'dokumenDiterima', 'totalDokumenDiterima', 'dokumenBelumDibaca',
        ));
    }

    // ──────────────────────────────────────────────────────────
    // SISWA SEKOLAH
    // ──────────────────────────────────────────────────────────
    private function dashboardSiswa($user, $masyarakat, $dokumenDiterima, $totalDokumenDiterima, $dokumenBelumDibaca)
    {
        $madingSaya      = Mading::where('id_user', $user->id)->count();
        $madingPublikSaya = Mading::where('id_user', $user->id)
                                ->where('status', 'publish')
                                ->where('approval_status', 'approved')
                                ->count();
        $madingPendingSaya = Mading::where('id_user', $user->id)
                                ->where('approval_status', 'pending')
                                ->count();

        $madingTerbaru = Mading::where('id_user', $user->id)
            ->with('sekolah')
            ->latest()
            ->take(5)
            ->get();

        return view('pages.masyarakat.dashboard', compact(
            'user', 'masyarakat',
            'madingSaya', 'madingPublikSaya', 'madingPendingSaya',
            'madingTerbaru',
            'dokumenDiterima', 'totalDokumenDiterima', 'dokumenBelumDibaca',
        ));
    }
}
