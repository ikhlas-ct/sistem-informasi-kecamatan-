<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;



    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database']; // Bisa juga 'mail', 'broadcast', dsb.
    }

    public function toDatabase($notifiable)
    {
        return [
            'message'    => 'Komentar baru dari ' . ($this->comment->user->masyarakat->nama_masyarakat ?? $this->comment->user->pegawai->nama_pegawai ?? 'Pengguna'),
            'content_id' => $this->comment->id_konten,
            'comment'    => $this->comment->isi,
            'type'       => 'komentar',
            'url'        => route('konten.detail', ['jenis_konten' => $this->comment->konten->jenis_konten, 'slug' => $this->comment->konten->slug]),

        ];

    }

    public function toArray($notifiable)
    {
        return [
            'message'    => 'Komentar baru dari ' . ($this->comment->user->masyarakat->nama_masyarakat ?? $this->comment->user->pegawai->nama_pegawai ?? 'Pengguna'),
            'content_id' => $this->comment->id_konten,
            'comment'    => $this->comment->isi,
            'type'       => 'komentar',
            'url'        => route('konten.detail', ['jenis_konten' => $this->comment->konten->jenis_konten, 'slug' => $this->comment->konten->slug]),
        ];
    }
}
