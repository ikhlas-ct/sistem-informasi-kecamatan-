<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Masyarakat;
use App\Models\Nagari;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengaturanmasyarakatController extends Controller
{
    // ─────────────────────────────────────────
    // HELPER: ambil role label & data pegawai
    // ─────────────────────────────────────────

    private function getRl(): string
    {
        return Auth::user()->getRoleLabel();
    }

    /**
     * Pegawai yang sedang login (null jika camat).
     */
    private function getAp()
    {
        return Auth::user()->pegawai;
    }

    /**
     * Apakah user ini termasuk "super" (camat atau staf_camat)?
     * Mereka boleh memilih nagari secara bebas.
     */
    private function isSuperRole(): bool
    {
        return in_array($this->getRl(), ['camat', 'staf_camat']);
    }

    /**
     * Nagari yang dikunci untuk role nagari (wali_nagari / pegawai_nagari).
     * Mengembalikan null jika role adalah camat/staf_camat.
     */
    private function getLockedNagariId(): ?int
    {
        if ($this->isSuperRole()) return null;
        return $this->getAp()?->id_nagari;
    }

    // ─────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────

    public function index(Request $request)
    {
        $rl      = $this->getRl();
        $ap      = $this->getAp();
        $keyword = $request->get('search', ''); // FIX: tambah semicolon

        $query = Masyarakat::with(['user', 'nagari'])
            ->orderByDesc('created_at');

        // Batasi scope data sesuai role
        if (in_array($rl, ['wali_nagari', 'pegawai_nagari'])) {
            $query->where('id_nagari', $ap->id_nagari);
        }
        // camat & staf_camat bisa lihat semua

        // Filter opsional
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_masyarakat', 'like', "%$q%")
                    ->orWhere('nik', 'like', "%$q%")
                    ->orWhere('no_hp', 'like', "%$q%");
            });
        }

        if ($request->filled('id_nagari') && $this->isSuperRole()) {
            $query->where('id_nagari', $request->id_nagari);
        }

        $masyarakats = $query->paginate(15)->withQueryString();

        $nagaris = $this->isSuperRole()
            ? Nagari::orderBy('nama_nagari')->get()
            : collect();

        // Stat
        $totalAll      = Masyarakat::count();
        $totalNagariku = $ap ? Masyarakat::where('id_nagari', $ap->id_nagari)->count() : 0;

        return view('pages.pegawai.masyarakat.index', [
            'masyarakats'   => $masyarakats,
            'nagaris'       => $nagaris,
            'rl'            => $rl,
            'ap'            => $ap,
            'keyword'       => $keyword,
            'totalAll'      => $totalAll,
            'totalNagariku' => $totalNagariku,
        ]);
    }

    // ─────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────

    public function create()
    {
        $rl           = $this->getRl();
        $lockedNagari = $this->getLockedNagariId();
        $nagaris      = Nagari::orderBy('nama_nagari')->get();

        return view('pages.pegawai.masyarakat.create', compact(
            'rl',
            'lockedNagari',
            'nagaris'
        ));
    }

    // ─────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────

    public function store(Request $request)
    {
        $rl           = $this->getRl();
        $lockedNagari = $this->getLockedNagariId();

        // Tentukan id_nagari yang akan disimpan
        $idNagari = $lockedNagari ?? $request->id_nagari;

        $request->merge(['id_nagari_resolved' => $idNagari]);

        $rules = [
            'nama_masyarakat' => ['required', 'string', 'max:100'],
            'nik'             => ['required', 'digits:16', Rule::unique('masyarakat', 'nik')],
            'kk'              => ['nullable', 'digits:16'],
            'jenis_kelamin'   => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string', 'max:255'],
            'pekerjaan'       => ['nullable', 'string', 'max:100'],
            'deskripsi'       => ['nullable', 'string'],
            'instagram'       => ['nullable', 'url', 'max:255'],
            'twitter'         => ['nullable', 'url', 'max:255'],
            'facebook'        => ['nullable', 'url', 'max:255'],
            'foto_profil'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Validasi nagari hanya jika super role (bebas pilih)
        if ($this->isSuperRole()) {
            $rules['id_nagari'] = ['nullable', 'exists:nagari,id'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $idNagari) {
            // 1. Buat User
            $user = User::create([
                'nip_nik'  => $validated['nik'],
                'password' => Hash::make($validated['password']),
                'role'     => 'masyarakat',
                'status'   => 'aktif',
            ]);

            // 2. Upload foto
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')
                    ->store('masyarakat/foto', 'public');
            }

            // 3. Buat Masyarakat
            Masyarakat::create([
                'nama_masyarakat' => $validated['nama_masyarakat'],
                'nik'             => $validated['nik'],
                'kk'              => $validated['kk'] ?? null,
                'jenis_kelamin'   => $validated['jenis_kelamin'],
                'no_hp'           => $validated['no_hp'] ?? null,
                'alamat'          => $validated['alamat'] ?? null,
                'pekerjaan'       => $validated['pekerjaan'] ?? null,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'instagram'       => $validated['instagram'] ?? null,
                'twitter'         => $validated['twitter'] ?? null,
                'facebook'        => $validated['facebook'] ?? null,
                'foto_profil'     => $fotoPath,
                'id_user'         => $user->id,
                'id_nagari'       => $idNagari,
            ]);
        });

        return redirect()->route('camat.masyarakat.index')
            ->with('success', 'Masyarakat berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────

    public function show(Masyarakat $masyarakat)
    {
        $this->authorizeAccess($masyarakat);

        $rl = $this->getRl();
        return view('pages.pegawai.masyarakat.show', compact('masyarakat', 'rl'));
    }

    // ─────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────

    public function edit(Masyarakat $masyarakat)
    {
        $this->authorizeAccess($masyarakat);

        $rl           = $this->getRl();
        $lockedNagari = $this->getLockedNagariId();
        $nagaris      = Nagari::orderBy('nama_nagari')->get();

        return view('pages.pegawai.masyarakat.edit', compact(
            'masyarakat',
            'rl',
            'lockedNagari',
            'nagaris'
        ));
    }

    // ─────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────

    public function update(Request $request, Masyarakat $masyarakat)
    {
        $this->authorizeAccess($masyarakat);

        $rl           = $this->getRl();
        $lockedNagari = $this->getLockedNagariId();
        $idNagari     = $lockedNagari ?? $request->id_nagari;

        $rules = [
            'nama_masyarakat' => ['required', 'string', 'max:100'],
            'nik'             => ['required', 'digits:16', Rule::unique('masyarakat', 'nik')->ignore($masyarakat->id_masyarakat, 'id_masyarakat')],
            'kk'              => ['nullable', 'digits:16'],
            'jenis_kelamin'   => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string', 'max:255'],
            'pekerjaan'       => ['nullable', 'string', 'max:100'],
            'deskripsi'       => ['nullable', 'string'],
            'instagram'       => ['nullable', 'url', 'max:255'],
            'twitter'         => ['nullable', 'url', 'max:255'],
            'facebook'        => ['nullable', 'url', 'max:255'],
            'foto_profil'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'status'          => ['required', 'in:aktif,nonaktif'],
            'password'        => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($this->isSuperRole()) {
            $rules['id_nagari'] = ['nullable', 'exists:nagari,id'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $masyarakat, $idNagari) {
            // Update foto
            $fotoPath = $masyarakat->foto_profil;
            if ($request->hasFile('foto_profil')) {
                if ($fotoPath) Storage::disk('public')->delete($fotoPath);
                $fotoPath = $request->file('foto_profil')
                    ->store('masyarakat/foto', 'public');
            }

            // Update masyarakat
            $masyarakat->update([
                'nama_masyarakat' => $validated['nama_masyarakat'],
                'nik'             => $validated['nik'],
                'kk'              => $validated['kk'] ?? null,
                'jenis_kelamin'   => $validated['jenis_kelamin'],
                'no_hp'           => $validated['no_hp'] ?? null,
                'alamat'          => $validated['alamat'] ?? null,
                'pekerjaan'       => $validated['pekerjaan'] ?? null,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'instagram'       => $validated['instagram'] ?? null,
                'twitter'         => $validated['twitter'] ?? null,
                'facebook'        => $validated['facebook'] ?? null,
                'foto_profil'     => $fotoPath,
                'id_nagari'       => $idNagari,
            ]);

            // Update user (NIK sebagai nip_nik, status, password)
            $userUpdate = [
                'nip_nik' => $validated['nik'],
                'status'  => $validated['status'],
            ];
            if (!empty($validated['password'])) {
                $userUpdate['password'] = Hash::make($validated['password']);
            }
            $masyarakat->user->update($userUpdate);
        });

        return redirect()->route('camat.masyarakat.index')
            ->with('success', 'Data masyarakat berhasil diperbarui.');
    }

    // ─────────────────────────────────────────
    // UPDATE PASSWORD (endpoint terpisah)
    // ─────────────────────────────────────────

    public function updatePassword(Request $request, Masyarakat $masyarakat)
    {
        $this->authorizeAccess($masyarakat);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $masyarakat->user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // ─────────────────────────────────────────
    // TOGGLE STATUS
    // ─────────────────────────────────────────

    public function toggleStatus(Masyarakat $masyarakat)
    {
        $this->authorizeAccess($masyarakat);

        $user      = $masyarakat->user;
        $newStatus = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return back()->with('success', 'Status akun berhasil diubah.');
    }

    // ─────────────────────────────────────────
    // OTORISASI AKSES
    // ─────────────────────────────────────────

    /**
     * Pastikan user hanya bisa akses masyarakat sesuai wewenangnya.
     * - camat & staf_camat : semua masyarakat
     * - wali_nagari & pegawai_nagari : hanya masyarakat di nagari mereka
     */
    private function authorizeAccess(Masyarakat $masyarakat): void
    {
        if ($this->isSuperRole()) return;

        $lockedNagari = $this->getLockedNagariId();
        if ($masyarakat->id_nagari !== $lockedNagari) {
            abort(403, 'Anda tidak memiliki akses ke data masyarakat ini.');
        }
    }
}
