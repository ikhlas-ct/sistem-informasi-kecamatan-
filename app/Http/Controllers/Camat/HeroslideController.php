<?php

namespace App\Http\Controllers\Camat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class HeroslideController extends Controller
{
    // Menampilkan daftar slide
    public function index()
    {
        $slides = HeroSlide::orderBy('created_at', 'asc')->get();
        return view('pages.camat.heroslide.index', compact('slides'));
    }

    // Menampilkan form untuk membuat slide baru
    public function create()
    {
        return view('pages.camat.heroslide.create');
    }

    // Menyimpan slide baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url'
        ]);

        // Upload gambar
        $imagePath = $request->file('image')->store('heroslide', 'public');

        HeroSlide::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $imagePath,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
        ]);

        return redirect()->route('camat.settings.heroslide')
                         ->with('success', 'Hero slide berhasil dibuat.');
    }

    // Menampilkan detail slide (opsional, jika diperlukan)
    public function show($id)
    {
        $slide = HeroSlide::findOrFail($id);
        return view('pages.camat.heroslide.show', compact('slide'));
    }

    // Menampilkan form edit untuk slide yang dipilih
    public function edit($id)
    {
        $slide = HeroSlide::findOrFail($id);
        return view('pages.camat.heroslide.edit', compact('slide'));
    }

    // Memperbarui data slide
    public function update(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                Storage::disk('public')->delete($slide->image);
            }
            $imagePath = $request->file('image')->store('heroslide', 'public');
            $slide->image = $imagePath;
        }
        $slide->title       = $request->title;
        $slide->description = $request->description;
        $slide->button_text = $request->button_text;
        $slide->button_link = $request->button_link;
        $slide->save();

        return redirect()->route('camat.settings.heroslide')
                         ->with('success', 'Hero slide berhasil diperbarui.');
    }

    // Menghapus slide
    public function destroy($id)
    {
        $slide = HeroSlide::findOrFail($id);

        // Hapus gambar dari storage
        if ($slide->image && Storage::disk('public')->exists($slide->image)) {
            Storage::disk('public')->delete($slide->image);
        }

        $slide->delete();

        return redirect()->route('camat.settings.heroslide')
                         ->with('success', 'Hero slide berhasil dihapus.');
    }
}
