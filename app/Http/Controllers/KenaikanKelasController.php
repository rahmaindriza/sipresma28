<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    /**
     * Display page for class promotion.
     */
    public function index(Request $request)
    {
        $listKelas = Kelas::orderBy('nama_kelas')->get();
        $kelasAsalId = $request->input('kelas_asal_id');
        $kelasTujuanId = $request->input('kelas_tujuan_id');

        $students = collect();
        $kelasAsal = null;
        if ($kelasAsalId) {
            $kelasAsal = Kelas::find($kelasAsalId);
            $students = Siswa::where('kelas_id', $kelasAsalId)
                ->where('status', 'Aktif')
                ->orderBy('nama')
                ->get();
            
            // Auto-set kelas_tujuan_id to 'lulus' if kelas asal is Grade 6
            if ($kelasAsal && (strpos($kelasAsal->nama_kelas, '6') !== false || strpos($kelasAsal->nama_kelas, 'VI') !== false)) {
                $kelasTujuanId = 'lulus';
            }
        }

        $activeTa = TahunAjaran::active();

        return view('admin.kenaikan_kelas', compact('listKelas', 'kelasAsalId', 'kelasTujuanId', 'students', 'activeTa', 'kelasAsal'));
    }

    /**
     * Process class promotion/graduation for selected students.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'kelas_tujuan_id' => 'required', // can be a kelas ID or 'lulus'
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id'
        ]);

        $kelasAsal = Kelas::findOrFail($request->kelas_asal_id);
        $kelasTujuanId = $request->kelas_tujuan_id;
        $siswaIds = $request->siswa_ids;

        $activeTa = TahunAjaran::active();
        if (!$activeTa) {
            return redirect()->back()->with('error', 'Gagal memproses kenaikan kelas: Tidak ada Tahun Ajaran aktif saat ini. Silakan aktifkan Tahun Ajaran terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            if ($kelasTujuanId === 'lulus') {
                // Graduate students (Lulus / Alumni)
                Siswa::whereIn('id', $siswaIds)
                    ->where('kelas_id', $kelasAsal->id)
                    ->update([
                        'kelas_id' => null,
                        'status' => 'Lulus'
                    ]);

                DB::commit();
                return redirect()->route('admin.kenaikan-kelas.index')->with('success', 'Berhasil meluluskan ' . count($siswaIds) . ' siswa dari Kelas ' . $kelasAsal->nama_kelas . ' menjadi Alumni!');
            } else {
                // Promote to another class
                $kelasTujuan = Kelas::findOrFail($kelasTujuanId);

                Siswa::whereIn('id', $siswaIds)
                    ->where('kelas_id', $kelasAsal->id)
                    ->update([
                        'kelas_id' => $kelasTujuan->id,
                        'status' => 'Aktif'
                    ]);

                DB::commit();
                return redirect()->route('admin.kenaikan-kelas.index', [
                    'kelas_asal_id' => $request->kelas_asal_id,
                    'kelas_tujuan_id' => $request->kelas_tujuan_id
                ])->with('success', 'Berhasil memindahkan ' . count($siswaIds) . ' siswa dari Kelas ' . $kelasAsal->nama_kelas . ' ke Kelas ' . $kelasTujuan->nama_kelas . '!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses kenaikan kelas: ' . $e->getMessage());
        }
    }
}
