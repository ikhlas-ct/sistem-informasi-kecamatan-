<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user is a Camat.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isCamat(User $user)
    {
        return $user->role === 'camat';
    }

    /**
     * Determine if the user is a Masyarakat.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isMasyarakat(User $user)
    {
        return $user->role === 'masyarakat';
    }

    /**
     * Determine if the user is a Pegawai.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isPegawai(User $user)
    {
        return $user->role === 'pegawai';
    }

    /**
     * Cek apakah masyarakat boleh mengajukan surat keterangan miskin berdasarkan nagari.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function canRequestSuratKeteranganMiskin(User $user)
    {
        // Pastikan user punya relasi masyarakat dan nagari
        if (!$user->masyarakat || !$user->masyarakat->nagari) {
            return false;
        }
        return $user->masyarakat->nagari->surat_keterangan_tidak_mampu === true;
    }

    /**
     * Cek akses pegawai nagari (jabatan_nagari = 'pegawai_nagari').
     */
    public function canAccessSuratKeteranganMiskinPegawaiNagari(User $user)
    {
        if (
            !$user->pegawai ||
            !$user->pegawai->nagari ||
            $user->pegawai->jabatan_nagari !== 'pegawai_nagari'
        ) {
            return false;
        }
        return $user->pegawai->nagari->surat_keterangan_tidak_mampu === true;
    }

    /**
     * Cek akses kepala nagari (jabatan_nagari = 'kepala_nagari').
     */
    public function canAccessSuratKeteranganMiskinKepalaNagari(User $user)
    {
        if (
            !$user->pegawai ||
            !$user->pegawai->nagari ||
            $user->pegawai->jabatan_nagari !== 'kepala_nagari'
        ) {
            return false;
        }
        return $user->pegawai->nagari->surat_keterangan_tidak_mampu === true;
    }

      public function is(User $user)
    {
        if (
            !$user->pegawai ||
            !$user->pegawai->nagari ||
            $user->pegawai->jabatan_nagari !== 'kepala_nagari'
        ) {
            return false;
        }
        return $user->pegawai->nagari->surat_keterangan_tidak_mampu === true;
    }
}
