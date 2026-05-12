<?php

namespace App\Http\Controllers\Camat;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Konten;
use App\Models\Visitor;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Suratketeranganmiskin;
use Illuminate\Support\Facades\DB;


class CamatController extends Controller
{
    public function dashboard()
    {
      $visitorCounts = DB::table('visitors')
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
        ->whereDate('created_at', '>=', now()->subDays(29)->toDateString()) // 30 hari terakhir termasuk hari ini
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    $chartLabels = $visitorCounts->pluck('date')->toArray();
    $chartData = $visitorCounts->pluck('total')->toArray();


        $pegawaiCount        = User::where('role', 'pegawai')->count();
        $masyarakatCount     = User::where('role', 'masyarakat')->count();
        $totalVisitors       = Visitor::count();
        $todayVisitors       = Visitor::whereDate('created_at', today())->count();
        $totalPengaduan      = Pengaduan::count();
        $totalsuratketengan  = Suratketeranganmiskin::count();

        $kontenCounts = Konten::groupBy('jenis_konten')
            ->select('jenis_konten', DB::raw('count(*) as total'))
            ->pluck('total', 'jenis_konten')
            ->toArray();

        // Pastikan semua variabel dikirim
        return view('pages.camat.dashboard', compact(
            'pegawaiCount',
            'masyarakatCount',
            'totalVisitors',
            'todayVisitors',
            'totalPengaduan',
            'totalsuratketengan',
            'kontenCounts',
            'chartLabels',
            'chartData',
        ));
    }


}
