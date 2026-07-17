<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the index view based on user role.
     */
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            $stats = [
                'users' => User::count(),
                'gurus' => Guru::count(),
                'siswas' => Siswa::count(),
                'kelas' => Kelas::count(),
                'mapels' => Mapel::count(),
            ];
            
            $activeTa = TahunAjaran::active();

            // 1. Leaderboard - Top 5 Siswa Berprestasi
            $topPrestasi = DB::table('prestasis')
                ->join('siswas', 'prestasis.siswa_id', '=', 'siswas.id')
                ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
                ->select('siswas.nama', 'kelas.nama_kelas', DB::raw('SUM(prestasis.poin) as total_poin'))
                ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas')
                ->orderBy('total_poin', 'desc')
                ->take(5)
                ->get();

            // 2. Kategori Prestasi count
            $akademikCount = DB::table('prestasis')->where('kategori', 'Akademik')->count();
            $nonAkademikCount = DB::table('prestasis')->where('kategori', 'Non-Akademik')->count();
            
            return view('admin.dashboard', compact('stats', 'activeTa', 'topPrestasi', 'akademikCount', 'nonAkademikCount'));
        } elseif ($role === 'guru_mapel') {
            return redirect()->route('guru.index');
        } elseif ($role === 'wali_kelas') {
            return redirect()->route('wali.index');
        } elseif ($role === 'kepala_sekolah') {
            return redirect()->route('kepsek.index');
        }

        return abort(403, 'Akses ditolak.');
    }
}
