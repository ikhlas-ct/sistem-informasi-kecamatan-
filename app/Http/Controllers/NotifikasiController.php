<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function baca($id)
    {
        // Ambil notifikasi berdasarkan id milik user yang sedang login
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Tandai notifikasi tersebut sebagai sudah dibaca
        $notification->markAsRead();

        // Redirect ke URL dari notifikasi jika tersedia, atau ke halaman default
        return redirect($notification->data['url'] ?? '/');
    }

    // (Opsional) Method untuk menandai semua notifikasi sebagai telah dibaca
    public function bacaSemua()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }







}
