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
     * Show the form for creating a new achievement.
     */
    public function create()
    {
        $siswas = Siswa::orderBy('nama')->get();
        return view('prestasi.create', compact('siswas'));
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

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return redirect()->back()->with('error', 'Gagal menyimpan: Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        $data = $request->all();
        $siswa = Siswa::findOrFail($request->siswa_id);
        $data['tahun_ajaran_id'] = $activeTa->id;
        $data['kelas_id'] = $siswa->kelas_id;



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

        $redirectRoute = Auth::user()->isAdmin() ? 'prestasis' : 'prestasi';
        return redirect()->route($redirectRoute)->with('success', 'Data prestasi siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified achievement.
     */
    public function show($id)
    {
        $prestasi = Prestasi::with('siswa.kelas.waliKelas')->findOrFail($id);
        return view('prestasi.show', compact('prestasi'));
    }

    /**
     * Show the form for editing the specified achievement.
     */
    public function edit($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $siswas = Siswa::orderBy('nama')->get();
        return view('prestasi.edit', compact('prestasi', 'siswas'));
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return redirect()->back()->with('error', 'Gagal memperbarui: Tidak ada tahun ajaran aktif.');
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

        $data = $request->all();
        $siswa = Siswa::findOrFail($request->siswa_id);
        $data['kelas_id'] = $siswa->kelas_id;
        $data['tahun_ajaran_id'] = $activeTa->id;



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

        $redirectRoute = Auth::user()->isAdmin() ? 'prestasis' : 'prestasi';
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
        $redirectRoute = Auth::user()->isAdmin() ? 'prestasis' : 'prestasi';
        return redirect()->route($redirectRoute)->with('success', 'Data prestasi siswa berhasil dihapus.');
    }

    /**
     * Print PDF "Lembar Lampiran Prestasi Rapor" for a specific student.
     */
    public function cetak(Request $request, $siswa_id)
    {
        $activeTa = TahunAjaran::active();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

        $siswa = Siswa::with('kelas.waliKelas')->findOrFail($siswa_id);
        $achievements = Prestasi::where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $selectedTa->id)
            ->orderBy('tanggal_penghargaan', 'desc')
            ->get();

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
            'activeTa' => $selectedTa,
            'waliKelas' => $waliKelas,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.cetak_prestasi', $data);
        return $pdf->stream('Lembar_Prestasi_Rapor_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }



    /**
     * Download the certificate/proof of achievement.
     */
    public function downloadBukti($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $filePath = public_path('uploads/sertifikat/' . $prestasi->sertifikat);
        
        if ($prestasi->sertifikat && file_exists($filePath)) {
            return response()->download($filePath);
        }
        
        return back()->with('error', 'Berkas bukti sertifikat tidak ditemukan.');
    }
}
