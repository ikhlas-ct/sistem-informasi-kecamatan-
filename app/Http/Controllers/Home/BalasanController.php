<?php

namespace App\Http\Controllers\Home;

use App\Models\Reaksi;
use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewCommentNotification;
use App\Models\Pegawai;



class BalasanController extends Controller
{


    public function storeReaksi(Request $request, $id_konten)
    {
        $request->validate([
            'jenis' => 'required|in:suka,marah,sedih,senang,terkejut,lucu'
        ]);

        $user_id = auth()->id();
        $ip_address = $request->ip();
        $currentReaksi = null;

        // Cari reaksi sebelumnya
        $existingReaksi = Reaksi::where('id_konten', $id_konten)
            ->when($user_id, fn($q) => $q->where('id_user', $user_id))
            ->when(!$user_id, fn($q) => $q->where('ip_address', $ip_address))
            ->first();

        // Jika klik reaksi yang sama dengan yang aktif
        if ($existingReaksi && $existingReaksi->jenis === $request->jenis) {
            $existingReaksi->delete(); // Hapus reaksi (toggle off)
        }
        // Jika berpindah reaksi
        else if ($existingReaksi) {
            $oldJenis = $existingReaksi->jenis; // Simpan jenis lama
            $existingReaksi->update(['jenis' => $request->jenis]);
            $currentReaksi = $request->jenis;
        }
        // Jika belum ada reaksi
        else {
            Reaksi::create([
                'id_konten' => $id_konten,
                'id_user' => $user_id,
                'ip_address' => $user_id ? null : $ip_address,
                'jenis' => $request->jenis,
            ]);
            $currentReaksi = $request->jenis;
        }

        // Hitung ulang SEMUA reaksi untuk memastikan count akurat
        $reaksiCounts = Reaksi::where('id_konten', $id_konten)
            ->select('jenis', \DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->pluck('total', 'jenis')
            ->toArray();

        return response()->json([
            'success' => true,
            'counts' => $reaksiCounts,
            'user_reaksi' => $currentReaksi
        ]);
    }

    public function storeKomentar(Request $request, $id_konten)
    {
        $request->validate([
            'isi' => 'required'
        ]);

        $komentar = Komentar::create([
            'id_konten'   => $id_konten,
            'id_user'     => auth()->id(),
            'ip_address'  => auth()->check() ? null : $request->ip(),
            'isi_komentar'=> $request->isi,
            'nama'        => $request->nama,
            'no_hp'       => $request->no_hp,
        ]);

        // Kirim notifikasi ke semua pegawai & camat
        $pegawaiUsers = Pegawai::with('user')->get();

        foreach ($pegawaiUsers as $pegawai) {
            if ($pegawai->user) {
                $pegawai->user->notify(new NewCommentNotification($komentar));
            }
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }


    public function updateKomentar(Request $request, Komentar $komentar)
    {
        // Cek apakah yang mengedit komentar adalah pemilik komentar, berdasarkan id_user atau IP.
        if ($komentar->id_user !== auth()->id() && $komentar->ip_address !== $request->ip()) {
            return back()->with('error', 'Anda tidak berhak mengedit komentar ini.');
        }

        $request->validate([
            'isi' => 'required'
        ]);

        $komentar->update(['isi_komentar' => $request->isi]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    public function deleteKomentar(Request $request, Komentar $komentar)
    {
        // Cek apakah yang menghapus komentar adalah pemilik komentar, berdasarkan id_user atau IP.
        if ($komentar->id_user !== auth()->id() && $komentar->ip_address !== $request->ip()) {
            return back()->with('error', 'Anda tidak berhak menghapus komentar ini.');
        }

        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }


    public function storebalasan(Request $request, $id_konten)
    {
        $validated = $request->validate([
            'isi' => 'required|string',
            'parent_id' => 'nullable|integer|exists:komentar,id_komentar',
        ]);

        // Cari root_id dari komentar induk
        $parentKomentar = Komentar::find($validated['parent_id']);
        $rootId = $parentKomentar ? ($parentKomentar->root_id ?? $parentKomentar->id_komentar) : null;

        // Simpan balasan
        Komentar::create([
            'id_konten' => $id_konten,
            'id_user' => auth()->id(), // Simpan hanya id_user
            'isi_komentar' => $validated['isi'],
            'parent_id' => $validated['parent_id'] ?? null,
            'root_id' => $rootId, // Simpan root_id
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil ditambahkan.');
    }
}
