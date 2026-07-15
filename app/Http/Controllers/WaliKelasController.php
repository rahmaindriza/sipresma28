<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\Nilai;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class WaliKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // 1. Try to find Guru by user_id
        $guru = null;
        if ($user) {
            $guru = DB::table('gurus')->where('user_id', $user->id)->first();
            
            // 2. Fallback check: try by username as NIP
            if (!$guru) {
                $guru = DB::table('gurus')
                    ->where('nip', $user->username)
                    ->first();
            }

            // 3. Fallback check: try by NIP property if exists
            if (!$guru && isset($user->nip)) {
                $guru = DB::table('gurus')->where('nip', $user->nip)->first();
            }
            
            // 4. Fallback check: try by matching nama with user name
            if (!$guru) {
                $guru = DB::table('gurus')->where('nama', $user->name)->first();
            }
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.dashboard', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        if (!$guru) {
            return view('wali.dashboard', ['error' => 'Profil Guru Anda tidak ditemukan. Silakan hubungi Administrator untuk menghubungkan akun pengguna Anda dengan profil Guru.', 'activeTa' => $activeTa]);
        }

        // Get class managed by this Wali Kelas
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.dashboard', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();

        // Compute statistics
        $ranks = $this->calculateRankings($kelas->id, $activeTa->id);
        $avgClassScore = collect($ranks)->avg('rata_rata') ?? 0;
        $avgClassScore = round($avgClassScore, 2);

        $totalAchievements = Prestasi::whereIn('siswa_id', $students->pluck('id'))->count();

        // Fetch grades from both nilais (general subject grades) and nilai (others)
        $umumGrades = DB::table('nilais')
            ->whereIn('siswa_id', $students->pluck('id'))
            ->where('kelas_id', $kelas->id)
            ->select('id', 'siswa_id', 'mapel_id', 'nilai_tugas as tugas', 'nilai_uh as uh', 'nilai_uts as uts', 'nilai_uas as uas', 'nilai_akhir', 'status_kkm')
            ->get();

        $khususGrades = DB::table('nilai')
            ->whereIn('siswa_id', $students->pluck('id'))
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get();

        $mergedGrades = collect();
        foreach ($students as $siswa) {
            $siswaUmum = $umumGrades->where('siswa_id', $siswa->id);
            $siswaKhusus = $khususGrades->where('siswa_id', $siswa->id);

            foreach ($siswaUmum as $ug) {
                $ug->mapel = Mapel::find($ug->mapel_id);
                $mergedGrades->push($ug);
            }
            foreach ($siswaKhusus as $kg) {
                $mergedGrades->push($kg);
            }
        }

        $remedialCount = $mergedGrades->where('nilai_akhir', '<', 75)->pluck('siswa_id')->unique()->count();

        // Prepare chart data (Subject averages)
        $mapels = Mapel::orderBy('jenis_mapel')->orderBy('nama_mapel')->get();
        $chartData = [];
        foreach ($mapels as $mapel) {
            $avgUmum = DB::table('nilais')
                ->whereIn('siswa_id', $students->pluck('id'))
                ->where('mapel_id', $mapel->id)
                ->where('kelas_id', $kelas->id)
                ->avg('nilai_akhir');

            if ($avgUmum !== null) {
                $avg = $avgUmum;
            } else {
                $avg = Nilai::whereIn('siswa_id', $students->pluck('id'))
                    ->where('mapel_id', $mapel->id)
                    ->where('tahun_ajaran_id', $activeTa->id)
                    ->avg('nilai_akhir');
            }

            $chartData[] = [
                'mapel' => $mapel->nama_mapel,
                'kode' => $mapel->kode_mapel,
                'rata_rata' => $avg !== null ? round($avg, 2) : 0
            ];
        }

        return view('wali.dashboard', compact('kelas', 'activeTa', 'students', 'avgClassScore', 'totalAchievements', 'remedialCount', 'chartData'));
    }

    public function siswa(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.siswa', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.siswa', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $query = Siswa::where('kelas_id', $kelas->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $students = $query->orderBy('nama')->get();

        return view('wali.siswa', compact('kelas', 'students', 'activeTa'));
    }

    public function nilai()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.nilai', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.nilai', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $mapels = Mapel::where('jenis_mapel', 'umum')->orderBy('nama_mapel')->get();

        return view('wali.nilai', compact('kelas', 'mapels', 'activeTa'));
    }

    public function rekap()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.rekap', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.rekap', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        $mapels = Mapel::orderBy('jenis_mapel')->orderBy('nama_mapel')->get();

        // Calculate rankings
        $ranks = $this->calculateRankings($kelas->id, $activeTa->id);

        // Get all grades and achievements for this class to pass to view
        $umumGrades = DB::table('nilais')
            ->whereIn('siswa_id', $students->pluck('id'))
            ->where('kelas_id', $kelas->id)
            ->select('id', 'siswa_id', 'mapel_id', 'nilai_tugas as tugas', 'nilai_uh as uh', 'nilai_uts as uts', 'nilai_uas as uas', 'nilai_akhir', 'status_kkm')
            ->get();

        $khususGrades = DB::table('nilai')
            ->whereIn('siswa_id', $students->pluck('id'))
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get();

        $mergedGrades = collect();
        foreach ($students as $siswa) {
            $siswaUmum = $umumGrades->where('siswa_id', $siswa->id);
            $siswaKhusus = $khususGrades->where('siswa_id', $siswa->id);

            foreach ($siswaUmum as $ug) {
                $ug->mapel = Mapel::find($ug->mapel_id);
                $mergedGrades->push($ug);
            }
            foreach ($siswaKhusus as $kg) {
                $mergedGrades->push($kg);
            }
        }

        $grades = $mergedGrades->groupBy('siswa_id');

        $achievements = Prestasi::whereIn('siswa_id', $students->pluck('id'))
            ->get()
            ->groupBy('siswa_id');

        // Identify remedial students (under KKM 75 in any subject, grouped by subject)
        $remedialAlerts = [];
        foreach ($students as $siswa) {
            $siswaGrades = $grades->get($siswa->id) ?? collect();
            foreach ($siswaGrades as $grade) {
                if ($grade->nilai_akhir < 75) {
                    $remedialAlerts[$grade->mapel->nama_mapel][] = [
                        'siswa' => $siswa->nama,
                        'nilai' => $grade->nilai_akhir,
                    ];
                }
            }
        }

        return view('wali.rekap', compact('kelas', 'students', 'mapels', 'ranks', 'grades', 'achievements', 'remedialAlerts', 'activeTa'));
    }

    public function prestasi()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.prestasi', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.prestasi', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        $achievements = Prestasi::whereIn('siswa_id', $students->pluck('id'))
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('wali.prestasi', compact('kelas', 'students', 'achievements', 'activeTa'));
    }

    public function createPrestasi()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('wali.prestasi_create', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
        if (!$kelas) {
            return view('wali.prestasi_create', ['error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.', 'activeTa' => $activeTa]);
        }

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();

        return view('wali.prestasi_create', compact('kelas', 'students', 'activeTa'));
    }

    public function showGeneralGradeForm($mapel_id)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $activeTa = TahunAjaran::active();

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();
        $mapel = Mapel::where('jenis_mapel', 'umum')->findOrFail($mapel_id);

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        $grades = Nilai::where('mapel_id', $mapel->id)
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get()
            ->keyBy('siswa_id');

        return view('wali.grades', compact('kelas', 'mapel', 'students', 'grades', 'activeTa'));
    }

    public function storeGeneralGrades(Request $request, $mapel_id)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $activeTa = TahunAjaran::active();

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();
        $mapel = Mapel::where('jenis_mapel', 'umum')->findOrFail($mapel_id);

        $request->validate([
            'grades' => 'required|array',
            'grades.*.siswa_id' => 'required|exists:siswas,id',
            'grades.*.tugas' => 'required|numeric|min:0|max:100',
            'grades.*.uh' => 'required|numeric|min:0|max:100',
            'grades.*.uts' => 'required|numeric|min:0|max:100',
            'grades.*.uas' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $gradeData) {
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $gradeData['siswa_id'],
                    'mapel_id' => $mapel->id,
                    'tahun_ajaran_id' => $activeTa->id,
                ],
                [
                    'guru_id' => $guru->id,
                    'tugas' => $gradeData['tugas'],
                    'uh' => $gradeData['uh'],
                    'uts' => $gradeData['uts'],
                    'uas' => $gradeData['uas'],
                ]
            );
        }

        return redirect()->route('wali.nilai')->with('success', 'Nilai mapel ' . $mapel->nama_mapel . ' berhasil disimpan.');
    }

    public function storePrestasi(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        Prestasi::create($request->all());

        return redirect()->route('wali.prestasi')->with('success', 'Prestasi siswa berhasil ditambahkan.');
    }

    public function destroyPrestasi($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();

        return redirect()->back()->with('success', 'Prestasi siswa berhasil dihapus.');
    }

    public function printPdf($siswa_id)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $activeTa = TahunAjaran::active();

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();
        $siswa = Siswa::where('kelas_id', $kelas->id)->findOrFail($siswa_id);

        // Fetch grades for this student from both tables
        $umumGrades = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas->id)
            ->select('id', 'siswa_id', 'mapel_id', 'nilai_tugas as tugas', 'nilai_uh as uh', 'nilai_uts as uts', 'nilai_uas as uas', 'nilai_akhir', 'status_kkm')
            ->get();

        foreach ($umumGrades as $ug) {
            $ug->mapel = Mapel::find($ug->mapel_id);
        }

        $khususGrades = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get();

        $grades = $umumGrades->concat($khususGrades);

        // Calculate rank
        $ranks = $this->calculateRankings($kelas->id, $activeTa->id);
        $studentRank = $ranks[$siswa->id] ?? ['rank' => '-', 'rata_rata' => 0];

        // Fetch achievements
        $achievements = Prestasi::where('siswa_id', $siswa->id)->orderBy('tanggal', 'desc')->get();

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        $data = [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'activeTa' => $activeTa,
            'grades' => $grades,
            'rank' => $studentRank['rank'],
            'rata_rata' => $studentRank['rata_rata'],
            'achievements' => $achievements,
            'waliKelas' => $guru,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.laporan_nilai', $data);
        return $pdf->stream('Laporan_Nilai_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    /**
     * Compute rankings based on average final score of students.
     */
    private function calculateRankings($kelas_id, $ta_id)
    {
        $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        $studentAverages = [];

        foreach ($siswas as $siswa) {
            $umumGrades = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->where('kelas_id', $kelas_id)
                ->select('nilai_akhir')
                ->get();

            $khususGrades = DB::table('nilai')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $ta_id)
                ->select('nilai_akhir')
                ->get();

            $allGrades = $umumGrades->concat($khususGrades);

            if ($allGrades->count() > 0) {
                $avg = $allGrades->avg('nilai_akhir');
                $studentAverages[$siswa->id] = $avg;
            } else {
                $studentAverages[$siswa->id] = 0;
            }
        }

        // Sort descending by average score
        arsort($studentAverages);

        $rank = 1;
        $prev_avg = null;
        $ranks = [];
        $i = 0;
        foreach ($studentAverages as $siswa_id => $avg) {
            if ($prev_avg !== null && $avg < $prev_avg) {
                $rank = $i + 1;
            }
            $ranks[$siswa_id] = [
                'rank' => $rank,
                'rata_rata' => round($avg, 2)
            ];
            $prev_avg = $avg;
            $i++;
        }

        return $ranks;
    }

    /**
     * Print PDF of the classroom student list.
     */
    public function cetakSiswa(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        $kelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();

        $query = Siswa::where('kelas_id', $kelas->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $students = $query->orderBy('nama')->get();

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        $data = [
            'kelas' => $kelas,
            'students' => $students,
            'activeTa' => $activeTa,
            'waliKelas' => $guru,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.cetak_siswa', $data);
        return $pdf->stream('Data_Siswa_Kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf');
    }
}
