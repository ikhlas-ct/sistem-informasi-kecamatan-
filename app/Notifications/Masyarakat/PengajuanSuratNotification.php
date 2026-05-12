<?php

namespace App\Notifications\Masyarakat;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanSuratNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $status;
    protected $pesan;
    protected $surat;

    public function __construct($status, $pesan, $surat)
    {
        $this->status = $status;
        $this->pesan  = $pesan;
        $this->surat  = $surat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->pesan,
            'status'  => $this->status,
            'url'     => route('masyarakat.surat_keterangan_miskin'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
