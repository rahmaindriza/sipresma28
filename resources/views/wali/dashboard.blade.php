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
    });
</script>
@endif
@endsection
