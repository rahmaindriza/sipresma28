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
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

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
        $umumGrades = collect();
        $khususGrades = collect();
        if ($selectedTa) {
            $umumGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->whereIn('nilais.siswa_id', $students->pluck('id'))
                ->where('nilais.tahun_ajaran_id', $selectedTa->id)
                ->where('mapels.jenis_mapel', 'umum')
                ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
                ->get();

            $khususGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->whereIn('nilais.siswa_id', $students->pluck('id'))
                ->where('nilais.tahun_ajaran_id', $selectedTa->id)
                ->where('mapels.jenis_mapel', 'khusus')
                ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
                ->get();
        }

        $mergedGrades = $umumGrades->concat($khususGrades);
        $grades = $mergedGrades->groupBy('siswa_id');

        // Precalculate all classes rankings
        $classes = Kelas::all();
        $ranks = [];
        if ($selectedTa) {
            foreach ($classes as $cls) {
                $classRanks = $this->calculateClassRankings($cls->id, $selectedTa->id);
                foreach ($classRanks as $sId => $rData) {
                    $ranks[$sId] = $rData;
                }
            }
        }

        $listKelas = Kelas::orderBy('nama_kelas')->get();

        return view('kepsek.monitoring_nilai', compact('students', 'mapels', 'ranks', 'grades', 'listKelas', 'activeTa', 'tahunAjarans', 'selectedTa'));
    }

    public function cetakRekapNilaiPdf(Request $request)
    {
        $activeTa = TahunAjaran::active();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

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
        $umumGrades = collect();
        $khususGrades = collect();
        if ($selectedTa) {
            $umumGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->whereIn('nilais.siswa_id', $students->pluck('id'))
                ->where('nilais.tahun_ajaran_id', $selectedTa->id)
                ->where('mapels.jenis_mapel', 'umum')
                ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
                ->get();

            $khususGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->whereIn('nilais.siswa_id', $students->pluck('id'))
                ->where('nilais.tahun_ajaran_id', $selectedTa->id)
                ->where('mapels.jenis_mapel', 'khusus')
                ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_akhir')
                ->get();
        }

        $mergedGrades = $umumGrades->concat($khususGrades);
        $grades = $mergedGrades->groupBy('siswa_id');

        // Precalculate all classes rankings
        $classes = Kelas::all();
        $ranks = [];
        if ($selectedTa) {
            foreach ($classes as $cls) {
                $classRanks = $this->calculateClassRankings($cls->id, $selectedTa->id);
                foreach ($classRanks as $sId => $rData) {
                    $ranks[$sId] = $rData;
                }
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
            'activeTa' => $selectedTa,
            'kepsek' => $kepsek,
            'kelasText' => $kelasText,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cetak_rekap_nilai', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Rekap_Nilai_Siswa_' . time() . '.pdf');
    }

    public function printSiswaPdf(Request $request, $siswa_id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($siswa_id);
        $activeTa = TahunAjaran::active();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

        // Resolve historical class of the student for the selected Year/Semester (from grades)
        $nilaiKelas = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $selectedTa->id)
            ->first();

        // If grades exist, use the class from the grade, otherwise fallback to student's current class
        $kelas = $nilaiKelas ? Kelas::find($nilaiKelas->kelas_id) : $siswa->kelas;

        // Fetch report card data (attendance, notes, extracurriculars)
        $rapor = null;
        if ($selectedTa) {
            $rapor = \App\Models\RaporSiswa::where('siswa_id', $siswa->id)
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->first();
        }

        // Get Wali Kelas profile (historical)
        $namaWaliKelas = '-';
        $nipWaliKelas = '-';
        if ($rapor && $rapor->nama_wali_kelas) {
            $namaWaliKelas = $rapor->nama_wali_kelas;
            $nipWaliKelas = $rapor->nip_wali_kelas ?? '-';
        } else {
            if ($kelas && $selectedTa) {
                $waliKelasHistory = DB::table('wali_kelas_history')
                    ->where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $selectedTa->id)
                    ->first();

                if ($waliKelasHistory) {
                    $namaWaliKelas = $waliKelasHistory->guru_name ?? ($waliKelasHistory->guru_id ? (Guru::find($waliKelasHistory->guru_id)?->nama) : null) ?? '-';
                    $nipWaliKelas = $waliKelasHistory->guru_nip ?? ($waliKelasHistory->guru_id ? (Guru::find($waliKelasHistory->guru_id)?->nip) : null) ?? '-';
                } else {
                    $currentWali = $kelas->wali_kelas_id ? Guru::find($kelas->wali_kelas_id) : null;
                    $namaWaliKelas = $currentWali ? $currentWali->nama : '-';
                    $nipWaliKelas = $currentWali ? $currentWali->nip : '-';
                }
            }
        }

        // Fetch all mapels of type 'umum' (Wajib)
        $allUmumMapels = Mapel::where('jenis_mapel', 'umum')->orderBy('nama_mapel')->get();

        // Fetch existing grades for this student from 'nilais'
        $existingUmumGrades = collect();
        if ($selectedTa) {
            $existingUmumGrades = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->get()
                ->keyBy('mapel_id');
        }

        $umumGrades = collect();
        foreach ($allUmumMapels as $mapel) {
            $g = $existingUmumGrades->get($mapel->id);
            if ($g) {
                $g->mapel = $mapel;
                $umumGrades->push($g);
            } else {
                $umumGrades->push((object)[
                    'id' => null,
                    'siswa_id' => $siswa->id,
                    'mapel_id' => $mapel->id,
                    'tugas' => 0,
                    'uh' => 0,
                    'uts' => 0,
                    'uas' => 0,
                    'nilai_akhir' => 0,
                    'status_kkm' => 'Remedial',
                    'capaian_tertinggi' => null,
                    'capaian_perlu_peningkatan' => null,
                    'mapel' => $mapel
                ]);
            }
        }

        // Fetch all mapels of type 'khusus' (Pilihan)
        $allKhususMapels = Mapel::where('jenis_mapel', 'khusus')->orderBy('nama_mapel')->get();

        $existingKhususGrades = collect();
        if ($selectedTa) {
            $existingKhususGrades = Nilai::with('mapel')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->get()
                ->keyBy('mapel_id');
        }

        $khususGrades = collect();
        foreach ($allKhususMapels as $mapel) {
            $g = $existingKhususGrades->get($mapel->id);
            if ($g) {
                $khususGrades->push($g);
            } else {
                $khususGrades->push((object)[
                    'id' => null,
                    'siswa_id' => $siswa->id,
                    'mapel_id' => $mapel->id,
                    'nilai_tugas' => 0,
                    'nilai_uh' => 0,
                    'nilai_uts' => 0,
                    'nilai_uas' => 0,
                    'nilai_akhir' => 0,
                    'status_kkm' => 'Remedial',
                    'capaian_tertinggi' => null,
                    'capaian_perlu_peningkatan' => null,
                    'mapel' => $mapel
                ]);
            }
        }

        $grades = $umumGrades->concat($khususGrades);



        // Calculate rank
        $studentRank = ['rank' => '-', 'rata_rata' => 0];
        if ($selectedTa) {
            $ranks = $this->calculateClassRankings($kelas->id, $selectedTa->id);
            $studentRank = $ranks[$siswa->id] ?? ['rank' => '-', 'rata_rata' => 0];
        }

        // Fetch achievements
        $achievements = collect();
        if ($selectedTa) {
            $achievements = Prestasi::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->orderBy('tanggal_penghargaan', 'desc')
                ->get();
        }

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        $data = [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'activeTa' => $selectedTa,
            'grades' => $grades,
            'rank' => $studentRank['rank'],
            'rata_rata' => $studentRank['rata_rata'],
            'achievements' => $achievements,
            'namaWaliKelas' => $namaWaliKelas,
            'nipWaliKelas' => $nipWaliKelas,
            'kepsek' => $kepsek,
            'rapor' => $rapor,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan_nilai', $data);
        return $pdf->stream('Laporan_Nilai_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    private function calculateClassRankings($kelas_id, $ta_id)
    {
        $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        $studentAverages = [];

        foreach ($siswas as $siswa) {
            $umumGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->where('nilais.siswa_id', $siswa->id)
                ->where('nilais.kelas_id', $kelas_id)
                ->where('nilais.tahun_ajaran_id', $ta_id)
                ->where('mapels.jenis_mapel', 'umum')
                ->select('nilais.nilai_akhir')
                ->get();

            $khususGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->where('nilais.siswa_id', $siswa->id)
                ->where('nilais.tahun_ajaran_id', $ta_id)
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
        $activeTa = TahunAjaran::active();
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

        $query = Prestasi::with('siswa.kelas')->latest();

        if ($selectedTa) {
            $query->where('tahun_ajaran_id', $selectedTa->id);
        }

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

        return view('kepsek.monitoring_prestasi', compact('prestasis', 'listKelas', 'activeTa', 'tahunAjarans', 'selectedTa'));
    }

    /**
     * Print PDF rekapitulasi of student achievements (Landscape).
     */
    public function cetakRekapPdf(Request $request)
    {
        $activeTa = TahunAjaran::active();
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id ?? null);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

        $query = Prestasi::with(['siswa.kelas'])->latest();

        if ($selectedTa) {
            $query->where('tahun_ajaran_id', $selectedTa->id);
        }

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

        // Get Kepala Sekolah profile
        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        // Filter info text
        $filterKelas = $request->filled('kelas_id') ? Kelas::find($request->kelas_id) : null;
        $kelasText = $filterKelas ? 'Kelas ' . $filterKelas->nama_kelas : 'Semua Kelas';

        $data = [
            'prestasis' => $prestasis,
            'activeTa' => $selectedTa,
            'kepsek' => $kepsek,
            'kelasText' => $kelasText,
            'kategori' => $request->kategori ?? 'Semua Kategori',
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cetak_rekap_prestasi', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Rekap_Prestasi_Siswa_' . time() . '.pdf');
    }
}
