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
            
            return view('admin.dashboard', compact('stats', 'activeTa'));
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
