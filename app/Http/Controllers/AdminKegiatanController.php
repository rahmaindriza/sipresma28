<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelas; // To pass classes count or others if needed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKegiatanController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index()
    {
        $kegiatans = Kegiatan::orderBy('tanggal_kegiatan', 'desc')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get(); // Just in case layout needs it
        return view('admin.kegiatan.index', compact('kegiatans', 'kelas'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:akademik,non-akademik',
            'kategori' => 'required|in:ekstrakurikuler,organisasi,perlombaan,resmi,lainnya',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_kegiatan' => 'required|date',
            'semester_aktif' => 'required|string|max:255',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('kegiatan', 'public');
        }

        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'semester_aktif' => $request->semester_aktif,
        ]);

        return redirect()->back()->with('success', 'Kegiatan sekolah berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified activity as JSON.
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return response()->json($kegiatan);
    }

    /**
     * Update the specified activity in storage.
     */
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:akademik,non-akademik',
            'kategori' => 'required|in:ekstrakurikuler,organisasi,perlombaan,resmi,lainnya',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_kegiatan' => 'required|date',
            'semester_aktif' => 'required|string|max:255',
        ]);

        $kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $kegiatan->jenis_kegiatan = $request->jenis_kegiatan;
        $kegiatan->kategori = $request->kategori;
        $kegiatan->deskripsi = $request->deskripsi;
        $kegiatan->tanggal_kegiatan = $request->tanggal_kegiatan;
        $kegiatan->semester_aktif = $request->semester_aktif;

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($kegiatan->gambar) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }
            $kegiatan->gambar = $request->file('gambar')->store('kegiatan', 'public');
        }

        $kegiatan->save();

        return redirect()->back()->with('success', 'Kegiatan sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->gambar) {
            Storage::disk('public')->delete($kegiatan->gambar);
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan sekolah berhasil dihapus.');
    }
}
