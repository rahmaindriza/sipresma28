<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\GuruMapelKelas;
use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    /**
     * Display the teacher dashboard (role: guru_mapel).
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Try by user_id
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
            return view('guru.dashboard', ['error' => 'Tidak ada tahun ajaran aktif. Silakan hubungi Admin.']);
        }

        if ($guru) {
            // Get assigned classes and subjects
            $assignments = GuruMapelKelas::with(['kelas', 'mapel'])
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', $activeTa->id)
                ->get();
        } else {
            // Fallback: Provide empty collection when teacher profile is not linked
            $assignments = collect();
        }

        return view('guru.dashboard', compact('assignments', 'activeTa'));
    }

    /**
     * Show grade input form for teachers.
     */
    public function showGradeForm($assignment_id)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $activeTa = TahunAjaran::active();
        
        $assignment = GuruMapelKelas::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $activeTa->id)
            ->findOrFail($assignment_id);

        $kelas = $assignment->kelas;
        $mapel = $assignment->mapel;

        // Get all students in the class
        $students = Siswa::where('kelas_id', $kelas->id)->orderBy('nama')->get();

        // Get existing grades for these students in this subject and academic year
        $grades = Nilai::where('mapel_id', $mapel->id)
            ->where('kelas_id', $kelas->id)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.grades', compact('assignment', 'kelas', 'mapel', 'students', 'grades', 'activeTa'));
    }

    /**
     * Store grades input by teachers.
     */
    public function storeGrades(Request $request, $assignment_id)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $activeTa = TahunAjaran::active();

        $assignment = GuruMapelKelas::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $activeTa->id)
            ->findOrFail($assignment_id);

        $mapel_id = $assignment->mapel_id;

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
                    'mapel_id' => $mapel_id,
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

        return redirect()->route('guru.index')->with('success', 'Nilai berhasil disimpan.');
    }

    // ==========================================
    // ADMIN GURU CRUD ACTIONS
    // ==========================================

    /**
     * Display a listing of all teachers (for Admin).
     */
    public function adminIndex(Request $request)
    {
        $query = Guru::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        $gurus = $query->orderBy('nama')->get();

        return view('admin.guru.index', compact('gurus'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip',
            'jabatan' => 'required|string|max:255',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/guru');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $file->move($destinationPath, $filename);
            $data['foto'] = $filename;
        }

        Guru::create($data);

        return redirect()->route('admin.gurus')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip,' . $id,
            'jabatan' => 'required|string|max:255',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/guru');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            // Remove old file if it exists
            if ($guru->foto && file_exists($destinationPath . '/' . $guru->foto)) {
                @unlink($destinationPath . '/' . $guru->foto);
            }

            $file->move($destinationPath, $filename);
            $data['foto'] = $filename;
        }

        $guru->update($data);

        return redirect()->route('admin.gurus')->with('success', 'Data Guru berhasil diperbarui.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->foto) {
            $filePath = public_path('uploads/guru/' . $guru->foto);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $guru->delete();

        return redirect()->route('admin.gurus')->with('success', 'Data Guru berhasil dihapus.');
    }
}
