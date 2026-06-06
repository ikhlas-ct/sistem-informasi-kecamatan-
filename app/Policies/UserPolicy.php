<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // ─────────────────────────────────────────────
    // CEK ROLE DASAR (raw role)
    // ─────────────────────────────────────────────

    public function isCamat(User $user): bool
    {
        return $user->role === 'camat';
    }

    public function isPegawai(User $user): bool
    {
        return $user->role === 'pegawai';
    }

    public function isMasyarakat(User $user): bool
    {
        return $user->role === 'masyarakat';
    }

    /**
     * Admin sekolah: role = masyarakat, sekolah = 'admin'
     */
    public function isAdminSekolah(User $user): bool
    {
        return $user->isAdminSekolah();
    }

    /**
     * Siswa sekolah: role = masyarakat, sekolah = 'siswa'
     */
    public function isSiswaSekolah(User $user): bool
    {
        return $user->isSiswaSekolah();
    }

    // ─────────────────────────────────────────────
    // CEK ROLE GRANULAR (via getRoleLabel)
    // ─────────────────────────────────────────────

    /**
     * Pegawai di kantor camat (tanpa nagari).
     */
    public function isStafCamat(User $user): bool
    {
        return $user->getRoleLabel() === 'staf_camat';
    }

    /**
     * Pegawai nagari dengan jabatan kepala nagari.
     */
    public function isWaliNagari(User $user): bool
    {
        return $user->getRoleLabel() === 'wali_nagari';
    }

    /**
     * Pegawai nagari selain kepala nagari.
     */
    public function isStafNagari(User $user): bool
    {
        return $user->getRoleLabel() === 'staf_nagari';
    }

    // ─────────────────────────────────────────────
    // KEBIJAKAN SURAT KETERANGAN TIDAK MAMPU
    // ─────────────────────────────────────────────

    /**
     * Masyarakat boleh mengajukan jika nagarinya mengaktifkan fitur ini.
     */
    public function canRequestSuratKeteranganMiskin(User $user): bool
    {
        return $user->role === 'masyarakat'
            && $user->masyarakat?->nagari?->surat_keterangan_tidak_mampu === true;
    }

    /**
     * Staf nagari boleh memproses jika nagarinya mengaktifkan fitur ini.
     */
    public function canAccessSuratKeteranganMiskinPegawaiNagari(User $user): bool
    {
        return $user->role === 'pegawai'
            && $user->pegawai?->jabatan_nagari === 'pegawai_nagari'
            && $user->pegawai?->nagari?->surat_keterangan_tidak_mampu === true;
    }

    /**
     * Wali nagari boleh menyetujui jika nagarinya mengaktifkan fitur ini.
     */
    public function canAccessSuratKeteranganMiskinKepalaNagari(User $user): bool
    {
        return $user->role === 'pegawai'
            && $user->pegawai?->jabatan_nagari === 'kepala_nagari'
            && $user->pegawai?->nagari?->surat_keterangan_tidak_mampu === true;
    }
}
