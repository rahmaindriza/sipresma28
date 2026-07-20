@extends('layouts.dashboard')

@section('title', 'Dashboard Wali Kelas')

@section('content')
<div class="space-y-8">
    @if(isset($error))
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ $error }}</p>
    </div>
    @else
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-slate-900/40 p-6 border border-slate-800 rounded-3xl">
        <div>
            <h3 class="text-2xl font-bold text-white">{{ $kelas->nama_kelas }}</h3>
            <p class="text-xs text-slate-400 mt-1">Wali Kelas: <span class="text-white font-semibold">{{ auth()->user()->name }}</span></p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                Tahun Ajaran: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
            </span>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Siswa -->
        <div class="glass-panel p-6 rounded-3xl flex items-center space-x-4">
            <div class="p-3 bg-blue-600/20 text-blue-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Siswa</p>
                <p class="text-2xl font-bold text-white mt-1">{{ count($students) }}</p>
            </div>
        </div>

        <!-- Rata-rata Kelas -->
        <div class="glass-panel p-6 rounded-3xl flex items-center space-x-4">
            <div class="p-3 bg-indigo-600/20 text-indigo-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Rerata Kelas</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $avgClassScore }}</p>
            </div>
        </div>

        <!-- Total Prestasi -->
        <div class="glass-panel p-6 rounded-3xl flex items-center space-x-4">
            <div class="p-3 bg-amber-600/20 text-amber-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Prestasi Siswa</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $totalAchievements }}</p>
            </div>
        </div>

        <!-- Butuh Remedial -->
        <div class="glass-panel p-6 rounded-3xl flex items-center space-x-4">
            <div class="p-3 {{ $remedialCount > 0 ? 'bg-red-600/20 text-red-400' : 'bg-green-600/20 text-green-400' }} rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Butuh Remedial</p>
                <p class="text-2xl font-bold {{ $remedialCount > 0 ? 'text-red-400 font-bold' : 'text-white' }} mt-1">
                    {{ $remedialCount }} <span class="text-xs text-slate-500 font-normal">Siswa</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="glass-panel p-6 rounded-3xl space-y-4">
        <h4 class="text-lg font-bold text-white">Grafik Monitoring Perkembangan Rata-Rata Kelas</h4>
        <p class="text-xs text-slate-400">Menampilkan rata-rata nilai akhir siswa di kelas {{ $kelas->nama_kelas }} berdasarkan komponen nilai mata pelajaran aktif.</p>
        
        <div class="h-80 relative flex items-end justify-center">
            <canvas id="classAveragesChart"></canvas>
        </div>
    </div>

    <!-- New Visualizations: Achievement Distribution and Top 3 Class Leaderboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Donut Chart: Kategori Prestasi Kelas -->
        <div class="glass-panel p-6 rounded-3xl space-y-4">
            <h4 class="text-lg font-bold text-white">Distribusi Kategori Prestasi Kelas</h4>
            <p class="text-xs text-slate-400">Menampilkan perbandingan prestasi akademik vs non-akademik di kelas {{ $kelas->nama_kelas }}.</p>
            
            <div style="position: relative; height: 200px; width: 100%;" class="flex items-center justify-center">
                <canvas id="classPrestasiChart"></canvas>
            </div>
            
            <div class="flex justify-around text-xs font-semibold text-slate-450">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#9F5261] me-1.5 inline-block"></span> Akademik</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#3D8B6F] me-1.5 inline-block"></span> Non-Akademik</span>
            </div>
        </div>

        <!-- Leaderboard: Top 3 Siswa Berprestasi Kelas -->
        <div class="glass-panel p-6 rounded-3xl flex flex-col justify-between space-y-4">
            <div>
                <h4 class="text-lg font-bold text-white">3 Besar Siswa Berprestasi Kelas</h4>
                <p class="text-xs text-slate-400 mt-1">Papan peringkat siswa di kelas {{ $kelas->nama_kelas }} berdasarkan perolehan akumulasi poin piagam penghargaan.</p>
            </div>
            <div class="flex-1 divide-y divide-slate-800/60 mt-2">
                @forelse($topPrestasiKelas as $index => $tp)
                <div class="flex justify-between items-center py-3.5">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs
                            @if($index == 0) bg-amber-500 text-slate-950
                            @elseif($index == 1) bg-slate-350 text-slate-950
                            @else bg-amber-700 text-white
                            @endif">
                            @if($index == 0) 🥇
                            @elseif($index == 1) 🥈
                            @elseif($index == 2) 🥉
                            @else #{{ $index + 1 }}
                            @endif
                        </div>
                        <div>
                            <h6 class="font-bold text-white text-sm mb-0">{{ $tp->nama }}</h6>
                            <span class="text-slate-450 text-xs font-mono">NISN: {{ $tp->nisn }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            {{ $tp->total_poin }} Poin
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-500 italic text-sm">
                    <div class="fs-2 text-slate-400 mb-2"><i class="bi bi-trophy"></i></div>
                    Belum ada rekaman prestasi siswa terdaftar untuk kelas ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>

@if(!isset($error))
<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('classAveragesChart').getContext('2d');
        
        // Parse data from backend
        const averages = @json($chartData);
        const labels = averages.map(item => item.mapel);
        const dataValues = averages.map(item => item.rata_rata);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-rata Nilai Akhir',
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
                            color: '#94a3b8',
                            font: {
                                family: 'Plus Jakarta Sans'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: 'Plus Jakarta Sans'
                            }
                        }
                    }
                }
            }
        });

        // Prestasi Donut Chart
        const prestasiCtx = document.getElementById('classPrestasiChart').getContext('2d');
        const akademikCount = {{ $prestasiAkademik }};
        const nonAkademikCount = {{ $prestasiNonAkademik }};
        
        const donutData = (akademikCount === 0 && nonAkademikCount === 0) ? [1, 1] : [akademikCount, nonAkademikCount];
        const displayLabels = (akademikCount === 0 && nonAkademikCount === 0) ? ['Belum Ada Data', 'Belum Ada Data'] : ['Akademik', 'Non-Akademik'];

        new Chart(prestasiCtx, {
            type: 'doughnut',
            data: {
                labels: displayLabels,
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
@endif
@endsection
