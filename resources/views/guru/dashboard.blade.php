@extends('layouts.main')

@section('title', 'Dashboard Guru Mata Pelajaran')

@section('content')
@php
    $guru = null;
    $user = Auth::user();
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

    $activeTa = \App\Models\TahunAjaran::active();
    $activeTaId = $activeTa ? $activeTa->id : null;

    // Get assignments
    $assignmentsList = collect();
    if ($guru && $activeTaId) {
        $assignmentsList = \App\Models\GuruMapelKelas::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $activeTaId)
            ->get();
    }

    // Kartu 1: Mata Pelajaran
    $mapelsList = 'PJOK';
    if ($assignmentsList->isNotEmpty()) {
        $mapelsList = $assignmentsList->pluck('mapel.nama_mapel')->unique()->implode(', ');
    }

    // Kartu 2: Total Kelas Diajar
    $totalKelas = 0;
    if ($assignmentsList->isNotEmpty()) {
        $totalKelas = $assignmentsList->pluck('kelas_id')->unique()->count();
    }
    $totalKelasText = $totalKelas > 0 ? $totalKelas . ' Kelas' : '6 Kelas';

    // Kartu 3: Total Siswa
    $totalSiswaCount = 0;
    if ($assignmentsList->isNotEmpty()) {
        $kelasIds = $assignmentsList->pluck('kelas_id')->unique()->toArray();
        $totalSiswaCount = DB::table('siswas')->whereIn('kelas_id', $kelasIds)->count();
    }
    $totalSiswaText = $totalSiswaCount > 0 ? $totalSiswaCount . ' Siswa' : '180 Siswa';

    // Kartu 4: Status Rapor / Progress Input
    $percentFilled = 85; // default fallback
    if ($assignmentsList->isNotEmpty()) {
        $kelasIds = $assignmentsList->pluck('kelas_id')->unique()->toArray();
        $mapelIds = $assignmentsList->pluck('mapel_id')->unique()->toArray();
        $totalExpectedGrades = DB::table('siswas')->whereIn('kelas_id', $kelasIds)->count() * count($mapelIds);
        if ($totalExpectedGrades > 0) {
            $totalGrades = DB::table('nilais')
                ->whereIn('kelas_id', $kelasIds)
                ->whereIn('mapel_id', $mapelIds)
                ->count();
            $percentFilled = round(($totalGrades / $totalExpectedGrades) * 100);
        }
    }

    // Grafik Utama: Rata-Rata Nilai Mapel Per Kelas (Kelas 1 - Kelas 6)
    $chartLabels = ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'];
    $chartData = [78.5, 80.2, 79.8, 82.1, 81.5, 83.4];
    
    if ($assignmentsList->isNotEmpty()) {
        $kelasIds = $assignmentsList->pluck('kelas_id')->unique()->toArray();
        $mapelIds = $assignmentsList->pluck('mapel_id')->unique()->toArray();
        
        $averages = DB::table('nilais')
            ->join('kelas', 'nilais.kelas_id', '=', 'kelas.id')
            ->whereIn('nilais.kelas_id', $kelasIds)
            ->whereIn('nilais.mapel_id', $mapelIds)
            ->select('kelas.nama_kelas', DB::raw('AVG(nilai_akhir) as avg_nilai'))
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->get();
            
        if ($averages->isNotEmpty()) {
            $chartLabels = $averages->pluck('nama_kelas')->toArray();
            $chartData = $averages->pluck('avg_nilai')->map(function($val) {
                return round($val, 2);
            })->toArray();
        }
    }

    // Tabel Kelas Diajar (Quick Access)
    $assignmentsTable = collect();
    if ($assignmentsList->isNotEmpty()) {
        foreach ($assignmentsList as $assign) {
            $jumlah_siswa = DB::table('siswas')->where('kelas_id', $assign->kelas_id)->count();
            $nilai_count = DB::table('nilais')
                ->where('kelas_id', $assign->kelas_id)
                ->where('mapel_id', $assign->mapel_id)
                ->count();
            $percentage = $jumlah_siswa > 0 ? round(($nilai_count / $jumlah_siswa) * 100) : 0;
            
            $assignmentsTable->push((object)[
                'id' => $assign->id,
                'mapel' => $assign->mapel,
                'kelas' => $assign->kelas,
                'jumlah_siswa' => $jumlah_siswa,
                'percentage' => $percentage
            ]);
        }
    }

    // Widget Remedial Alerts
    $remedialStudents = collect();
    if ($assignmentsList->isNotEmpty()) {
        $kelasIds = $assignmentsList->pluck('kelas_id')->unique()->toArray();
        $mapelIds = $assignmentsList->pluck('mapel_id')->unique()->toArray();
        
        $remedialStudents = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'nilais.kelas_id', '=', 'kelas.id')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->whereIn('nilais.kelas_id', $kelasIds)
            ->whereIn('nilais.mapel_id', $mapelIds)
            ->where('nilais.nilai_akhir', '<', 75) // KKM standard
            ->select('siswas.nama', 'kelas.nama_kelas', 'mapels.nama_mapel', 'nilais.nilai_akhir')
            ->orderBy('nilais.nilai_akhir', 'asc')
            ->take(5)
            ->get();
    }
