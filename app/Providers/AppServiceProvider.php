<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kecamatansetting;
use App\Models\Konten;
use App\Models\Pengaduan;
use App\Models\SuratKeteranganMiskin;
use App\Models\Pegawai;
use App\Models\Masyarakat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        view()->composer('*', function ($view) {

            // ── Kecamatan settings (cached 1 jam) ─────────────────
            $settings = Cache::remember('kecamatan_settings', 3600, function () {
                return Kecamatansetting::first() ?? new Kecamatansetting([
                    'logo' => 'defaultimage/default_logo.png'
                ]);
            });

            $view->with('settings', $settings);

            // ── Default semua badge = 0 ───────────────────────────
            $pendingKonten         = 0;
            $pendingPengaduan      = 0;
            $pendingSurat          = 0;
            $pendingKontenByJenis  = collect();
            $pendingKontenCombined = 0;
            $pendingPotensi        = 0;

            $user = Auth::user();
            if (! $user) return;

            // ══════════════════════════════════════════════════════
            // Tentukan peran & scope akses
            //
            //  camat             → superadmin, lihat semua
            //  pegawai, nagari null → staf camat = superadmin
            //  pegawai, ada nagari  → wali/staf nagari = scope se-nagari
            //  masyarakat           → hanya milik sendiri
            // ══════════════════════════════════════════════════════

            $isSuperAdmin   = false;
            $allowedUserIds = null; // null = tidak dibatasi (superadmin)

            if ($user->role === 'camat') {
                $isSuperAdmin = true;
            } elseif ($user->role === 'pegawai') {
                $nagariId = $user->pegawai?->id_nagari;

                if (is_null($nagariId)) {
                    // Staf camat → superadmin
                    $isSuperAdmin = true;
                } else {
                    // Wali / staf nagari → scope ke user se-nagari
                    $allowedUserIds = Cache::remember(
                        'allowed_users_nagari_' . $nagariId,
                        300,
                        fn() => collect()
                            ->merge(Pegawai::where('id_nagari', $nagariId)->pluck('id_user'))
                            ->merge(Masyarakat::where('id_nagari', $nagariId)->pluck('id_user'))
                            ->push($user->id)
                            ->unique()
                            ->values()
                            ->all()
                    );
                }
            } elseif ($user->role === 'masyarakat') {
                $allowedUserIds = [$user->id];
            }

            // ── Helper: query pending konten dengan scope peran ───
            $pendingQuery = function (string $jenis) use ($isSuperAdmin, $allowedUserIds) {
                $q = Konten::where('jenis_konten', $jenis)
                    ->where('status', 'pending');

                if (! $isSuperAdmin && $allowedUserIds !== null) {
                    $q->whereIn('id_user', $allowedUserIds);
                }

                return $q->count();
            };

            // ── Pending konten per jenis (cached 60 detik per user)
            $pendingKontenByJenis = Cache::remember(
                'pending_konten_by_jenis_' . $user->id,
                60,
                fn() => collect([
                    'berita'           => $pendingQuery('berita'),
                    'artikel'          => $pendingQuery('artikel'),
                    'seni_tari'        => $pendingQuery('seni_tari'),
                    'makanan_daerah'   => $pendingQuery('makanan_daerah'),
                    'kerajinan_daerah' => $pendingQuery('kerajinan_daerah'),
                    'seni_musik'       => $pendingQuery('seni_musik'),
                    'seni_budaya'      => $pendingQuery('seni_budaya'),
                    'pariwisata'       => $pendingQuery('pariwisata'),
                    'pertanian'        => $pendingQuery('pertanian'),
                ])
            );

            // ── Hitung badge menu induk ───────────────────────────
            $pendingKontenCombined =
                ($pendingKontenByJenis['berita']  ?? 0) +
                ($pendingKontenByJenis['artikel'] ?? 0);

            $pendingPotensi =
                ($pendingKontenByJenis['seni_tari']        ?? 0) +
                ($pendingKontenByJenis['makanan_daerah']   ?? 0) +
                ($pendingKontenByJenis['kerajinan_daerah'] ?? 0) +
                ($pendingKontenByJenis['seni_musik']       ?? 0) +
                ($pendingKontenByJenis['seni_budaya']      ?? 0) +
                ($pendingKontenByJenis['pariwisata']       ?? 0) +
                ($pendingKontenByJenis['pertanian']        ?? 0);

            $pendingKonten = $pendingKontenCombined + $pendingPotensi;

            // ── Pengaduan & Surat: hanya camat / pegawai ─────────
            if (in_array($user->role, ['camat', 'pegawai'])) {

                $pendingPengaduan = Cache::remember(
                    'pending_pengaduan_' . $user->id,
                    60,
                    function () use ($isSuperAdmin, $allowedUserIds) {
                        $q = Pengaduan::where('status', 'pending');
                        if (! $isSuperAdmin && $allowedUserIds !== null) {
                            $q->whereIn('id_masyarakat', function ($sub) use ($allowedUserIds) {
                                $sub->select('id_masyarakat')
                                    ->from('masyarakat')
                                    ->whereIn('id_user', $allowedUserIds);
                            });
                        }
                        return $q->count();
                    }
                );

                $pendingSurat = Cache::remember(
                    'pending_surat_' . $user->id,
                    60,
                    function () use ($isSuperAdmin, $allowedUserIds) {
                        $q = SuratKeteranganMiskin::where('status', 'pending');
                        if (! $isSuperAdmin && $allowedUserIds !== null) {
                            $q->whereIn('id_masyarakat', function ($sub) use ($allowedUserIds) {
                                $sub->select('id_masyarakat')
                                    ->from('masyarakat')
                                    ->whereIn('id_user', $allowedUserIds);
                            });
                        }
                        return $q->count();
                    }
                );
            }

            $view->with(compact(
                'pendingKonten',
                'pendingPengaduan',
                'pendingSurat',
                'pendingKontenByJenis',
                'pendingKontenCombined',
                'pendingPotensi'
            ));
        });
    }
}
