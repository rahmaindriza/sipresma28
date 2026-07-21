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
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('nilais.kelas_id', $kelas->id)
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
            ->get();

        $khususGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('mapels.jenis_mapel', 'khusus')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
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
                $kg->mapel = Mapel::find($kg->mapel_id);
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
                    ->where('kelas_id', $kelas->id)
                    ->avg('nilai_akhir');
            }

            $chartData[] = [
                'mapel' => $mapel->nama_mapel,
                'kode' => $mapel->kode_mapel,
                'rata_rata' => $avg !== null ? round($avg, 2) : 0
            ];
        }

        // Kategori Prestasi count for class chart
        $prestasiAkademik = Prestasi::whereHas('siswa', function($q) use ($kelas) {
            $q->where('kelas_id', $kelas->id);
        })->where('kategori', 'Akademik')->count();

        $prestasiNonAkademik = Prestasi::whereHas('siswa', function($q) use ($kelas) {
            $q->where('kelas_id', $kelas->id);
        })->where('kategori', 'Non-Akademik')->count();

        // Top 3 Siswa Berprestasi di Kelas
        $topPrestasiKelas = DB::table('prestasis')
            ->join('siswas', 'prestasis.siswa_id', '=', 'siswas.id')
            ->where('siswas.kelas_id', $kelas->id)
            ->select('siswas.nama', 'siswas.nisn', DB::raw('SUM(prestasis.poin) as total_poin'))
            ->groupBy('siswas.id', 'siswas.nama', 'siswas.nisn')
            ->orderBy('total_poin', 'desc')
            ->take(3)
            ->get();

        return view('wali.dashboard', compact(
            'kelas', 'activeTa', 'students', 'avgClassScore', 'totalAchievements', 'remedialCount', 'chartData',
            'prestasiAkademik', 'prestasiNonAkademik', 'topPrestasiKelas'
        ));
    }

    public function siswa(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);

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
        $guru = $this->getGuruForUser($user);

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

    public function rekap(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);

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

        // For Wali Kelas, force selectedTa to be the active academic year only
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $selectedTa = $activeTa;

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        $mapels = Mapel::orderBy('jenis_mapel')->orderBy('nama_mapel')->get();

        // Calculate rankings
        $ranks = $this->calculateRankings($kelas->id, $selectedTa->id);

        $umumGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('nilais.kelas_id', $kelas->id)
            ->where('nilais.tahun_ajaran_id', $selectedTa->id)
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
            ->get();

        $khususGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.siswa_id', $students->pluck('id'))
            ->where('nilais.tahun_ajaran_id', $selectedTa->id)
            ->where('mapels.jenis_mapel', 'khusus')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
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
                $kg->mapel = Mapel::find($kg->mapel_id);
                $mergedGrades->push($kg);
            }
        }

        $grades = $mergedGrades->groupBy('siswa_id');

        $achievements = Prestasi::whereIn('siswa_id', $students->pluck('id'))
            ->where('tahun_ajaran_id', $selectedTa->id)
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

        return view('wali.rekap', compact('kelas', 'students', 'mapels', 'ranks', 'grades', 'achievements', 'remedialAlerts', 'activeTa', 'tahunAjarans', 'selectedTa'));
    }

    public function prestasi()
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);

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
        $guru = $this->getGuruForUser($user);

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
        $guru = $this->getGuruForUser($user);
        $activeTa = TahunAjaran::active();

        $kelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();
        $mapel = Mapel::where('jenis_mapel', 'umum')->findOrFail($mapel_id);

        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();
        $grades = Nilai::where('mapel_id', $mapel->id)
            ->where('kelas_id', $kelas->id)
            ->get()
            ->keyBy('siswa_id');

        return view('wali.grades', compact('kelas', 'mapel', 'students', 'grades', 'activeTa'));
    }

    public function storeGeneralGrades(Request $request, $mapel_id)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
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
                    'kelas_id' => $kelas->id,
                ],
                [
                    'nilai_tugas' => $gradeData['tugas'],
                    'nilai_uh' => $gradeData['uh'],
                    'nilai_uts' => $gradeData['uts'],
                    'nilai_uas' => $gradeData['uas'],
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

    public function printPdf(Request $request, $siswa_id)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);
        $activeTa = TahunAjaran::active();

        // 1. Get the current Wali Kelas's class (for authorization check)
        $currentWaliKelas = Kelas::where('wali_kelas_id', $guru->id)->firstOrFail();
        
        // 2. Ensure student currently belongs to this homeroom teacher's class
        $siswa = Siswa::where('kelas_id', $currentWaliKelas->id)->findOrFail($siswa_id);

        $selectedTaId = $request->input('tahun_ajaran_id', $activeTa->id);
        $selectedTa = TahunAjaran::find($selectedTaId) ?? $activeTa;

        // 3. Resolve historical class of the student for the selected Year/Semester (from grades)
        $nilaiKelas = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $selectedTa->id)
            ->first();

        // If grades exist, use the class from the grade, otherwise fallback to student's current class
        $kelas = $nilaiKelas ? Kelas::find($nilaiKelas->kelas_id) : $siswa->kelas;

        // Fetch grades for this student from both tables using resolved historical class
        $umumGrades = DB::table('nilais')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->where('nilais.siswa_id', $siswa->id)
            ->where('nilais.kelas_id', $kelas->id)
            ->where('nilais.tahun_ajaran_id', $selectedTa->id)
            ->where('mapels.jenis_mapel', 'umum')
            ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
            ->get();

        foreach ($umumGrades as $ug) {
            $ug->mapel = Mapel::find($ug->mapel_id);
        }

        $khususGrades = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $selectedTa->id)
            ->whereHas('mapel', function($q) {
                $q->where('jenis_mapel', 'khusus');
            })
            ->get();

        $grades = $umumGrades->concat($khususGrades);

        // Calculate rank using resolved historical class
        $ranks = $this->calculateRankings($kelas->id, $selectedTa->id);
        $studentRank = $ranks[$siswa->id] ?? ['rank' => '-', 'rata_rata' => 0];

        // Fetch achievements
        $achievements = Prestasi::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $selectedTa->id)
            ->orderBy('tanggal_penghargaan', 'desc')
            ->get();

        // Resolve Wali Kelas penanggung jawab based on history of the selected academic year and resolved historical class
        $namaWaliKelas = '-';
        $nipWaliKelas = '-';
        if ($kelas && $selectedTa) {
            $waliKelasHistory = DB::table('wali_kelas_history')
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->first();

            if ($waliKelasHistory) {
                $namaWaliKelas = $waliKelasHistory->guru_name ?? ($waliKelasHistory->guru_id ? (Guru::find($waliKelasHistory->guru_id)?->nama) : null) ?? '-';
                $nipWaliKelas = $waliKelasHistory->guru_nip ?? ($waliKelasHistory->guru_id ? (Guru::find($waliKelasHistory->guru_id)?->nip) : null) ?? '-';
            } else {
                $classWali = $kelas->wali_kelas_id ? Guru::find($kelas->wali_kelas_id) : null;
                $namaWaliKelas = $classWali ? $classWali->nama : '-';
                $nipWaliKelas = $classWali ? $classWali->nip : '-';
            }
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
     * Print PDF of the classroom student list.
     */
    public function cetakSiswa(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuruForUser($user);

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

    /**
     * Get the Guru profile linked to a User using relation and various fallbacks.
     */
    private function getGuruForUser($user)
    {
        if (!$user) return null;
        return $user->guru ?? Guru::where('user_id', $user->id)->first();
    }
}
