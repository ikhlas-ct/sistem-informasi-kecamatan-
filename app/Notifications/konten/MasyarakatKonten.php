<?php

namespace App\Notifications\konten;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MasyarakatKonten extends Notification
{
    use Queueable;

    protected $konten;
    protected $statusText;
    protected $additional;

    /**
     * Buat notifikasi baru.
     *
     * @param mixed  $konten     Objek konten.
     * @param string $statusText Status konten (misalnya "disetujui" atau "ditolak").
     * @param array  $additional Data tambahan jika diperlukan.
     */
    public function __construct($konten, $statusText = null, $additional = [])
    {
        $this->konten = $konten;
        $this->statusText = $statusText;
        $this->additional = $additional;
    }

    /**
     * Tentukan channel notifikasi.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Representasi array notifikasi untuk disimpan ke database.
     */
    public function toDatabase($notifiable)
    {
        return array_merge([
            'message'   => "Konten  {$this->konten->jenis_konten} Anda telah {$this->statusText}: " . "yang berjudul " . $this->konten->judul,
            'type'      => 'konten',
            'url'       => route('potensi.index', ['jenis_konten' => $this->konten->jenis_konten]),
            'timestamp' => now(),
        ], $this->additional);
    }


    /**
     * Representasi array notifikasi (alias toDatabase).
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
