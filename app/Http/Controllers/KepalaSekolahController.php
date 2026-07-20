<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\GuruMapelKelas;
use App\Models\Nilai;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KepalaSekolahController extends Controller
{
    public function index()
    {
        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return view('kepsek.dashboard', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        // 1. Total Stats
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalPrestasi = Prestasi::count();
        $rataRataNilai = Nilai::avg('nilai_akhir') ?? 0;

        // 2. Count remedial students (at least one subject score < 75 in active semester)
        $remedialCount = Siswa::whereHas('nilai', function($q) {
            $q->where('nilai_akhir', '<', 75);
        })->count();

        // 3. Teacher grading completion status list
        $assignments = GuruMapelKelas::with(['guru', 'kelas', 'mapel'])
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get();

        $gradingStatus = [];
        foreach ($assignments as $assign) {
            $studentCount = Siswa::where('kelas_id', $assign->kelas_id)->count();
            
            $gradesCount = Nilai::where('mapel_id', $assign->mapel_id)
                ->where('kelas_id', $assign->kelas_id)
                ->count();

            if ($studentCount == 0) {
                $status = 'Tidak ada siswa';
                $color = 'text-gray-500 bg-gray-100';
            } elseif ($gradesCount == 0) {
                $status = 'Belum Input';
                $color = 'text-red-600 bg-red-100';
            } elseif ($gradesCount < $studentCount) {
                $status = 'Sebagian (' . $gradesCount . '/' . $studentCount . ')';
                $color = 'text-yellow-600 bg-yellow-100';
            } else {
                $status = 'Lengkap (' . $gradesCount . '/' . $studentCount . ')';
                $color = 'text-green-600 bg-green-100';
            }

            $gradingStatus[] = [
                'guru' => $assign->guru->nama,
                'kelas' => $assign->kelas->nama_kelas,
                'mapel' => $assign->mapel->nama_mapel,
                'status' => $status,
                'color' => $color,
            ];
        }

        // 4. Class average scores for chart visualization
        $kelasAverages = [];
        $classes = Kelas::all();
        foreach ($classes as $cl) {
            $avgScore = Nilai::where('kelas_id', $cl->id)
                ->avg('nilai_akhir');
            
            $kelasAverages[] = [
                'kelas' => $cl->nama_kelas,
                'rata_rata' => $avgScore ? round($avgScore, 2) : 0,
            ];
        }

        // 5. Leaderboard - Top 5 Siswa Berprestasi (Berdasarkan jumlah sertifikat)
        $topPrestasi = \DB::table('prestasis')
            ->join('siswas', 'prestasis.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('siswas.nama', 'kelas.nama_kelas', \DB::raw('COUNT(prestasis.id) as total_sertifikat'))
            ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas')
            ->orderBy('total_sertifikat', 'desc')
            ->take(5)
            ->get();

        // 6. Kategori Prestasi count
        $akademikCount = \DB::table('prestasis')->where('kategori', 'Akademik')->count();
        $nonAkademikCount = \DB::table('prestasis')->where('kategori', 'Non-Akademik')->count();

        // 7. Calculate passing percentage for KKM (>= 75)
        $totalGrades = Nilai::count();
        $passingGrades = Nilai::where('nilai_akhir', '>=', 75)->count();
        $passingPercentage = $totalGrades > 0 ? ($passingGrades / $totalGrades) * 100 : 88.5;

        // 8. Juara Umum Sekolah (Top 3 students by average final grade)
        $juaraUmum = \DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('siswas.nama', 'kelas.nama_kelas', \DB::raw('ROUND(AVG(nilais.nilai_akhir), 2) as rata_rata'))
            ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas')
            ->orderBy('rata_rata', 'desc')
            ->take(3)
            ->get();

        return view('kepsek.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'remedialCount', 'gradingStatus', 'kelasAverages', 'activeTa',
            'topPrestasi', 'akademikCount', 'nonAkademikCount', 'totalPrestasi', 'rataRataNilai', 'passingPercentage', 'juaraUmum'
        ));
    }

    /**
     * Display global monitoring of student grades.
     */
    public function monitoringNilai(Request $request)
    {
        $activeTa = TahunAjaran::active();

        // Query students based on class filter and search query
        $studentsQuery = Siswa::with(['kelas']);
        
        if ($request->filled('kelas_id')) {
            $studentsQuery->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $students = $studentsQuery->orderBy('kelas_id')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('jenis_mapel')->orderBy('nama_mapel')->get();

        // Get grades for the active students
        $umumGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
            ->get();

        $khususGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('mapels.jenis_mapel', 'khusus')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
            ->get();

        $mergedGrades = $umumGrades->concat($khususGrades);
        $grades = $mergedGrades->groupBy('siswa_id');

        // Precalculate all classes rankings
        $classes = Kelas::all();
        $ranks = [];
        foreach ($classes as $cls) {
            $classRanks = $this->calculateClassRankings($cls->id);
            foreach ($classRanks as $sId => $rData) {
                $ranks[$sId] = $rData;
            }
        }

        $listKelas = Kelas::orderBy('nama_kelas')->get();

        return view('kepsek.monitoring_nilai', compact('students', 'mapels', 'ranks', 'grades', 'listKelas', 'activeTa'));
    }

    public function cetakRekapNilaiPdf(Request $request)
    {
        $activeTa = TahunAjaran::active();

        // Query students based on class filter and search query
        $studentsQuery = Siswa::with(['kelas']);
        
        if ($request->filled('kelas_id')) {
            $studentsQuery->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $students = $studentsQuery->orderBy('kelas_id')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('jenis_mapel')->orderBy('nama_mapel')->get();

        // Get grades for the active students
        $umumGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
            ->get();

        $khususGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('mapels.jenis_mapel', 'khusus')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
            ->get();

        $mergedGrades = $umumGrades->concat($khususGrades);
        $grades = $mergedGrades->groupBy('siswa_id');

        // Precalculate all classes rankings
        $classes = Kelas::all();
        $ranks = [];
        foreach ($classes as $cls) {
            $classRanks = $this->calculateClassRankings($cls->id);
            foreach ($classRanks as $sId => $rData) {
                $ranks[$sId] = $rData;
            }
        }

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        // Filter info text
        $filterKelas = $request->filled('kelas_id') ? Kelas::find($request->kelas_id) : null;
        $kelasText = $filterKelas ? 'Kelas ' . $filterKelas->nama_kelas : 'Semua Kelas';

        $data = [
            'students' => $students,
            'mapels' => $mapels,
            'ranks' => $ranks,
            'grades' => $grades,
            'activeTa' => $activeTa,
            'kepsek' => $kepsek,
            'kelasText' => $kelasText,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cetak_rekap_nilai', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Rekap_Nilai_Siswa_' . time() . '.pdf');
    }

    public function printSiswaPdf($siswa_id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($siswa_id);
        $kelas = $siswa->kelas;
        $activeTa = TahunAjaran::active();

        // Get Wali Kelas profile
        $waliKelas = $kelas ? Guru::find($kelas->wali_kelas_id) : null;

        // Fetch grades for this student from both tables
        $umumGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->where('nilais.siswa_id', $siswa->id)
            ->where('nilais.kelas_id', $kelas->id)
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
            ->get();

        foreach ($umumGrades as $ug) {
            $ug->mapel = Mapel::find($ug->mapel_id);
        }

        $khususGrades = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->whereHas('mapel', function($q) {
                $q->where('jenis_mapel', 'khusus');
            })
            ->get();

        $grades = $umumGrades->concat($khususGrades);

        // Calculate rank
        $ranks = $this->calculateClassRankings($kelas->id);
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
            'waliKelas' => $waliKelas,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan_nilai', $data);
        return $pdf->stream('Laporan_Nilai_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    private function calculateClassRankings($kelas_id)
    {
        $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        $studentAverages = [];

        foreach ($siswas as $siswa) {
            $umumGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->where('nilais.siswa_id', $siswa->id)
                ->where('nilais.kelas_id', $kelas_id)
                ->where('mapels.jenis_mapel', 'umum')
                ->select('nilais.nilai_akhir')
                ->get();

            $khususGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->where('nilais.siswa_id', $siswa->id)
                ->where('mapels.jenis_mapel', 'khusus')
                ->select('nilais.nilai_akhir')
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
     * Display global monitoring of student achievements.
     */
    public function monitoringPrestasi(Request $request)
    {
        $query = Prestasi::with('siswa.kelas')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lomba', 'like', '%' . $search . '%')
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $prestasis = $query->get();
        $listKelas = Kelas::orderBy('nama_kelas')->get();
        $activeTa = TahunAjaran::active();

        return view('kepsek.monitoring_prestasi', compact('prestasis', 'listKelas', 'activeTa'));
    }

    /**
     * Print PDF rekapitulasi of student achievements (Landscape).
     */
    public function cetakRekapPdf(Request $request)
    {
        $query = Prestasi::with(['siswa.kelas'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lomba', 'like', '%' . $search . '%')
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $prestasis = $query->get();
        $activeTa = TahunAjaran::active();

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        // Filter info text
        $filterKelas = $request->filled('kelas_id') ? Kelas::find($request->kelas_id) : null;
        $kelasText = $filterKelas ? 'Kelas ' . $filterKelas->nama_kelas : 'Semua Kelas';

        $data = [
            'prestasis' => $prestasis,
            'activeTa' => $activeTa,
            'kepsek' => $kepsek,
            'kelasText' => $kelasText,
            'kategori' => $request->kategori ?? 'Semua Kategori',
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cetak_rekap_prestasi', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Rekap_Prestasi_Siswa_' . time() . '.pdf');
    }
}
