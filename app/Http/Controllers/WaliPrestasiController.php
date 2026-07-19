<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class WaliPrestasiController extends Controller
{
    /**
     * Display a listing of classroom achievements.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);

        if (!$guru) {
            return redirect()->route('wali.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.prestasi.index', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.prestasi.index', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        
        $query = Prestasi::with('siswa.kelas')
            ->whereIn('siswa_id', $students->pluck('id'));

        // Search & filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lomba', 'like', '%' . $search . '%')
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $prestasis = $query->orderBy('tanggal_penghargaan', 'desc')->get();

        // Pass variables to view
        $siswas = $students; // for dropdown in add/edit modals

        return view('wali.prestasi.index', compact('kelas', 'students', 'siswas', 'prestasis', 'activeTa'));
    }

    /**
     * Store a newly created achievement.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
        if (!$guru) {
            return redirect()->back()->with('error', 'Profil Guru tidak ditemukan.');
        }
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'nama_lomba' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non-Akademik',
            'jenis_pelaksanaan' => 'required|in:Dalam Sekolah,Luar Sekolah',
            'tingkat' => 'required|in:Kecamatan,Kabupaten,Provinsi,Nasional',
            'juara' => 'required|in:Juara 1,Juara 2,Juara 3,Harapan',
            'sertifikat' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_penghargaan' => 'required|date',
        ]);

        // Security check: ensure student belongs to homogenious class
        $siswa = Siswa::findOrFail($request->siswa_id);
        if ($siswa->kelas_id !== $kelas->id) {
            abort(403, 'Anda hanya dapat menambah prestasi siswa di kelas Anda sendiri.');
        }

        $data = $request->all();

        // Calculate Poin
        $poin = $this->calculatePoints($request->tingkat, $request->juara);
        $data['poin'] = $poin;

        // Handle certificate upload
        if ($request->hasFile('sertifikat')) {
            $file = $request->file('sertifikat');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/sertifikat');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $file->move($destinationPath, $filename);
            $data['sertifikat'] = $filename;
        }

        Prestasi::create($data);

        return redirect()->route('wali.prestasi')->with('success', 'Data prestasi siswa berhasil ditambahkan.');
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
        if (!$guru) {
            return redirect()->back()->with('error', 'Profil Guru tidak ditemukan.');
        }
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $prestasi = Prestasi::findOrFail($id);
        
        // Security check
        if ($prestasi->siswa->kelas_id !== $kelas->id) {
            abort(403, 'Anda hanya dapat mengedit prestasi siswa di kelas Anda sendiri.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'nama_lomba' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non-Akademik',
            'jenis_pelaksanaan' => 'required|in:Dalam Sekolah,Luar Sekolah',
            'tingkat' => 'required|in:Kecamatan,Kabupaten,Provinsi,Nasional',
            'juara' => 'required|in:Juara 1,Juara 2,Juara 3,Harapan',
            'sertifikat' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_penghargaan' => 'required|date',
        ]);

        // Security check for new student selection
        $newSiswa = Siswa::findOrFail($request->siswa_id);
        if ($newSiswa->kelas_id !== $kelas->id) {
            abort(403, 'Anda hanya dapat memindahkan prestasi ke siswa di kelas Anda sendiri.');
        }

        $data = $request->all();

        // Calculate Poin
        $poin = $this->calculatePoints($request->tingkat, $request->juara);
        $data['poin'] = $poin;

        // Handle certificate update
        if ($request->hasFile('sertifikat')) {
            $file = $request->file('sertifikat');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/sertifikat');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            // Remove old certificate file if exists
            if ($prestasi->sertifikat && file_exists($destinationPath . '/' . $prestasi->sertifikat)) {
                @unlink($destinationPath . '/' . $prestasi->sertifikat);
            }

            $file->move($destinationPath, $filename);
            $data['sertifikat'] = $filename;
        }

        $prestasi->update($data);

        return redirect()->route('wali.prestasi')->with('success', 'Data prestasi siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified achievement.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
        if (!$guru) {
            return redirect()->back()->with('error', 'Profil Guru tidak ditemukan.');
        }
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $prestasi = Prestasi::findOrFail($id);

        // Security check
        if ($prestasi->siswa->kelas_id !== $kelas->id) {
            abort(403, 'Anda hanya dapat menghapus prestasi siswa di kelas Anda sendiri.');
        }

        // Remove certificate file from disk if exists
        if ($prestasi->sertifikat) {
            $filePath = public_path('uploads/sertifikat/' . $prestasi->sertifikat);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $prestasi->delete();

        return redirect()->back()->with('success', 'Data prestasi siswa berhasil dihapus.');
    }

    /**
     * Print PDF "Lembar Lampiran Prestasi Rapor" for a specific student in homeroom class.
     */
    public function cetakPdf($siswa_id)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
        if (!$guru) {
            return redirect()->back()->with('error', 'Profil Guru tidak ditemukan.');
        }
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $siswa = Siswa::with('kelas.waliKelas')->where('kelas_id', $kelas->id)->findOrFail($siswa_id);
        $achievements = Prestasi::where('siswa_id', $siswa_id)
            ->orderBy('tanggal_penghargaan', 'desc')
            ->get();

        $activeTa = TahunAjaran::active();

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        $totalPoin = $achievements->sum('poin');

        $data = [
            'siswa' => $siswa,
            'kelas' => $siswa->kelas,
            'achievements' => $achievements,
            'totalPoin' => $totalPoin,
            'activeTa' => $activeTa,
            'waliKelas' => $guru,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.cetak_prestasi', $data);
        return $pdf->stream('Lembar_Prestasi_Rapor_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    /**
     * Point calculation logic helper.
     */
    private function calculatePoints($tingkat, $juara)
    {
        if ($juara === 'Harapan') {
            return 2;
        }

        switch ($tingkat) {
            case 'Kecamatan':
                if ($juara === 'Juara 1') return 15;
                if ($juara === 'Juara 2') return 10;
                if ($juara === 'Juara 3') return 5;
                break;
            case 'Kabupaten':
                if ($juara === 'Juara 1') return 30;
                if ($juara === 'Juara 2') return 25;
                if ($juara === 'Juara 3') return 20;
                break;
            case 'Provinsi':
                if ($juara === 'Juara 1') return 60;
                if ($juara === 'Juara 2') return 50;
                if ($juara === 'Juara 3') return 40;
                break;
            case 'Nasional':
                if ($juara === 'Juara 1') return 100;
                if ($juara === 'Juara 2') return 90;
                if ($juara === 'Juara 3') return 80;
                break;
        }

        return 2; // Fallback
    }

    /**
     * Guru resolution helper.
     */
    private function getGuruForUser($user)
    {
        if (!$user) return null;
        $guru = $user->guru;
        if ($guru) return $guru;
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) return $guru;
        $guru = Guru::where('nip', $user->username)->first();
        if ($guru) return $guru;
        if (isset($user->nip)) {
            $guru = Guru::where('nip', $user->nip)->first();
            if ($guru) return $guru;
        }
        $guru = Guru::where('nama', $user->name)->first();
        if ($guru) return $guru;
        return null;
    }
}
