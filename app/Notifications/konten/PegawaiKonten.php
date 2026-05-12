<?php

namespace App\Notifications\konten;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PegawaiKonten extends Notification
{
    use Queueable;

    protected $jenisKonten;   // Misalnya 'berita', 'artikel', dsb.
    protected $count;         // Jumlah konten baru yang masih pending
    protected $additional;    // Data tambahan (misal: tipe notifikasi)

    /**
     * Buat notifikasi baru.
     *
     * @param string $jenisKonten  Jenis konten (misalnya 'berita', 'seni_tari', dll.)
     * @param int $count           Jumlah konten baru (untuk notifikasi agregat)
     * @param array $additional    Data tambahan, misalnya ['type' => 'konten', 'jenis_konten' => 'berita'].
     */
    public function __construct($jenisKonten, $count, $additional = [])
    {
        $this->jenisKonten = $jenisKonten;
        $this->count = $count;
        $this->additional = $additional;
    }

    /**
     * Tentukan channel notifikasi.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Mengembalikan route URL berdasar jenis konten.
     *
     * @return string
     */
    protected function getRouteForKonten()
    {
        // Gunakan key lower-case untuk konsistensi
        $specialRoutes = [
            'berita'           => route('berita.index'),
            'artikel'          => route('artikel.index'),
            'seni_tari'        => route('potensi.index', ['jenis_konten' => 'seni_tari']),
            'seni_musik'       => route('potensi.index', ['jenis_konten' => 'seni_musik']),
            'seni_budaya'      => route('potensi.index', ['jenis_konten' => 'seni_budaya']),
            'makanan_daerah'   => route('potensi.index', ['jenis_konten' => 'makanan_daerah']),
            'kerajinan_daerah'  => route('potensi.index', ['jenis_konten' => 'kerajinan_daerah']),
            'pariwisata'       => route('potensi.index', ['jenis_konten' => 'pariwisata']),
        ];

        $key = strtolower($this->jenisKonten);
        return $specialRoutes[$key] ?? '#';
    }

    /**
     * Menghasilkan pesan notifikasi berdasarkan jenis konten dan jumlah.
     *
     * @return string
     */
    protected function getMessageForKonten()
    {
        $jenisTeks = [
            'berita'           => 'berita baru',
            'artikel'          => 'artikel baru',
            'seni_tari'        => 'seni tari baru',
            'seni_musik'       => 'seni musik baru',
            'seni_budaya'      => 'seni budaya baru',
            'makanan_daerah'   => 'makanan daerah baru',
            'kerajinan_daerah'  => 'kerajinan daerah baru',
            'pariwisata'       => 'pariwisata baru',
        ];
        $key = strtolower($this->jenisKonten);
        $teks = $jenisTeks[$key] ?? 'konten baru';
        return "Ada {$this->count} {$teks} yang perlu diperiksa.";
    }

    /**
     * Representasi array notifikasi untuk disimpan di database.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return array_merge([
            'message'   => $this->getMessageForKonten(),
            'count'     => $this->count,
            'type'      => 'konten',
            'jenis_konten' => $this->jenisKonten,
            'url'       => $this->getRouteForKonten(),
            'timestamp' => now(),
        ], $this->additional);
    }

    /**
     * Representasi array notifikasi (alias).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
