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
        
        return view('admin.dashboard', compact('stats', 'activeTa'));
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

        Kelas::create($request->all());

        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,' . $id,
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        $kelas->update($request->all());

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
            TahunAjaran::create($request->all());
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
        });

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil diperbarui.');
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
    public function assignments()
    {
        $assignments = GuruMapelKelas::with(['guru', 'kelas', 'mapel', 'tahunAjaran'])->get();
        $gurus = Guru::orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        
        return view('admin.assignments', compact('assignments', 'gurus', 'kelas', 'mapels', 'tahunAjarans'));
    }

    public function assignmentStore(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

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
}
