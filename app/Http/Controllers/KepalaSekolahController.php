<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\GuruMapelKelas;
use App\Models\Nilai;
use Illuminate\Http\Request;

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

        // 2. Count remedial students (at least one subject score < 75 in active semester)
        $remedialCount = Siswa::whereHas('nilai', function($q) use ($activeTa) {
            $q->where('tahun_ajaran_id', $activeTa->id)
              ->where('nilai_akhir', '<', 75);
        })->count();

        // 3. Teacher grading completion status list
        $assignments = GuruMapelKelas::with(['guru', 'kelas', 'mapel'])
            ->where('tahun_ajaran_id', $activeTa->id)
            ->get();

        $gradingStatus = [];
        foreach ($assignments as $assign) {
            $studentCount = Siswa::where('kelas_id', $assign->kelas_id)->count();
            
            $gradesCount = Nilai::where('mapel_id', $assign->mapel_id)
                ->where('tahun_ajaran_id', $activeTa->id)
                ->whereHas('siswa', function($q) use ($assign) {
                    $q->where('kelas_id', $assign->kelas_id);
                })
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
            $avgScore = Nilai::where('tahun_ajaran_id', $activeTa->id)
                ->whereHas('siswa', function($q) use ($cl) {
                    $q->where('kelas_id', $cl->id);
                })
                ->avg('nilai_akhir');
            
            $kelasAverages[] = [
                'kelas' => $cl->nama_kelas,
                'rata_rata' => $avgScore ? round($avgScore, 2) : 0,
            ];
        }

        return view('kepsek.dashboard', compact('totalSiswa', 'totalGuru', 'totalKelas', 'remedialCount', 'gradingStatus', 'kelasAverages', 'activeTa'));
    }
}
