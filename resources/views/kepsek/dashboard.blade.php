@extends('layouts.main')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Custom Styles to enforce KEPSEK light theme & color palette overrides -->
<style>
    main {
        background-color: #FAF7F7 !important; /* krem lembut terang */
    }
    
    .kepsek-dashboard-container {
        color: var(--text-dark-main) !important;
    }

    .kepsek-card {
        background-color: #FFFFFF !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 16px !important;
        box-shadow: 0px 4px 6px -1px rgba(61, 34, 40, 0.03), 0px 2px 4px -1px rgba(61, 34, 40, 0.02) !important; /* shadow-sm */
    }

    .kepsek-card h4, .kepsek-card h6, .kepsek-card p, .kepsek-card span, .kepsek-card h3 {
        color: var(--text-dark-main) !important;
    }

    .kepsek-card .text-slate-400, .kepsek-card .text-slate-300, .kepsek-card .text-slate-500 {
        color: var(--text-muted) !important;
    }

    /* Leaderboard items light bg */
    .kepsek-leaderboard-item {
        background-color: #FAF7F7 !important;
        border: 1px solid var(--border-light) !important;
    }
    
    .kepsek-leaderboard-item p, .kepsek-leaderboard-item span {
        color: var(--text-dark-main) !important;
    }

    .text-emerald-450 {
        color: #245E49 !important;
    }
    
    .bg-emerald-500\/10 {
        background-color: #E9F5F0 !important;
        color: #245E49 !important;
        border: 1px solid rgba(36, 94, 73, 0.2) !important;
    }
    
    .text-red-400 {
        color: #A82E43 !important;
    }
</style>

