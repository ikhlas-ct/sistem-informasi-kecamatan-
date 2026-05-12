<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nagari;
use Illuminate\Http\Request;

class NagariController extends Controller
{
    public function index(Request $request)
    {
        $query = Nagari::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_nagari', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter status nagari
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter SKTM
        if ($request->filled('sktm')) {
            $query->where('surat_keterangan_tidak_mampu', $request->sktm);
        }

        $Nagari = $query->get();

        return view('pages.camat.nagari.index', compact('Nagari'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_nagari'                  => 'required|string|max:255|unique:nagari,nama_nagari',
            'singkatan'                    => 'nullable|string|max:20',
            'alamat'                       => 'nullable|string|max:255',
            'status'                       => 'required|boolean',
            'surat_keterangan_tidak_mampu' => 'required|boolean',
        ]);

        Nagari::create([
            'nama_nagari'                  => $request->nama_nagari,
            'singkatan'                    => $request->singkatan,
            'alamat'                       => $request->alamat,
            'status'                       => $request->status,
            'surat_keterangan_tidak_mampu' => $request->surat_keterangan_tidak_mampu,
        ]);

        return redirect()->route('camat.nagari.index')
            ->with('success', 'Nagari berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_nagari'                  => 'required|string|max:255|unique:nagari,nama_nagari,' . $id,
            'singkatan'                    => 'nullable|string|max:20',
            'alamat'                       => 'nullable|string|max:255',
            'status'                       => 'required|boolean',
            'surat_keterangan_tidak_mampu' => 'required|boolean',
        ]);

        $nagari = Nagari::findOrFail($id);
        $nagari->update([
            'nama_nagari'                  => $request->nama_nagari,
            'singkatan'                    => $request->singkatan,
            'alamat'                       => $request->alamat,
            'status'                       => $request->status,
            'surat_keterangan_tidak_mampu' => $request->surat_keterangan_tidak_mampu,
        ]);

        return redirect()->route('camat.nagari.index')
            ->with('success', 'Nagari berhasil diperbarui.');
    }
}