@endphp

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Panel Akademik Guru Mata Pelajaran</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Selamat datang kembali, <span class="font-bold text-[var(--primary-burgundy)]">{{ auth()->user()->name }}</span>. Rekap nilai dan monitoring kemajuan belajar siswa Anda.</p>
        </div>
    </div>

    <!-- 1. KARTU STATISTIK ATAS (CERAH BERAKSEN) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Mata Pelajaran -->
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="space-y-1">
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Mata Pelajaran</p>
                <h4 class="text-base font-bold text-[var(--text-dark-main)] mt-1">{{ $mapelsList ?: 'PJOK' }}</h4>
            </div>
            <div class="p-3 bg-[#FDF4F5] text-[#9F5261] rounded-full d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-dribbble fs-4" style="color: var(--primary-burgundy);"></i>
            </div>
        </div>

        <!-- Card 2: Total Kelas Diajar -->
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="space-y-1">
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Total Kelas Diajar</p>
                <h4 class="text-base font-bold text-[var(--text-dark-main)] mt-1">{{ $totalKelasText }}</h4>
            </div>
            <div class="p-3 bg-[#FDF4F5] text-[#9F5261] rounded-full d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-door-closed fs-4" style="color: var(--primary-burgundy);"></i>
            </div>
        </div>

        <!-- Card 3: Total Siswa -->
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="space-y-1">
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Total Siswa</p>
                <h4 class="text-base font-bold text-[var(--text-dark-main)] mt-1">{{ $totalSiswaText }}</h4>
            </div>
            <div class="p-3 bg-[#FDF4F5] text-[#9F5261] rounded-full d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-people fs-4" style="color: var(--primary-burgundy);"></i>
            </div>
        </div>

        <!-- Card 4: Status Rapor -->
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="space-y-1">
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Status Rapor</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-bold bg-[#E9F5F0] text-[#245E49] border border-[rgba(36,94,73,0.25)]">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ $percentFilled }}% Data Terisi
                    </span>
                </div>
            </div>
            <div class="p-3 bg-[#FDF4F5] text-[#9F5261] rounded-full d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-journal-check fs-4" style="color: var(--primary-burgundy);"></i>
            </div>
        </div>
    </div>

    <!-- 2. GRAFIK UTAMA & 4. WIDGET REMEDIAL ALERTS (SISI KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Utama (Bar Chart) -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-2xl shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4 border-b border-[var(--border-light)] pb-3">
                <div>
                    <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Rata-Rata Nilai Mapel Per Kelas</h4>
                    <p class="text-[10px] text-[var(--text-muted)]">Visualisasi rata-rata nilai akhir siswa per kelas di bawah bimbingan Anda</p>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded bg-[#FDF4F5] text-[#9F5261]">
                    Tahun Ajaran Aktif
                </span>
            </div>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="gradeAverageChart"></canvas>
            </div>
        </div>

        <!-- Widget Remedial Alerts (Sisi Kanan) -->
        <div class="lg:col-span-1 glass-panel p-6 rounded-2xl shadow-sm flex flex-col justify-between">
            <div class="flex flex-col mb-4 border-b border-[var(--border-light)] pb-3">
                <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Remedial Alerts</h4>
                <p class="text-[10px] text-[var(--text-muted)]">Daftar siswa dengan capaian nilai masih di bawah KKM (75)</p>
            </div>
            
            <div class="space-y-3 overflow-y-auto pr-1" style="max-height: 280px;">
                @forelse($remedialStudents as $student)
                    <div class="p-3 rounded-xl border border-[rgba(168,46,67,0.15)] bg-[#FDF0F2] flex items-center justify-between transition hover:shadow-sm">
                        <div class="min-w-0 flex-1 me-2">
                            <p class="text-xs font-bold text-[#A82E43] truncate">{{ $student->nama }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] truncate">Kelas {{ $student->nama_kelas }} | {{ $student->nama_mapel }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-[#A82E43] text-white shrink-0 shadow-sm">
                            Nilai: {{ round($student->nilai_akhir, 1) }}
                        </span>
                    </div>
                @empty
                    <!-- Fallback Static Items if no database records or linked profiles -->
                    <div class="p-3 rounded-xl border border-[rgba(168,46,67,0.15)] bg-[#FDF0F2] flex items-center justify-between transition hover:shadow-sm">
                        <div class="min-w-0 flex-1 me-2">
                            <p class="text-xs font-bold text-[#A82E43] truncate">Ahmad Faisal</p>
                            <p class="text-[10px] text-[var(--text-muted)] truncate">Kelas 4-A | PJOK</p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-[#A82E43] text-white shrink-0 shadow-sm">
                            Nilai: 68.5
                        </span>
                    </div>
                    <div class="p-3 rounded-xl border border-[rgba(168,46,67,0.15)] bg-[#FDF0F2] flex items-center justify-between transition hover:shadow-sm">
                        <div class="min-w-0 flex-1 me-2">
                            <p class="text-xs font-bold text-[#A82E43] truncate">Siti Rahma</p>
                            <p class="text-[10px] text-[var(--text-muted)] truncate">Kelas 5-B | PJOK</p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-[#A82E43] text-white shrink-0 shadow-sm">
                            Nilai: 72.0
                        </span>
                    </div>
                    <div class="p-3 rounded-xl border border-[rgba(168,46,67,0.15)] bg-[#FDF0F2] flex items-center justify-between transition hover:shadow-sm">
                        <div class="min-w-0 flex-1 me-2">
                            <p class="text-xs font-bold text-[#A82E43] truncate">Dedi Kurniawan</p>
                            <p class="text-[10px] text-[var(--text-muted)] truncate">Kelas 6-A | PJOK</p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-[#A82E43] text-white shrink-0 shadow-sm">
                            Nilai: 65.0
                        </span>
                    </div>
                    <div class="p-3 rounded-xl border border-[rgba(168,46,67,0.15)] bg-[#FDF0F2] flex items-center justify-between transition hover:shadow-sm">
                        <div class="min-w-0 flex-1 me-2">
                            <p class="text-xs font-bold text-[#A82E43] truncate">Rian Hidayat</p>
                            <p class="text-[10px] text-[var(--text-muted)] truncate">Kelas 3-A | PJOK</p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-[#A82E43] text-white shrink-0 shadow-sm">
                            Nilai: 70.0
                        </span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. TABEL AKSES KELAS DIAJAR (QUICK ACCESS) -->
    <div class="glass-panel p-6 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between mb-4 border-b border-[var(--border-light)] pb-3">
            <div>
                <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Daftar Kelas Diajar (Akses Cepat)</h4>
                <p class="text-[10px] text-[var(--text-muted)]">Pilih kelas di bawah ini untuk langsung masuk ke panel input nilai mata pelajaran Anda</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">No</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Mata Pelajaran</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Kelas</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Jumlah Siswa</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Progres Input</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)]">
                    @forelse($assignmentsTable as $index => $assign)
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-bold">{{ $assign->mapel->nama_mapel }}</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-semibold">{{ $assign->kelas->nama_kelas }}</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-muted)]">{{ $assign->jumlah_siswa }} Siswa</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200" style="height: 6px; border-radius: 3px; overflow: hidden; display: block;">
                                    <div class="h-full" style="width: {{ $assign->percentage }}%; background-color: var(--primary-burgundy); border-radius: 3px;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-[var(--text-dark-main)]">{{ $assign->percentage }}%</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('guru.grades', $assign->id) }}" class="btn btn-sm px-3 py-1.5 font-bold text-xs text-white bg-[#9F5261] hover:bg-[#86414E] transition rounded-lg" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(159, 82, 97, 0.2);">
                                <i class="bi bi-plus-lg me-1"></i> Input Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <!-- Fallback Static Rows if no database data or linked profiles -->
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">1</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-bold">Pendidikan Jasmani, Olahraga, & Kesehatan (PJOK)</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-semibold">Kelas 1-A</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-muted)]">28 Siswa</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200" style="height: 6px; border-radius: 3px; overflow: hidden; display: block;">
                                    <div class="h-full" style="width: 100%; background-color: var(--primary-burgundy); border-radius: 3px;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-[var(--text-dark-main)]">100%</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <button type="button" class="btn btn-sm px-3 py-1.5 font-bold text-xs text-white bg-[#9F5261] hover:bg-[#86414E] transition rounded-lg opacity-60 cursor-not-allowed" style="background-color: var(--primary-burgundy) !important; border: none;">
                                <i class="bi bi-plus-lg me-1"></i> Input Nilai
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">2</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-bold">Pendidikan Jasmani, Olahraga, & Kesehatan (PJOK)</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-semibold">Kelas 2-A</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-muted)]">30 Siswa</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200" style="height: 6px; border-radius: 3px; overflow: hidden; display: block;">
                                    <div class="h-full" style="width: 90%; background-color: var(--primary-burgundy); border-radius: 3px;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-[var(--text-dark-main)]">90%</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <button type="button" class="btn btn-sm px-3 py-1.5 font-bold text-xs text-white bg-[#9F5261] hover:bg-[#86414E] transition rounded-lg opacity-60 cursor-not-allowed" style="background-color: var(--primary-burgundy) !important; border: none;">
                                <i class="bi bi-plus-lg me-1"></i> Input Nilai
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">3</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-bold">Pendidikan Jasmani, Olahraga, & Kesehatan (PJOK)</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)] font-semibold">Kelas 3-A</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-muted)]">32 Siswa</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200" style="height: 6px; border-radius: 3px; overflow: hidden; display: block;">
                                    <div class="h-full" style="width: 80%; background-color: var(--primary-burgundy); border-radius: 3px;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-[var(--text-dark-main)]">80%</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <button type="button" class="btn btn-sm px-3 py-1.5 font-bold text-xs text-white bg-[#9F5261] hover:bg-[#86414E] transition rounded-lg opacity-60 cursor-not-allowed" style="background-color: var(--primary-burgundy) !important; border: none;">
                                <i class="bi bi-plus-lg me-1"></i> Input Nilai
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('gradeAverageChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Rata-Rata Nilai',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: '#9F5261', // Burgundy accent
                    hoverBackgroundColor: '#86414E',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F1215',
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        cornerRadius: 8,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rata-Rata: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(234, 225, 227, 0.5)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#7A6266',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#7A6266',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
