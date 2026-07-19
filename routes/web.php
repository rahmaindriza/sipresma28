<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKegiatanController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\GuruGradeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\WaliPrestasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $siswaCount = \App\Models\Siswa::count();
        $guruCount = \App\Models\Guru::count();
        $mapelCount = \App\Models\Mapel::count();
        $prestasiCount = \App\Models\Prestasi::count();
        $kegiatan_terbaru = \Illuminate\Support\Facades\DB::table('kegiatans')
            ->orderBy('tanggal_kegiatan', 'desc')
            ->take(10)
            ->get();

        $gurus = \App\Models\Guru::all();
        $gurusSorted = $gurus->sortBy(function($guru) {
            $jabatan = strtolower($guru->jabatan);
            
            // 1. Kepala Sekolah / Kepsek
            if (str_contains($jabatan, 'kepala') || str_contains($jabatan, 'kepsek')) {
                return 1;
            }
            
            // 2. Wali Kelas (Pengecekan ketat dari Kelas 1 sampai 6)
            if (str_contains($jabatan, 'wali') || str_contains($jabatan, 'kelas')) {
                // Wali Kelas 1 / I
                if (str_contains($jabatan, '1') || (str_contains($jabatan, 'i') && !str_contains($jabatan, 'ii') && !str_contains($jabatan, 'iii') && !str_contains($jabatan, 'iv') && !str_contains($jabatan, 'vi'))) {
                    return 2;
                }
                // Wali Kelas 2 / II
                if (str_contains($jabatan, '2') || (str_contains($jabatan, 'ii') && !str_contains($jabatan, 'iii'))) {
                    return 3;
                }
                // Wali Kelas 3 / III
                if (str_contains($jabatan, '3') || str_contains($jabatan, 'iii')) {
                    return 4;
                }
                // Wali Kelas 4 / IV
                if (str_contains($jabatan, '4') || str_contains($jabatan, 'iv')) {
                    return 5;
                }
                // Wali Kelas 5 / V
                if (str_contains($jabatan, '5') || (str_contains($jabatan, 'v') && !str_contains($jabatan, 'i') && !str_contains($jabatan, 'g'))) {
                    return 6;
                }
                // Wali Kelas 6 / VI
                if (str_contains($jabatan, '6') || str_contains($jabatan, 'vi')) {
                    return 7;
                }
                return 8; // Wali kelas tanpa keterangan angka jelas
            }
            
            // 3. Guru Mata Pelajaran (PJOK / PAI / Agama / Penjas)
            if (str_contains($jabatan, 'pjok') || str_contains($jabatan, 'pai') || str_contains($jabatan, 'agama') || str_contains($jabatan, 'penjas') || str_contains($jabatan, 'mapel') || str_contains($jabatan, 'guru')) {
                return 9;
            }
            
            // 4. TU / Operator (OP) / Administrasi
            if (str_contains($jabatan, 'tu') || str_contains($jabatan, 'operator') || str_contains($jabatan, 'op') || str_contains($jabatan, 'tata usaha')) {
                return 10;
            }
            
            // 5. Penjaga Sekolah / Satpam / Keamanan
            if (str_contains($jabatan, 'penjaga') || str_contains($jabatan, 'satpam') || str_contains($jabatan, 'keamanan') || str_contains($jabatan, 'kebersihan')) {
                return 11;
            }
            
            return 12; // Sisanya jika ada jabatan lain
        })->values();

    } catch (\Exception $e) {
        $siswaCount = 350;
        $guruCount = 25;
        $mapelCount = 12;
        $prestasiCount = 50;
        $kegiatan_terbaru = collect();
        $gurusSorted = collect();
    }

    return view('welcome', compact('siswaCount', 'guruCount', 'mapelCount', 'prestasiCount', 'kegiatan_terbaru', 'gurusSorted'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');
    Route::patch('/users/{id}/toggle', [AdminController::class, 'userToggleStatus'])->name('users.toggle');

    // Gurus
    Route::get('/gurus', [GuruController::class, 'adminIndex'])->name('gurus');
    Route::get('/gurus/create', [GuruController::class, 'create'])->name('gurus.create');
    Route::post('/gurus', [GuruController::class, 'store'])->name('gurus.store');
    Route::get('/gurus/{id}/edit', [GuruController::class, 'edit'])->name('gurus.edit');
    Route::put('/gurus/{id}', [GuruController::class, 'update'])->name('gurus.update');
    Route::delete('/gurus/{id}', [GuruController::class, 'destroy'])->name('gurus.destroy');

    // Kelas
    Route::get('/kelas', [AdminController::class, 'kelas'])->name('kelas');
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::put('/kelas/{id}', [AdminController::class, 'kelasUpdate'])->name('kelas.update');
    Route::delete('/kelas/{id}', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');

    // Siswas
    Route::get('/siswas/cetak', [AdminController::class, 'cetakSiswa'])->name('siswas.cetak');
    Route::get('/siswas', [SiswaController::class, 'index'])->name('siswas');
    Route::get('/siswas/create', [SiswaController::class, 'create'])->name('siswas.create');
    Route::post('/siswas', [SiswaController::class, 'store'])->name('siswas.store');
    Route::get('/siswas/{id}/edit', [SiswaController::class, 'edit'])->name('siswas.edit');
    Route::put('/siswas/{id}', [SiswaController::class, 'update'])->name('siswas.update');
    Route::delete('/siswas/{id}', [SiswaController::class, 'destroy'])->name('siswas.destroy');

    // Mapels
    Route::get('/mapels', [AdminController::class, 'mapels'])->name('mapels');
    Route::post('/mapels', [AdminController::class, 'mapelStore'])->name('mapels.store');
    Route::put('/mapels/{id}', [AdminController::class, 'mapelUpdate'])->name('mapels.update');
    Route::delete('/mapels/{id}', [AdminController::class, 'mapelDestroy'])->name('mapels.destroy');

    // Tahun Ajarans
    Route::get('/tahun-ajarans', [AdminController::class, 'tahunAjarans'])->name('tahun_ajarans');
    Route::post('/tahun-ajarans', [AdminController::class, 'tahunAjaranStore'])->name('tahun_ajarans.store');
    Route::put('/tahun-ajarans/{id}', [AdminController::class, 'tahunAjaranUpdate'])->name('tahun_ajarans.update');
    Route::delete('/tahun-ajarans/{id}', [AdminController::class, 'tahunAjaranDestroy'])->name('tahun_ajarans.destroy');

    // Assignments
    Route::get('/assignments', [AdminController::class, 'assignments'])->name('assignments');
    Route::post('/assignments', [AdminController::class, 'assignmentStore'])->name('assignments.store');
    Route::delete('/assignments/{id}', [AdminController::class, 'assignmentDestroy'])->name('assignments.destroy');

    // Prestasis
    Route::get('/prestasis', [PrestasiController::class, 'index'])->name('prestasis');
    Route::post('/prestasis', [PrestasiController::class, 'store'])->name('prestasis.store');
    Route::put('/prestasis/{id}', [PrestasiController::class, 'update'])->name('prestasis.update');
    Route::delete('/prestasis/{id}', [PrestasiController::class, 'destroy'])->name('prestasis.destroy');
    Route::get('/prestasis/cetak/{siswa_id}', [PrestasiController::class, 'cetakPdf'])->name('prestasis.cetak');

    // Kegiatans
    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [AdminKegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{id}', [AdminKegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{id}', [AdminKegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    // Monitoring Akademik
    Route::get('/monitoring-nilai', [AdminController::class, 'monitoringNilai'])->name('nilai.index');
    Route::get('/monitoring-nilai/cetak-rekap', [AdminController::class, 'cetakRekapNilaiPdf'])->name('nilai.cetak_rekap');
    Route::get('/monitoring-nilai/print/{siswa_id}', [AdminController::class, 'printSiswaPdf'])->name('nilai.print_siswa');
    Route::get('/monitoring-prestasi', [AdminController::class, 'monitoringPrestasi'])->name('prestasi.index');
    Route::get('/prestasi/cetak-rekap', [AdminController::class, 'cetakRekapPdf'])->name('prestasi.cetak_rekap');
});

// ==========================================
// GURU MATA PELAJARAN ROUTES
// ==========================================
Route::middleware(['auth', 'role:guru_mapel'])->prefix('guru')->name('guru.')->group(function() {
    Route::get('/', [GuruController::class, 'index'])->name('index');
    Route::get('/grades', [GuruGradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GuruGradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{assignment_id}', [GuruController::class, 'showGradeForm'])->name('grades');
    Route::post('/grades/{assignment_id}', [GuruController::class, 'storeGrades'])->name('grades.store');
});

// ==========================================
// WALI KELAS ROUTES
// ==========================================
Route::middleware(['auth', 'role:wali_kelas'])->prefix('wali')->name('wali.')->group(function() {
    Route::get('/', function() {
        return redirect()->route('wali.dashboard');
    })->name('index');
    Route::get('/dashboard', [WaliKelasController::class, 'index'])->name('dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'siswa'])->name('siswa');
    Route::get('/siswa/cetak', [WaliKelasController::class, 'cetakSiswa'])->name('siswa.cetak');
    Route::get('/nilai', [WaliKelasController::class, 'nilai'])->name('nilai');
    Route::get('/rekap', [WaliKelasController::class, 'rekap'])->name('rekap');
    Route::get('/print/{siswa_id}', [WaliKelasController::class, 'printPdf'])->name('print');
    Route::get('/prestasi', [WaliPrestasiController::class, 'index'])->name('prestasi');
    Route::post('/prestasi', [WaliPrestasiController::class, 'store'])->name('prestasi.store');
    Route::put('/prestasi/{id}', [WaliPrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{id}', [WaliPrestasiController::class, 'destroy'])->name('prestasi.destroy');
    Route::get('/prestasi/cetak/{siswa_id}', [WaliPrestasiController::class, 'cetakPdf'])->name('prestasi.cetak');
    Route::get('/grades/{mapel_id}', [WaliKelasController::class, 'showGeneralGradeForm'])->name('grades');
    Route::post('/grades/{mapel_id}', [WaliKelasController::class, 'storeGeneralGrades'])->name('grades.store');
});

Route::middleware(['auth', 'role:wali_kelas'])->group(function() {
    Route::get('/walas/nilai', [NilaiController::class, 'index'])->name('walas.nilai.index');
    Route::post('/walas/nilai', [NilaiController::class, 'storeAtauUpdate'])->name('walas.nilai.store');
    Route::get('/walas/nilai/cetak', [NilaiController::class, 'cetakPdf'])->name('walas.nilai.cetak');
});

// ==========================================
// KEPALA SEKOLAH ROUTES
// ==========================================
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepsek')->name('kepsek.')->group(function() {
    Route::get('/', [KepalaSekolahController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';