<div class="space-y-8 text-[var(--text-dark-main)] kepsek-dashboard-container">
    @php
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
            <h3 class="text-2xl font-bold text-[var(--text-dark-main)]">Dashboard Pemantauan Kepala Sekolah</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Ringkasan grafik capaian nilai akademik dan perkembangan prestasi siswa SDN 28 Kinali</p>
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
        <div class="kepsek-card p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Total Siswa Aktif</p>
                <p class="text-3xl font-bold text-[var(--text-dark-main)] mt-1">{{ $totalSiswa }} Siswa</p>
            </div>
            <div class="p-3 bg-[#9F5261]/15 text-[#9F5261] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Rata-Rata Nilai Akademik -->
        <div class="kepsek-card p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Rata-Rata Nilai Akademik</p>
                <p class="text-3xl font-bold text-[var(--primary-burgundy)] mt-1">{{ number_format($rataRataNilai, 1) }}</p>
            </div>
            <div class="p-3 bg-[#9F5261]/15 text-[#9F5261] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Persentase Kelulusan KKM -->
        <div class="kepsek-card p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Lulus KKM (&ge;75)</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1">{{ number_format($passingPercentage, 1) }}%</p>
            </div>
            <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Total Sertifikat Prestasi -->
        <div class="kepsek-card p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-[var(--text-muted)] font-semibold uppercase tracking-wider">Total Sertifikat Prestasi</p>
                <p class="text-3xl font-bold text-[#F59E0B] mt-1">{{ $totalPrestasi }} <span class="text-xs text-[var(--text-muted)] font-normal">Piagam/Sertifikat</span></p>
            </div>
            <div class="p-3 bg-[#F59E0B]/10 text-[#F59E0B] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- 3. GRAFIK RATA-RATA NILAI AKADEMIK KELAS (AKADEMIK) -->
    <div class="kepsek-card p-6 space-y-4 shadow-sm">
        <div class="border-b border-[var(--border-light)] pb-4">
            <h4 class="text-lg font-bold text-[var(--text-dark-main)]">Rata-Rata Nilai Akhir Per Kelas</h4>
            <p class="text-xs text-[var(--text-muted)] mt-1">Grafik Perkembangan Nilai Akademik Rapor Siswa</p>
        </div>
        <div class="h-80 relative">
            <canvas id="chartNilaiSekolah"></canvas>
        </div>
    </div>

    <!-- 4. SEKSI LEADERBOARD PRESTASI (KIRI), KATEGORI PRESTASI (TENGAH), & JUARA UMUM SEKOLAH (KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Leaderboard (Top 5 Siswa Berprestasi) -->
        <div class="kepsek-card p-6 space-y-4 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-[var(--border-light)] pb-3">
                    <div>
                        <h4 class="text-lg font-bold text-[var(--text-dark-main)]">Leaderboard - Top 5 Siswa Berprestasi (Sertifikat)</h4>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Peringkat siswa berdasarkan total akumulasi jumlah sertifikat penghargaan</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @forelse($topPrestasi as $index => $tp)
                        <div class="kepsek-leaderboard-item p-3 rounded-xl border border-[var(--border-light)] bg-[#FAF7F7] flex items-center justify-between transition hover:shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-[#9F5261]/10 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[var(--text-dark-main)] mb-0">{{ $tp->nama }}</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mb-0">Kelas: {{ $tp->nama_kelas }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $tp->total_sertifikat }} Sertifikat
                            </span>
                        </div>
                    @empty
                        <!-- Fallback Mock Leaderboard items if empty database -->
                        <div class="kepsek-leaderboard-item p-3 rounded-xl border border-[var(--border-light)] bg-[#FAF7F7] flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-[#9F5261]/10 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                    1
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[var(--text-dark-main)] mb-0">Rian Hidayat (Demo)</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mb-0">Kelas: Kelas 6-A</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                3 Sertifikat
                            </span>
                        </div>
                        <div class="kepsek-leaderboard-item p-3 rounded-xl border border-[var(--border-light)] bg-[#FAF7F7] flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-[#9F5261]/10 text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                    2
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[var(--text-dark-main)] mb-0">Siti Rahma (Demo)</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mb-0">Kelas: Kelas 5-B</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                2 Sertifikat
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="kepsek-card p-6 flex flex-col justify-between shadow-sm">
            <div class="mb-4 border-b border-[var(--border-light)] pb-3">
                <h4 class="text-lg font-bold text-[var(--text-dark-main)]">Kategori Prestasi</h4>
                <p class="text-xs text-[var(--text-muted)] mt-1">Perbandingan persentase prestasi akademik & non-akademik</p>
            </div>
            
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="prestasiDonutChart"></canvas>
            </div>
            
            <div class="flex justify-around mt-4 text-xs font-semibold text-[var(--text-muted)]">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#9F5261] me-1.5 inline-block"></span> Akademik</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#3D8B6F] me-1.5 inline-block"></span> Non-Akademik</span>
            </div>
        </div>

        <!-- Juara Umum Sekolah -->
        <div class="kepsek-card p-6 space-y-4 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-[var(--border-light)] pb-3">
                    <div>
                        <h4 class="text-lg font-bold text-[var(--text-dark-main)]">Juara Umum Sekolah</h4>
                        <p class="text-xs text-[var(--text-muted)] mt-1">3 Nilai Akademik Rerata Rapor Tertinggi Lintas Kelas 1-6</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @forelse($juaraUmum as $index => $juara)
                        <div class="kepsek-leaderboard-item p-3 rounded-xl border border-[var(--border-light)] bg-[#FAF7F7] flex items-center justify-between transition hover:shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 flex items-center justify-center text-xl" 
                                     style="color: @if($index == 0) #FFD700 @elseif($index == 1) #C0C0C0 @else #CD7F32 @endif;">
                                    <i class="fa-solid fa-medal"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[var(--text-dark-main)] mb-0">Juara Umum {{ $index + 1 }}</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mb-0">{{ $juara->nama }} ({{ $juara->nama_kelas }})</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-white text-[var(--text-dark-main)] border border-[var(--border-light)]">
                                Rerata: {{ number_format($juara->rata_rata, 1) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-[var(--text-muted)] italic text-center py-6">Belum ada data nilai akademik.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- 5. SEKSI TABEL RINGKASAN REKAPITULASI KKM (BAWAH) -->
    <div class="kepsek-card p-6 shadow-sm space-y-4">
        <div class="border-b border-[var(--border-light)] pb-4">
            <h4 class="text-lg font-bold text-[var(--text-dark-main)]">Ringkasan Ketuntasan KKM Per Kelas</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[var(--text-dark-main)] bg-white rounded-xl">
                <thead>
                    <tr class="bg-[#FDF4F5] border-b border-[var(--border-light)] text-xs font-semibold text-[#9F5261] uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama Kelas</th>
                        <th class="py-4 px-6">Wali Kelas</th>
                        <th class="py-4 px-6">Jumlah Siswa</th>
                        <th class="py-4 px-6">Siswa Tuntas (&ge;75)</th>
                        <th class="py-4 px-6">Siswa Remedial (&lt;75)</th>
                        <th class="py-4 px-6">Persentase Ketercapaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)] text-sm">
                    @forelse($classesSummary as $idx => $summary)
                    <tr class="hover:bg-[var(--accent-table-hover)] border-b border-[var(--border-light)] transition duration-150">
                        <td class="py-4 px-6 text-[var(--text-muted)] font-medium">{{ $idx + 1 }}</td>
                        <td class="py-4 px-6 font-bold text-[var(--text-dark-main)]">{{ $summary['nama_kelas'] }}</td>
                        <td class="py-4 px-6 text-[var(--text-dark-main)]">{{ $summary['wali_kelas'] }}</td>
                        <td class="py-4 px-6 text-[var(--text-dark-main)]">{{ $summary['jumlah_siswa'] }} Siswa</td>
                        <td class="py-4 px-6 text-emerald-600 font-semibold">{{ $summary['siswa_tuntas'] }} Siswa</td>
                        <td class="py-4 px-6 font-semibold {{ $summary['siswa_remedial'] > 0 ? 'text-red-600' : 'text-[var(--text-muted)]' }}">
                            {{ $summary['siswa_remedial'] }} Siswa
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-[var(--text-dark-main)] w-12">{{ $summary['persentase'] }}%</span>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 border border-slate-200">
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

        // Chart initialization with Burgundy theme
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-Rata Nilai Akhir',
                    data: dataValues,
                    backgroundColor: 'rgba(244, 63, 94, 0.75)', // Rose-500 bright contrast rose-red
                    borderColor: 'rgba(244, 63, 94, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(225, 29, 72, 0.9)' // Rose-600 hover
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
                        backgroundColor: '#FFFFFF',
                        titleColor: '#3D2228',
                        bodyColor: '#9F5261',
                        borderColor: '#EAE1E3',
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
                            color: 'rgba(61, 34, 40, 0.05)'
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
