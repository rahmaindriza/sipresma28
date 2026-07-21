<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswas = $query->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('siswas', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'nisn' => 'required|string|size:10|unique:siswas,nisn',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|size:16|unique:siswas,nik',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'alamat' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->all());

        return redirect()->route('admin.siswas')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'nisn' => 'required|string|size:10|unique:siswas,nisn,' . $id,
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|size:16|unique:siswas,nik,' . $id,
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'alamat' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($request->all());

        return redirect()->route('admin.siswas')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswas')->with('success', 'Data Siswa berhasil dihapus.');
    }

    /**
     * Get multi-semester history data for a student.
     */
    public function getHistoryData($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        
        // Fetch all grades for this student, grouped by academic year
        $grades = \App\Models\Nilai::with(['mapel', 'tahunAjaran', 'kelas'])
            ->where('siswa_id', $id)
            ->get()
            ->groupBy('tahun_ajaran_id');

        // Fetch all achievements for this student, grouped by academic year
        $prestasis = \App\Models\Prestasi::with(['tahunAjaran', 'kelas'])
            ->where('siswa_id', $id)
            ->get()
            ->groupBy('tahun_ajaran_id');

        // Fetch all academic years where the student has grades or achievements
        $taIds = $grades->keys()->merge($prestasis->keys())->unique();
        $tahunAjarans = \App\Models\TahunAjaran::whereIn('id', $taIds)
            ->orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        $history = [];
        foreach ($tahunAjarans as $ta) {
            $taGrades = $grades->get($ta->id) ?? collect();
            $taPrestasis = $prestasis->get($ta->id) ?? collect();
            
            // Resolve class name for this semester (from first grade or fallback to current class)
            $kelasObj = $taGrades->first()?->kelas ?? $taPrestasis->first()?->kelas ?? $siswa->kelas;
            
            $history[] = [
                'tahun_ajaran' => $ta->tahun . ' (' . $ta->semester . ')',
                'kelas' => $kelasObj ? $kelasObj->nama_kelas : '-',
                'grades' => $taGrades->map(function($g) {
                    return [
                        'mapel' => $g->mapel->nama_mapel,
                        'kkm' => $g->mapel->kkm,
                        'nilai_akhir' => round($g->nilai_akhir, 0),
                        'status' => $g->nilai_akhir >= $g->mapel->kkm ? 'Tuntas' : 'Remedial'
                    ];
                }),
                'prestasis' => $taPrestasis->map(function($p) {
                    return [
                        'nama_lomba' => $p->nama_lomba,
                        'kategori' => $p->kategori,
                        'tingkat' => $p->tingkat,
                        'juara' => $p->juara,
                        'sertifikat' => $p->sertifikat ? asset('uploads/sertifikat/' . $p->sertifikat) : null
                    ];
                })
            ];
        }

        return response()->json([
            'siswa' => [
                'nama' => $siswa->nama,
                'nisn' => $siswa->nisn,
                'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-'
            ],
            'history' => $history
        ]);
    }
}
