@extends('layouts.main')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<!-- Custom Styles to enforce KEPSEK dark theme & color palette overrides -->
<style>
    main {
        background-color: var(--bg-dark-panel) !important;
    }
    .text-emerald-400 {
        color: var(--lulus-sekolah) !important;
    }
    .bg-emerald-500\/10 {
        background-color: rgba(5, 150, 105, 0.15) !important;
    }
    .text-red-400 {
        color: var(--remedial-sekolah) !important;
    }
</style>

<div class="space-y-8 text-white">
    @php
        // Fetch all required data dynamically to avoid nulls or errors
        $totalPrestasi = \App\Models\Prestasi::count();
        $totalGrades = \App\Models\Nilai::count();
        $passingGrades = \App\Models\Nilai::where('nilai_akhir', '>=', 75)->count();
        $passingPercentage = $totalGrades > 0 ? ($passingGrades / $totalGrades) * 100 : 88.5; // default fallback if empty

        // Get Top 5 student rankings safely
        $topStudents = \App\Models\Siswa::with(['kelas'])
            ->get()
            ->map(function($siswa) {
                $siswa->rata_rata = \App\Models\Nilai::where('siswa_id', $siswa->id)
                    ->avg('nilai_akhir') ?? 0;
                return $siswa;
            })
            ->sortByDesc('rata_rata')
            ->take(5);

        // Get classes summary for KKM completion report
        $classesSummary = \App\Models\Kelas::with(['waliKelas'])->get()->map(function($cl) {
            $studentsInClass = \App\Models\Siswa::where('kelas_id', $cl->id)->get();
            $totalSiswaInClass = $studentsInClass->count();
            
            $remedialCountInClass = \App\Models\Siswa::where('kelas_id', $cl->id)
                ->whereHas('nilai', function($q) {
                    $q->where('nilai_akhir', '<', 75);
                })->count();
                
            $tuntasCountInClass = $totalSiswaInClass - $remedialCountInClass;
            $percentage = $totalSiswaInClass > 0 ? ($tuntasCountInClass / $totalSiswaInClass) * 100 : 100;
            
            return [
                'nama_kelas' => $cl->nama_kelas,
                'wali_kelas' => $cl->waliKelas ? $cl->waliKelas->nama : '-',
                'jumlah_siswa' => $totalSiswaInClass,
                'siswa_tuntas' => $tuntasCountInClass,
                'siswa_remedial' => $remedialCountInClass,
                'persentase' => round($percentage, 1)
            ];
        });
    @endphp

    <!-- 1. HEADER DASHBOARD -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-2xl font-bold text-white">Dashboard Pemantauan Kepala Sekolah</h3>
            <p class="text-xs text-slate-400 mt-1">Ringkasan grafik capaian nilai akademik dan perkembangan prestasi siswa SDN 28 Kinali</p>
        </div>
        <div>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-[#9F5261]/10 text-[#9F5261] border border-[#9F5261]/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                TA Aktif: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
            </span>
        </div>
    </div>

    <!-- 2. KARTU STATISTIK UTAMA (HIGHLIGHTS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Siswa Aktif -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-2xl flex items-center justify-between shadow-lg">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Siswa Aktif</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalSiswa }}</p>
            </div>
            <div class="p-3 bg-[#9F5261]/15 text-[#9F5261] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Rata-Rata Nilai Akademik Sekolah -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-2xl flex items-center justify-between shadow-lg">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Rata-Rata Nilai</p>
                <p class="text-3xl font-bold text-[#9F5261] mt-1">{{ number_format(collect($kelasAverages)->avg('rata_rata') ?? 0, 1) }}</p>
            </div>
            <div class="p-3 bg-[#9F5261]/15 text-[#9F5261] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Persentase Kelulusan KKM -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-2xl flex items-center justify-between shadow-lg">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Lulus KKM (&ge;75)</p>
                <p class="text-3xl font-bold text-emerald-400 mt-1">{{ number_format($passingPercentage, 1) }}%</p>
            </div>
            <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Total Prestasi Tercatat -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-2xl flex items-center justify-between shadow-lg">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Prestasi</p>
                <p class="text-3xl font-bold text-[#F59E0B] mt-1">{{ $totalPrestasi }} <span class="text-xs text-slate-400 font-normal">Piagam</span></p>
            </div>
            <div class="p-3 bg-[#F59E0B]/10 text-[#F59E0B] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- 3 & 4. SEKSI GRAFIK MONITORING (KIRI) & PERINGKAT PRESTASI (KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Grafik Rata-Rata Nilai Akhir Per Kelas -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-3xl lg:col-span-2 space-y-4 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h4 class="text-lg font-bold text-white">Rata-Rata Nilai Akhir Per Kelas</h4>
            </div>
            <div class="h-80 relative">
                <canvas id="chartNilaiSekolah"></canvas>
            </div>
        </div>

        <!-- 5 Besar Peringkat Prestasi -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-3xl flex flex-col space-y-4 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h4 class="text-lg font-bold text-white">5 Besar Siswa Berprestasi</h4>
            </div>
            <div class="flex-1 divide-y divide-slate-800/60">
                @forelse($topStudents as $index => $topSiswa)
                <div class="flex justify-between items-center py-3.5">
                    <div>
                        <h6 class="font-bold text-white text-sm">{{ $topSiswa->nama }}</h6>
                        <span class="text-slate-400 text-xs">Kelas: {{ $topSiswa->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-[#F59E0B] text-slate-950 rounded mb-1 shadow-md shadow-orange-950/20">
                            Rank {{ $index + 1 }}
                        </span>
                        <p class="text-xs text-[#9F5261] font-bold">{{ number_format($topSiswa->rata_rata, 1) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-500 italic text-center py-12">Tidak ada data peringkat siswa.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 5. SEKSI TABEL RINGKASAN REKAPITULASI KKM (BAWAH) -->
    <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-3xl shadow-xl space-y-4">
        <div class="border-b border-slate-800 pb-4">
            <h4 class="text-lg font-bold text-white">Ringkasan Ketuntasan KKM Per Kelas</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-slate-300">
                <thead>
                    <tr class="bg-slate-900/40 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama Kelas</th>
                        <th class="py-4 px-6">Wali Kelas</th>
                        <th class="py-4 px-6">Jumlah Siswa</th>
                        <th class="py-4 px-6">Siswa Tuntas (&ge;75)</th>
                        <th class="py-4 px-6">Siswa Remedial (&lt;75)</th>
                        <th class="py-4 px-6">Persentase Ketercapaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse($classesSummary as $idx => $summary)
                    <tr class="hover:bg-slate-900/20 transition duration-150">
                        <td class="py-4 px-6 text-slate-450 text-slate-400 font-medium">{{ $idx + 1 }}</td>
                        <td class="py-4 px-6 font-bold text-white">{{ $summary['nama_kelas'] }}</td>
                        <td class="py-4 px-6 text-slate-300">{{ $summary['wali_kelas'] }}</td>
                        <td class="py-4 px-6">{{ $summary['jumlah_siswa'] }} Siswa</td>
                        <td class="py-4 px-6 text-emerald-400 font-semibold">{{ $summary['siswa_tuntas'] }} Siswa</td>
                        <td class="py-4 px-6 font-semibold {{ $summary['siswa_remedial'] > 0 ? 'text-red-400' : 'text-slate-500' }}">
                            {{ $summary['siswa_remedial'] }} Siswa
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-white w-12">{{ $summary['persentase'] }}%</span>
                                <div class="w-full bg-slate-950 rounded-full h-1.5 border border-slate-800/40">
                                    <div class="bg-[#9F5261] h-1.5 rounded-full" style="width: {{ $summary['persentase'] }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-550 italic">Tidak ada data rekapitulasi kelas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Achievements Analytics & Leaderboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaderboard (Top 5 Siswa Berprestasi) -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-3xl lg:col-span-2 space-y-4 shadow-xl">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <div>
                    <h4 class="text-lg font-bold text-white">Leaderboard - Top 5 Siswa Berprestasi (Sertifikat)</h4>
                    <p class="text-xs text-slate-400 mt-1">Peringkat siswa berdasarkan total akumulasi poin piagam penghargaan</p>
                </div>
            </div>
            
            <div class="space-y-3">
                @forelse($topPrestasi as $index => $tp)
                    <div class="p-3 rounded-xl border border-slate-800 bg-[#1F1215] flex items-center justify-between transition hover:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#9F5261]/20 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $tp->nama }}</p>
                                <p class="text-[10px] text-slate-400">Kelas: {{ $tp->nama_kelas }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            {{ $tp->total_poin }} Poin
                        </span>
                    </div>
                @empty
                    <!-- Fallback Mock Leaderboard items if empty database -->
                    <div class="p-3 rounded-xl border border-slate-800 bg-[#1F1215] flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#9F5261]/20 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #1
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Rian Hidayat (Demo)</p>
                                <p class="text-[10px] text-slate-400">Kelas: Kelas 6-A</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            115 Poin
                        </span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-800 bg-[#1F1215] flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#9F5261]/20 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #2
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Siti Rahma (Demo)</p>
                                <p class="text-[10px] text-slate-400">Kelas: Kelas 5-B</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            95 Poin
                        </span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-[#2D1B1F] border border-slate-800/80 p-6 rounded-3xl flex flex-col justify-between shadow-xl">
            <div class="mb-4 border-b border-slate-800 pb-3">
                <h4 class="text-lg font-bold text-white">Kategori Prestasi</h4>
                <p class="text-xs text-slate-400 mt-1">Perbandingan persentase prestasi akademik & non-akademik</p>
            </div>
            
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="prestasiDonutChart"></canvas>
            </div>
            
            <div class="flex justify-around mt-4 text-xs font-semibold text-slate-300">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#9F5261] me-1.5 inline-block"></span> Akademik</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#3D8B6F] me-1.5 inline-block"></span> Non-Akademik</span>
            </div>
        </div>
    </div>
</div>

<!-- Script Stack Section for Chart.js Rendering -->
@push('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartNilaiSekolah').getContext('2d');
        
        // Parse data from PHP backend variables
        const averages = @json($kelasAverages);
        const labels = averages.map(item => item.kelas);
        const dataValues = averages.map(item => item.rata_rata);

        // Chart initialization with Electric Blue theme
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-Rata Nilai Akhir',
                    data: dataValues,
                    backgroundColor: 'rgba(159, 82, 97, 0.45)',
                    borderColor: 'rgba(159, 82, 97, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(159, 82, 97, 0.7)'
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
                        backgroundColor: '#2D1B1F',
                        titleColor: '#FFFFFF',
                        bodyColor: '#9F5261',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `Rata-rata: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94A3B8',
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
                            color: '#94A3B8',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Achievements Donut Chart
        const donutCtx = document.getElementById('prestasiDonutChart').getContext('2d');
        const akademikVal = {{ $akademikCount }};
        const nonAkademikVal = {{ $nonAkademikCount }};
        
        // Show sample data if both are zero
        const donutData = (akademikVal === 0 && nonAkademikVal === 0) ? [60, 40] : [akademikVal, nonAkademikVal];

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Akademik', 'Non-Akademik'],
                datasets: [{
                    data: donutData,
                    backgroundColor: ['#9F5261', '#3D8B6F'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
@endsection
