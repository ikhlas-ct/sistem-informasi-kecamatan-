<?php

namespace App\Notifications\Pegawai;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusSuratKeteranganNotification extends Notification
{
    use Queueable;

    protected $surat;
    protected $untuk;
    protected $count;
    protected $additional;



    /**
     * Buat notifikasi baru.
     *
     * @param mixed $surat Objek surat keterangan miskin.
     */
    public function __construct($surat, $untuk = 'masyarakat', $count = null, $additional = [])
    {
        $this->surat = $surat;
        $this->untuk = $untuk;
        $this->count = $count;
        $this->additional = $additional;



    }

    /**
     * Dapatkan kanal notifikasi.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Representasi array notifikasi untuk database.
     */
    public function toDatabase($notifiable)
    {
        // Untuk Camat: kirim notifikasi agregat berdasarkan count
        if ($this->untuk === 'camat') {
            return array_merge([
                'message' => 'Ada ' . $this->count . ' surat keterangan miskin baru yang perlu diperiksa.',
                'count'   => $this->count,
                'type'    => 'surat',
                'url'     => route('nagari.surat_keterangan_miskin'),
            ], $this->additional);
        }

        // Untuk Pegawai: notifikasi agregat count juga
        if ($this->untuk === 'pegawai') {
            return array_merge([
                'message' => 'Ada ' . $this->count . ' surat keterangan miskin baru yang menunggu pemeriksaan.',
                'count'   => $this->count,
                'type'    => 'surat',
                'url'     => route('pegawai.surat_keterangan_miskin'),
            ], $this->additional);
        }

        // Default untuk Masyarakat: kirim pesan umum (tanpa menggunakan data $surat)
        return array_merge([
            'message'   => 'Status pengajuan surat Anda telah diperbarui.',
            'type'      => 'surat',
            'url'       => route('masyarakat.surat_keterangan_miskin'),
            'timestamp' => now(),
        ], $this->additional);
    }





    /**
     * Representasi array notifikasi.
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
