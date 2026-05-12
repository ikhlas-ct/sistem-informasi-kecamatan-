<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengaduanNotification extends Notification
{
    use Queueable;

    protected $pengaduan;
    protected $untuk;
    protected $count;
    protected $additional;

    public function __construct($pengaduan, $untuk = 'masyarakat', $count = null, $additional = [])
    {
        $this->pengaduan = $pengaduan;
        $this->untuk = $untuk;
        $this->count = $count;
        $this->additional = $additional;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        if ($this->untuk === 'pegawai' || $this->untuk === 'camat') {
            return array_merge([
                'message' => "Ada " . $this->count . " pengaduan baru yang perlu ditindaklanjuti.",
                'count'   => $this->count,
                'type'    => 'pengaduan',
                'url'     => route('balasan_pengaduan.index'),
            ], $this->additional);
        }
        return array_merge([
            'message' => "Pengaduan Anda telah mendapatkan balasan dari petugas.",
            'type'    => 'pengaduan',
            'url'     => route('masyarakat.pengaduan.index'),
            'timestamp' => now(),
        ], $this->additional);
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
