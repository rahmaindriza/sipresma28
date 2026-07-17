<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PrestasiController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Prestasi::with('siswa.kelas');

        // Filter based on role
        if ($user->isWaliKelas()) {
            $guru = DB::table('gurus')->where('user_id', $user->id)->first();
            if (!$guru) {
                $guru = DB::table('gurus')->where('nip', $user->username)->first();
            }
            if (!$guru) {
                return redirect()->route('dashboard')->with('error', 'Profil Wali Kelas Anda belum terhubung.');
            }

            $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
            if (!$kelas) {
                return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
            }

            // Wali Kelas can only see achievements of their own class
            $query->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            });

            $siswas = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        } else {
            // Admin can see everything
            $siswas = Siswa::orderBy('nama')->get();
        }

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
        $activeTa = TahunAjaran::active();

        return view('prestasi.index', compact('prestasis', 'siswas', 'activeTa'));
    }

    /**
     * Store a newly created achievement.
     */
    public function store(Request $request)
    {
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

        $data = $request->all();

        // 1. Calculate Poin automatically based on KKM/Tingkat/Juara
        $poin = $this->calculatePoints($request->tingkat, $request->juara);
        $data['poin'] = $poin;

        // 2. Handle certificate upload
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

        $redirectRoute = Auth::user()->isAdmin() ? 'admin.prestasis' : 'wali.prestasi';
        return redirect()->route($redirectRoute)->with('success', 'Data prestasi siswa berhasil ditambahkan.');
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

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

        $data = $request->all();

        // 1. Re-calculate Poin
        $poin = $this->calculatePoints($request->tingkat, $request->juara);
        $data['poin'] = $poin;

        // 2. Handle certificate update
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

        $redirectRoute = Auth::user()->isAdmin() ? 'admin.prestasis' : 'wali.prestasi';
        return redirect()->route($redirectRoute)->with('success', 'Data prestasi siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified achievement.
     */
    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        
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
     * Generate PDF "Lembar Lampiran Prestasi Rapor" for a specific student.
     */
    public function cetakPdf($siswa_id)
    {
        $siswa = Siswa::with('kelas.waliKelas')->findOrFail($siswa_id);
        $achievements = Prestasi::where('siswa_id', $siswa_id)
            ->orderBy('tanggal_penghargaan', 'desc')
            ->get();

        $activeTa = TahunAjaran::active();

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        // Wali Kelas profile
        $waliKelas = $siswa->kelas->waliKelas ?? null;

        $totalPoin = $achievements->sum('poin');

        $data = [
            'siswa' => $siswa,
            'kelas' => $siswa->kelas,
            'achievements' => $achievements,
            'totalPoin' => $totalPoin,
            'activeTa' => $activeTa,
            'waliKelas' => $waliKelas,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.cetak_prestasi', $data);
        return $pdf->stream('Lembar_Prestasi_Rapor_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    /**
     * Helper logic to calculate points automatically.
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
}
