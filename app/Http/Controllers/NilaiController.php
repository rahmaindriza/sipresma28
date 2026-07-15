<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    /**
     * Display a listing of students in the managed/selected class and their general subject grades.
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

        // Get the class managed by this Wali Kelas
        $kelas = null;
        if ($guru) {
            $kelas = DB::table('kelas')->where('wali_kelas_id', $guru->id)->first();
        }

        if ($kelas) {
            // Limit the class list and selected class ID to the managed class only
            $kelas_list = DB::table('kelas')->where('id', $kelas->id)->get();
            $kelasId = $kelas->id;

            // List of general subjects (mapel umum)
            $mapels = DB::table('mapels')
                ->where('jenis_mapel', 'umum')
                ->orderBy('nama_mapel')
                ->get();
        } else {
            // Fallback: load all classes and mapels when the teacher profile is not linked
            $kelas_list = DB::table('kelas')->orderBy('nama_kelas')->get();
            $mapels = DB::table('mapels')
                ->where('jenis_mapel', 'umum')
                ->orderBy('nama_mapel')
                ->get();
            
            $kelasId = $request->input('kelas_id', $kelas_list->first() ? $kelas_list->first()->id : null);
        }

        // Determine current selected mapel from request filters
        $mapelId = $request->input('mapel_id', $mapels->first() ? $mapels->first()->id : null);

        $siswas = [];
        if ($kelasId && $mapelId) {
            // Using DB::table with a Left Join to pull students and their respective scores
            $siswas = DB::table('siswas')
                ->leftJoin('nilais', function($join) use ($mapelId, $kelasId) {
                    $join->on('siswas.id', '=', 'nilais.siswa_id')
                         ->where('nilais.mapel_id', '=', $mapelId)
                         ->where('nilais.kelas_id', '=', $kelasId);
                })
                ->where('siswas.kelas_id', '=', $kelasId)
                ->select(
                    'siswas.id as siswa_id',
                    'siswas.nisn',
                    'siswas.nama as nama_siswa',
                    'nilais.nilai_tugas',
                    'nilais.nilai_uh',
                    'nilais.nilai_uts',
                    'nilais.nilai_uas',
                    'nilais.nilai_akhir',
                    'nilais.status_kkm'
                )
                ->orderBy('siswas.nama')
                ->get();
        }

        return view('wali.index', compact(
            'kelas_list',
            'mapels',
            'kelasId',
            'mapelId',
            'siswas',
            'activeTa',
            'kelas'
        ));
    }

    /**
     * Bulk save or update grades for the selected class and subject.
     */
    public function storeAtauUpdate(Request $request)
    {
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

        $kelasId = null;
        if ($guru) {
            $kelas = DB::table('kelas')->where('wali_kelas_id', $guru->id)->first();
            if ($kelas) {
                $kelasId = $kelas->id;
            }
        }

        // Fallback: take class ID from request if not explicitly managing a class
        if (!$kelasId) {
            $kelasId = $request->input('kelas_id');
        }

        if (!$kelasId) {
            return redirect()->back()->with('error', 'Kelas tidak diidentifikasi.');
        }

        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'grades' => 'required|array',
            'grades.*.siswa_id' => 'required|exists:siswas,id',
            'grades.*.tugas' => 'nullable|numeric|min:0|max:100',
            'grades.*.uh' => 'nullable|numeric|min:0|max:100',
            'grades.*.uts' => 'nullable|numeric|min:0|max:100',
            'grades.*.uas' => 'nullable|numeric|min:0|max:100',
        ]);

        $mapelId = $request->input('mapel_id');
        $gradesData = $request->input('grades', []);

        // Security boundary validation: Verify that all student IDs in the request belong to the class
        $siswaIds = collect($gradesData)->pluck('siswa_id')->toArray();
        $invalidStudentsCount = DB::table('siswas')
            ->whereIn('id', $siswaIds)
            ->where('kelas_id', '!=', $kelasId)
            ->count();

        if ($invalidStudentsCount > 0) {
            return redirect()->back()->with('error', 'Akses ditolak: Terdapat siswa yang bukan merupakan anggota dari kelas yang Anda ampu.');
        }

        foreach ($gradesData as $gradeData) {
            $siswaId = $gradeData['siswa_id'];
            $tugas = isset($gradeData['tugas']) && $gradeData['tugas'] !== '' ? (float)$gradeData['tugas'] : null;
            $uh = isset($gradeData['uh']) && $gradeData['uh'] !== '' ? (float)$gradeData['uh'] : null;
            $uts = isset($gradeData['uts']) && $gradeData['uts'] !== '' ? (float)$gradeData['uts'] : null;
            $uas = isset($gradeData['uas']) && $gradeData['uas'] !== '' ? (float)$gradeData['uas'] : null;

            // Calculate Nilai Akhir: (20% x Tugas) + (20% x UH) + (30% x UTS) + (30% x UAS)
            $nilaiAkhir = (($tugas ?? 0.0) * 0.20) + (($uh ?? 0.0) * 0.20) + (($uts ?? 0.0) * 0.30) + (($uas ?? 0.0) * 0.30);
            
            // Status KKM threshold: >= 75 is 'Lulus', otherwise 'Remedial'
            $statusKkm = ($nilaiAkhir >= 75) ? 'Lulus' : 'Remedial';

            // Smart insert or update based on (siswa_id, mapel_id, kelas_id)
            DB::table('nilais')->updateOrInsert(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $mapelId,
                    'kelas_id' => $kelasId,
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

        return redirect()->back()->with('success', 'Semua nilai berhasil disimpan.');
    }

    /**
     * Print PDF rekap grades for a class and general subject.
     */
    public function cetakPdf(Request $request)
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        // Get class managed by this Wali Kelas
        $kelas = DB::table('kelas')->where('wali_kelas_id', $guru->id)->first();

        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun.');
        }

        $mapelId = $request->input('mapel_id');
        $mapel = DB::table('mapels')->where('id', $mapelId)->first();

        if (!$mapel) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $activeTa = TahunAjaran::active();
        $kelasId = $kelas->id;

        // Query students and their grades for this class and subject
        $siswas = DB::table('siswas')
            ->leftJoin('nilais', function($join) use ($mapelId, $kelasId) {
                $join->on('siswas.id', '=', 'nilais.siswa_id')
                     ->where('nilais.mapel_id', '=', $mapelId)
                     ->where('nilais.kelas_id', '=', $kelasId);
            })
            ->where('siswas.kelas_id', '=', $kelasId)
            ->select(
                'siswas.id as siswa_id',
                'siswas.nisn',
                'siswas.nama as nama_siswa',
                'nilais.nilai_tugas',
                'nilais.nilai_uh',
                'nilais.nilai_uts',
                'nilais.nilai_uas',
                'nilais.nilai_akhir',
                'nilais.status_kkm'
            )
            ->orderBy('siswas.nama')
            ->get();

        // Get Kepala Sekolah profile for signatures
        $kepsek = DB::table('gurus')
            ->join('users', 'gurus.user_id', '=', 'users.id')
            ->where('users.role', 'kepala_sekolah')
            ->select('gurus.nama', 'gurus.nip')
            ->first();

        $data = [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'activeTa' => $activeTa,
            'siswas' => $siswas,
            'waliKelas' => $guru,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.cetak_nilai_mapel', $data);
        return $pdf->stream('Rekap_Nilai_' . str_replace(' ', '_', $mapel->nama_mapel) . '_Kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf');
    }
}
