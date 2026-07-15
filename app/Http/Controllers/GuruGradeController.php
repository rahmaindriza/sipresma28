<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\TahunAjaran;

class GuruGradeController extends Controller
{
    /**
     * Display the general filter form and student list for Subject Teachers.
     */
    public function index(Request $request)
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
        $activeTaId = $activeTa ? $activeTa->id : null;

        if ($guru) {
            // Get the distinct kelas and mapel that this guru teaches in the active TA (filtering only mapel khusus)
            $assignmentsQuery = DB::table('guru_mapel_kelas')
                ->join('mapels', 'guru_mapel_kelas.mapel_id', '=', 'mapels.id')
                ->where('mapels.jenis_mapel', 'khusus')
                ->where('guru_mapel_kelas.guru_id', $guru->id);

            if ($activeTaId) {
                $assignmentsQuery->where('guru_mapel_kelas.tahun_ajaran_id', $activeTaId);
            }

            $assignments = $assignmentsQuery->select('guru_mapel_kelas.*')->get();

            $all_kelas_ids = $assignments->pluck('kelas_id')->unique();
            $all_mapel_ids = $assignments->pluck('mapel_id')->unique();

            $all_kelas = DB::table('kelas')
                ->whereIn('id', $all_kelas_ids)
                ->orderBy('nama_kelas')
                ->get();

            $all_mapel = DB::table('mapels')
                ->whereIn('id', $all_mapel_ids)
                ->where('jenis_mapel', 'khusus')
                ->orderBy('nama_mapel')
                ->get();
        } else {
            // Fallback: Load all classes and mapels when the teacher profile is not linked
            $all_kelas = DB::table('kelas')->orderBy('nama_kelas')->get();
            $all_mapel = DB::table('mapels')->where('jenis_mapel', 'khusus')->orderBy('nama_mapel')->get();
        }

        $kelas_id = $request->input('kelas_id');
        $mapel_id = $request->input('mapel_id');
        $all_students = collect();
        $all_grades = collect();

        if ($kelas_id && $mapel_id) {
            $isAuthorized = true;
            if ($guru) {
                // Security verification: Verify that the guru actually teaches this mapel and kelas (must be mapel khusus)
                $isAuthorized = DB::table('guru_mapel_kelas')
                    ->join('mapels', 'guru_mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->where('mapels.jenis_mapel', 'khusus')
                    ->where('guru_mapel_kelas.guru_id', $guru->id)
                    ->where('guru_mapel_kelas.kelas_id', $kelas_id)
                    ->where('guru_mapel_kelas.mapel_id', $mapel_id)
                    ->when($activeTaId, function($q) use ($activeTaId) {
                        return $q->where('guru_mapel_kelas.tahun_ajaran_id', $activeTaId);
                    })
                    ->exists();
            } else {
                // Fallback: Verify that the selected mapel is indeed mapel khusus
                $isAuthorized = DB::table('mapels')
                    ->where('id', $mapel_id)
                    ->where('jenis_mapel', 'khusus')
                    ->exists();
            }

            if ($isAuthorized) {
                // Fetch students in this class
                $all_students = DB::table('siswas')
                    ->where('kelas_id', $kelas_id)
                    ->orderBy('nama')
                    ->get();

                // Fetch existing grades in 'nilais' table
                $all_grades = DB::table('nilais')
                    ->where('kelas_id', $kelas_id)
                    ->where('mapel_id', $mapel_id)
                    ->get()
                    ->keyBy('siswa_id');
            } else {
                return redirect()->route('guru.grades.index')->with('error', 'Akses ditolak: Anda tidak ditugaskan untuk mengampu kelas/mapel tersebut.');
            }
        }

        return view('guru.grades.index', compact(
            'all_kelas',
            'all_mapel',
            'kelas_id',
            'mapel_id',
            'all_students',
            'all_grades',
            'activeTa'
        ));
    }

    /**
     * Process the bulk input of grades.
     */
    public function store(Request $request)
    {
        $kelas_id = $request->input('kelas_id');
        $mapel_id = $request->input('mapel_id');
        $grades = $request->input('grades', []);

        $user = Auth::user();
        
        // Find Guru by user_id or fallbacks
        $guru = null;
        if ($user) {
            $guru = DB::table('gurus')->where('user_id', $user->id)->first();
            if (!$guru) {
                $guru = DB::table('gurus')->where('nip', $user->username)->first();
            }
            if (!$guru && isset($user->nip)) {
                $guru = DB::table('gurus')->where('nip', $user->nip)->first();
            }
            if (!$guru) {
                $guru = DB::table('gurus')->where('nama', $user->name)->first();
            }
        }

        $activeTa = TahunAjaran::active();
        $activeTaId = $activeTa ? $activeTa->id : null;

        $isAuthorized = true;
        if ($guru) {
            // Security verification: Verify that the guru actually teaches this mapel and kelas (must be mapel khusus)
            $isAuthorized = DB::table('guru_mapel_kelas')
                ->join('mapels', 'guru_mapel_kelas.mapel_id', '=', 'mapels.id')
                ->where('mapels.jenis_mapel', 'khusus')
                ->where('guru_mapel_kelas.guru_id', $guru->id)
                ->where('guru_mapel_kelas.kelas_id', $kelas_id)
                ->where('guru_mapel_kelas.mapel_id', $mapel_id)
                ->when($activeTaId, function($q) use ($activeTaId) {
                    return $q->where('guru_mapel_kelas.tahun_ajaran_id', $activeTaId);
                })
                ->exists();
        } else {
            // Fallback: Verify that the selected mapel is indeed mapel khusus
            $isAuthorized = DB::table('mapels')
                ->where('id', $mapel_id)
                ->where('jenis_mapel', 'khusus')
                ->exists();
        }

        if (!$isAuthorized) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda tidak ditugaskan untuk mengampu kombinasi kelas dan mata pelajaran ini.');
        }

        foreach ($grades as $g) {
            $siswa_id = $g['siswa_id'];
            $tugas = isset($g['nilai_tugas']) && $g['nilai_tugas'] !== '' ? (float)$g['nilai_tugas'] : null;
            $uh = isset($g['nilai_uh']) && $g['nilai_uh'] !== '' ? (float)$g['nilai_uh'] : null;
            $uts = isset($g['nilai_uts']) && $g['nilai_uts'] !== '' ? (float)$g['nilai_uts'] : null;
            $uas = isset($g['nilai_uas']) && $g['nilai_uas'] !== '' ? (float)$g['nilai_uas'] : null;

            // Calculate Nilai Akhir: (20% x Tugas) + (20% x UH) + (30% x UTS) + (30% x UAS)
            $nilaiAkhir = (($tugas ?? 0.0) * 0.20) + (($uh ?? 0.0) * 0.20) + (($uts ?? 0.0) * 0.30) + (($uas ?? 0.0) * 0.30);

            // Determine KKM status (>= 75 is 'Lulus', otherwise 'Remedial')
            $statusKkm = ($nilaiAkhir >= 75) ? 'Lulus' : 'Remedial';

            DB::table('nilais')->updateOrInsert(
                [
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $mapel_id,
                    'kelas_id' => $kelas_id,
                ],
                [
                    'nilai_tugas' => $tugas,
                    'nilai_uh' => $uh,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'status_kkm' => $statusKkm,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Semua komponen nilai siswa berhasil disimpan!');
    }
}
