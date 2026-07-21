<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\GuruMapelKelas;
use App\Models\Prestasi;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'gurus' => Guru::count(),
            'siswas' => Siswa::count(),
            'kelas' => Kelas::count(),
            'mapels' => Mapel::count(),
        ];
        
        $activeTa = TahunAjaran::active();

        // 1. Leaderboard - Top 5 Siswa Berprestasi (Berdasarkan jumlah sertifikat)
        $topPrestasi = DB::table('prestasis')
            ->join('siswas', 'prestasis.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('siswas.nama', 'kelas.nama_kelas', DB::raw('COUNT(prestasis.id) as total_sertifikat'))
            ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas')
            ->orderBy('total_sertifikat', 'desc')
            ->take(5)
            ->get();

        // 2. Kategori Prestasi count
        $akademikCount = DB::table('prestasis')->where('kategori', 'Akademik')->count();
        $nonAkademikCount = DB::table('prestasis')->where('kategori', 'Non-Akademik')->count();

        // 3. Juara Umum Sekolah (Top 3 students by average final grade)
        $juaraUmum = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('siswas.nama', 'kelas.nama_kelas', DB::raw('ROUND(AVG(nilais.nilai_akhir), 2) as rata_rata'))
            ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas')
            ->orderBy('rata_rata', 'desc')
            ->take(3)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'activeTa', 'topPrestasi', 'akademikCount', 'nonAkademikCount', 'juaraUmum'));
    }
    
    // ==========================================
    // USER MANAGEMENT
    // ==========================================
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru_mapel,wali_kelas,kepala_sekolah',
            'status_akun' => 'required|in:aktif,nonaktif',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'password_plain' => $request->password,
            'role' => $request->role,
            'status_akun' => $request->status_akun,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role' => 'required|in:admin,guru_mapel,wali_kelas,kepala_sekolah',
            'status_akun' => 'required|in:aktif,nonaktif',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->role = $request->role;
        $user->status_akun = $request->status_akun;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->password_plain = $request->password;
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function userToggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status_akun = $user->status_akun === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return redirect()->back()->with('success', 'Status akun berhasil diubah.');
    }

    // ==========================================
    // GURU MANAGEMENT
    // ==========================================
    public function gurus(Request $request)
    {
        $query = Guru::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $gurus = $query->orderBy('nama')->get();
        $availableUsers = User::whereNotIn('id', function($query) {
            $query->select('user_id')->from('gurus')->whereNotNull('user_id');
        })->whereIn('role', ['guru_mapel', 'wali_kelas'])->get();
        
        return view('admin.gurus', compact('gurus', 'availableUsers'));
    }

    public function guruStore(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|string|unique:gurus',
            'nama' => 'required|string|max:255',
            'no_telp' => 'nullable|string',
        ]);

        Guru::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function guruUpdate(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        $request->validate([
            'nip' => 'nullable|string|unique:gurus,nip,' . $id,
            'nama' => 'required|string|max:255',
            'no_telp' => 'nullable|string',
        ]);

        $guru->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function guruDestroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();
        return redirect()->back()->with('success', 'Data Guru berhasil dihapus.');
    }

    // ==========================================
    // KELAS MANAGEMENT
    // ==========================================
    public function kelas()
    {
        $kelas = Kelas::with('waliKelas')->orderBy('nama_kelas')->get();
        $gurus = Guru::orderBy('nama')->get();
        return view('admin.kelas', compact('kelas', 'gurus'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        DB::transaction(function() use ($request) {
            $kelas = Kelas::create($request->all());

            $activeTa = TahunAjaran::active();
            if ($activeTa && $request->wali_kelas_id) {
                $newWali = Guru::find($request->wali_kelas_id);
                // Record in history table
                DB::table('wali_kelas_history')->updateOrInsert(
                    [
                        'kelas_id' => $kelas->id,
                        'tahun_ajaran_id' => $activeTa->id,
                    ],
                    [
                        'guru_id' => $request->wali_kelas_id,
                        'guru_name' => $newWali ? $newWali->nama : null,
                        'guru_nip' => $newWali ? $newWali->nip : null,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                // Update User role
                if ($newWali && $newWali->user_id) {
                    $newUser = User::find($newWali->user_id);
                    if ($newUser) {
                        $newUser->update(['role' => 'wali_kelas']);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,' . $id,
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        $oldWaliKelasId = $kelas->wali_kelas_id;
        $newWaliKelasId = $request->wali_kelas_id;

        DB::transaction(function() use ($kelas, $request, $oldWaliKelasId, $newWaliKelasId) {
            $kelas->update($request->all());

            $activeTa = TahunAjaran::active();
            if ($activeTa) {
                if ($newWaliKelasId) {
                    $newWali = Guru::find($newWaliKelasId);
                    DB::table('wali_kelas_history')->updateOrInsert(
                        [
                            'kelas_id' => $kelas->id,
                            'tahun_ajaran_id' => $activeTa->id,
                        ],
                        [
                            'guru_id' => $newWaliKelasId,
                            'guru_name' => $newWali ? $newWali->nama : null,
                            'guru_nip' => $newWali ? $newWali->nip : null,
                            'updated_at' => now(),
                            'created_at' => now()
                        ]
                    );

                    // Update new Wali Kelas role to 'wali_kelas'
                    if ($newWali && $newWali->user_id) {
                        $newUser = User::find($newWali->user_id);
                        if ($newUser) {
                            $newUser->update(['role' => 'wali_kelas']);
                        }
                    }
                } else {
                    // If wali_kelas_id is unassigned, set to null in history
                    DB::table('wali_kelas_history')
                        ->where('kelas_id', $kelas->id)
                        ->where('tahun_ajaran_id', $activeTa->id)
                        ->update([
                            'guru_id' => null,
                            'guru_name' => null,
                            'guru_nip' => null,
                            'updated_at' => now()
                        ]);
                }

                if ($oldWaliKelasId != $newWaliKelasId) {
                    // Update old Wali Kelas role to guru_mapel if not assigned to other classes
                    if ($oldWaliKelasId) {
                        $otherClassCount = Kelas::where('wali_kelas_id', $oldWaliKelasId)
                            ->where('id', '!=', $kelas->id)
                            ->count();

                        if ($otherClassCount === 0) {
                            $oldWali = Guru::find($oldWaliKelasId);
                            if ($oldWali && $oldWali->user_id) {
                                $oldUser = User::find($oldWali->user_id);
                                if ($oldUser && $oldUser->role === 'wali_kelas') {
                                    $oldUser->update(['role' => 'guru_mapel']);
                                }
                            }
                        }
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Data Kelas berhasil diperbarui.');
    }

    public function kelasDestroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->back()->with('success', 'Data Kelas berhasil dihapus.');
    }

    // ==========================================
    // SISWA MANAGEMENT
    // ==========================================
    public function siswas(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswas = $query->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswas', compact('siswas', 'kelas'));
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|unique:siswas',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Siswa::create($request->all());

        return redirect()->back()->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function siswaUpdate(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn,' . $id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $siswa->update($request->all());

        return redirect()->back()->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function siswaDestroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->back()->with('success', 'Data Siswa berhasil dihapus.');
    }

    // ==========================================
    // MAPEL MANAGEMENT
    // ==========================================
    public function mapels(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mapel', 'like', '%' . $search . '%')
                  ->orWhere('kode_mapel', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('jenis_mapel')) {
            $query->where('jenis_mapel', $request->jenis_mapel);
        }

        $mapels = $query->orderBy('nama_mapel')->get();
        return view('admin.mapels', compact('mapels'));
    }

    public function mapelStore(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|unique:mapels',
            'kode_mapel' => 'required|string|unique:mapels',
            'jenis_mapel' => 'required|in:umum,khusus',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        Mapel::create($request->all());

        return redirect()->back()->with('success', 'Data Mapel berhasil ditambahkan.');
    }

    public function mapelUpdate(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|unique:mapels,nama_mapel,' . $id,
            'kode_mapel' => 'required|string|unique:mapels,kode_mapel,' . $id,
            'jenis_mapel' => 'required|in:umum,khusus',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $mapel->update($request->all());

        return redirect()->back()->with('success', 'Data Mapel berhasil diperbarui.');
    }

    public function mapelDestroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();
        return redirect()->back()->with('success', 'Data Mapel berhasil dihapus.');
    }

    // ==========================================
    // TAHUN AJARAN MANAGEMENT
    // ==========================================
    public function tahunAjarans()
    {
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        return view('admin.tahun_ajarans', compact('tahunAjarans'));
    }

    public function tahunAjaranStore(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::transaction(function() use ($request) {
            if ($request->status === 'aktif') {
                TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            }
            $ta = TahunAjaran::create($request->all());
            if ($ta->status === 'aktif') {
                $this->snapshotWaliKelas($ta->id);
            }
        });

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function tahunAjaranUpdate(Request $request, $id)
    {
        $ta = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::transaction(function() use ($request, $ta) {
            if ($request->status === 'aktif') {
                TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            }
            $ta->update($request->all());
            if ($ta->status === 'aktif') {
                $this->snapshotWaliKelas($ta->id);
            }
        });

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    /**
     * Snapshot current class homeroom teachers for the specified active academic year.
     */
    private function snapshotWaliKelas($taId)
    {
        $kelasList = Kelas::whereNotNull('wali_kelas_id')->get();
        foreach ($kelasList as $kls) {
            $guru = Guru::find($kls->wali_kelas_id);
            if ($guru) {
                DB::table('wali_kelas_history')->updateOrInsert(
                    [
                        'kelas_id' => $kls->id,
                        'tahun_ajaran_id' => $taId,
                    ],
                    [
                        'guru_id' => $kls->wali_kelas_id,
                        'guru_name' => $guru->nama,
                        'guru_nip' => $guru->nip,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            }
        }
    }

    public function tahunAjaranDestroy($id)
    {
        $ta = TahunAjaran::findOrFail($id);
        if ($ta->status === 'aktif') {
            return redirect()->back()->with('error', 'Tahun Ajaran aktif tidak boleh dihapus.');
        }
        $ta->delete();
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    // ==========================================
    // TEACHER ASSIGNMENT (GURU MAPEL KELAS)
    // ==========================================
    public function assignments(Request $request)
    {
        $activeTa = TahunAjaran::active();
        $activeTaId = $activeTa ? $activeTa->id : null;
        
        // If tahun_ajaran_id is not specified in request, default to active Year/Semester
        $selectedTaId = $request->input('tahun_ajaran_id', $activeTaId);

        $query = GuruMapelKelas::with(['guru', 'kelas', 'mapel', 'tahunAjaran']);

        // Filter by Tahun Ajaran (defaults to active TA if not filtered otherwise)
        if ($selectedTaId) {
            $query->where('tahun_ajaran_id', $selectedTaId);
        }

        // Filter by Guru Name search
        if ($request->filled('search_guru')) {
            $search = $request->search_guru;
            $query->whereHas('guru', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter by Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter by Mapel
        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        $assignments = $query->get();

        $gurus = Guru::orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        
        return view('admin.assignments', compact('assignments', 'gurus', 'kelas', 'mapels', 'tahunAjarans', 'activeTa', 'selectedTaId'));
    }

    public function assignmentStore(Request $request)
    {
        $rules = [
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ];

        if ($request->kelas_id !== 'all') {
            $rules['kelas_id'] = 'required|exists:kelas,id';
        } else {
            $rules['kelas_id'] = 'required';
        }

        $request->validate($rules);

        if ($request->kelas_id === 'all') {
            $allKelas = Kelas::all();
            foreach ($allKelas as $kls) {
                GuruMapelKelas::firstOrCreate([
                    'guru_id' => $request->guru_id,
                    'kelas_id' => $kls->id,
                    'mapel_id' => $request->mapel_id,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ]);
            }

            return redirect()->back()->with('success', 'Berhasil menambahkan penugasan guru ke seluruh kelas!');
        } else {
            $exists = GuruMapelKelas::where([
                'guru_id' => $request->guru_id,
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
            ])->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Guru sudah ditugaskan untuk mapel dan kelas tersebut di tahun ajaran ini.');
            }

            GuruMapelKelas::create($request->all());

            return redirect()->back()->with('success', 'Penugasan Guru berhasil ditambahkan.');
        }
    }

    public function assignmentDestroy($id)
    {
        $assignment = GuruMapelKelas::findOrFail($id);
        $assignment->delete();
        return redirect()->back()->with('success', 'Penugasan Guru berhasil dihapus.');
    }

    // ==========================================
    // PRESTASI SEKOLAH MANAGEMENT
    // ==========================================
    public function prestasis(Request $request)
    {
        $query = Prestasi::with('siswa.kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('jenis_prestasi')) {
            $query->where('jenis_prestasi', $request->jenis_prestasi);
        }

        $prestasis = $query->orderBy('tanggal', 'desc')->get();
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        return view('admin.prestasis', compact('prestasis', 'siswas'));
    }

    public function prestasiStore(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        Prestasi::create($request->all());

        return redirect()->back()->with('success', 'Data Prestasi berhasil ditambahkan.');
    }

    public function prestasiUpdate(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $prestasi->update($request->all());

        return redirect()->back()->with('success', 'Data Prestasi berhasil diperbarui.');
    }

    public function prestasiDestroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();
        return redirect()->back()->with('success', 'Data Prestasi berhasil dihapus.');
    }

    public function cetakSiswa(Request $request)
    {
        $activeTa = TahunAjaran::active();
        $query = Siswa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $kelas = null;
        $waliKelas = null;

        if ($request->filled('kelas_id')) {
            $kelas = Kelas::with('waliKelas')->find($request->kelas_id);
            if ($kelas) {
                $query->where('kelas_id', $kelas->id);
                $waliKelas = $kelas->waliKelas;
            }
        }

        $students = $query->orderBy('nama')->get();

        $kepsek = Guru::whereHas('user', function($q) {
            $q->where('role', 'kepala_sekolah');
        })->first();

        if (!$kelas) {
            $kelas = new \stdClass();
            $kelas->nama_kelas = 'Semua Kelas';
        }

        if (!$waliKelas) {
            $waliKelas = new \stdClass();
            $waliKelas->nama = 'Administrator';
            $waliKelas->nip = '-';
        }

        $data = [
            'kelas' => $kelas,
            'students' => $students,
            'activeTa' => $activeTa,
            'waliKelas' => $waliKelas,
            'kepsek' => $kepsek,
            'tanggal_cetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cetak_siswa', $data);
        return $pdf->stream('Data_Siswa_Kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf');
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

        return view('admin.monitoring_nilai', compact('students', 'mapels', 'ranks', 'grades', 'listKelas', 'activeTa', 'tahunAjarans', 'selectedTa'));
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

        // Get Wali Kelas profile (historical)
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
                $currentWali = $kelas->wali_kelas_id ? Guru::find($kelas->wali_kelas_id) : null;
                $namaWaliKelas = $currentWali ? $currentWali->nama : '-';
                $nipWaliKelas = $currentWali ? $currentWali->nip : '-';
            }
        }

        // Fetch grades for this student from both tables
        $umumGrades = collect();
        if ($selectedTa) {
            $umumGrades = DB::table('nilais')
                ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
                ->where('nilais.siswa_id', $siswa->id)
                ->where('nilais.kelas_id', $kelas->id)
                ->where('nilais.tahun_ajaran_id', $selectedTa->id)
                ->where('mapels.jenis_mapel', 'umum')
                ->select('nilais.id', 'nilais.siswa_id', 'nilais.mapel_id', 'nilais.nilai_tugas as tugas', 'nilais.nilai_uh as uh', 'nilais.nilai_uts as uts', 'nilais.nilai_uas as uas', 'nilais.nilai_akhir', 'nilais.status_kkm as status_kkm')
                ->get();
        }

        foreach ($umumGrades as $ug) {
            $ug->mapel = Mapel::find($ug->mapel_id);
        }

        $khususGrades = collect();
        if ($selectedTa) {
            $khususGrades = Nilai::with('mapel')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $selectedTa->id)
                ->whereHas('mapel', function($q) {
                    $q->where('jenis_mapel', 'khusus');
                })
                ->get();
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

        return view('admin.monitoring_prestasi', compact('prestasis', 'listKelas', 'activeTa', 'tahunAjarans', 'selectedTa'));
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
