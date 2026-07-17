@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <!-- Header Summary -->
    <div>
        <h3 class="text-2xl font-bold text-white">Selamat Datang, Administrator</h3>
        <p class="text-sm text-slate-400 mt-1">Gunakan panel ini untuk mengelola data master, akun pengguna, penugasan guru, dan prestasi sekolah.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Users -->
        <div class="glass-panel p-6 rounded-2xl flex items-center space-x-4">
            <div class="p-3 rounded-xl" style="background-color: rgba(159, 82, 97, 0.15); color: var(--primary-burgundy);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold">Pengguna</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['users'] }}</p>
            </div>
        </div>

        <!-- Gurus -->
        <div class="glass-panel p-6 rounded-2xl flex items-center space-x-4">
            <div class="p-3 rounded-xl" style="background-color: rgba(159, 82, 97, 0.15); color: var(--primary-burgundy);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold">Guru</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['gurus'] }}</p>
            </div>
        </div>

        <!-- Siswa -->
        <div class="glass-panel p-6 rounded-2xl flex items-center space-x-4">
            <div class="p-3 rounded-xl" style="background-color: rgba(159, 82, 97, 0.15); color: var(--primary-burgundy);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold">Siswa</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['siswas'] }}</p>
            </div>
        </div>

        <!-- Kelas -->
        <div class="glass-panel p-6 rounded-2xl flex items-center space-x-4">
            <div class="p-3 rounded-xl" style="background-color: rgba(159, 82, 97, 0.15); color: var(--primary-burgundy);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold">Kelas</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['kelas'] }}</p>
            </div>
        </div>

        <!-- Mapel -->
        <div class="glass-panel p-6 rounded-2xl flex items-center space-x-4">
            <div class="p-3 rounded-xl" style="background-color: rgba(159, 82, 97, 0.15); color: var(--primary-burgundy);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold">Mapel</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['mapels'] }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Access Matrix -->
    <div class="glass-panel p-8 rounded-3xl">
        <h4 class="text-lg font-bold text-white mb-6">Pintasan Manajemen Data</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Manage Users Card -->
            <a href="{{ route('admin.users') }}" class="group block p-6 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-[var(--primary-burgundy)] rounded-2xl transition duration-300">
                <div class="flex justify-between items-start">
                    <span class="p-3 bg-purple-600/10 text-purple-400 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                    <span class="text-blue-500 opacity-0 group-hover:opacity-100 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
                <h5 class="text-white font-bold text-base mt-4">Manajemen Pengguna</h5>
                <p class="text-xs text-slate-400 mt-1">Kelola dan aktifkan/nonaktifkan akun guru, wali kelas, dan kepsek.</p>
            </a>

            <!-- Manage Master Data Card -->
            <a href="{{ route('admin.gurus') }}" class="group block p-6 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-[var(--primary-burgundy)] rounded-2xl transition duration-300">
                <div class="flex justify-between items-start">
                    <span class="p-3 bg-blue-600/10 text-blue-400 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    <span class="text-blue-500 opacity-0 group-hover:opacity-100 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
                <h5 class="text-white font-bold text-base mt-4">Data Akademik</h5>
                <p class="text-xs text-slate-400 mt-1">Input data siswa, guru pengajar, kelas, mapel, dan tahun ajaran.</p>
            </a>

            <!-- Manage Assignments Card -->
            <a href="{{ route('admin.assignments') }}" class="group block p-6 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-[var(--primary-burgundy)] rounded-2xl transition duration-300">
                <div class="flex justify-between items-start">
                    <span class="p-3 bg-yellow-600/10 text-yellow-400 rounded-xl group-hover:bg-yellow-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </span>
                    <span class="text-blue-500 opacity-0 group-hover:opacity-100 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
                <h5 class="text-white font-bold text-base mt-4">Penugasan Guru</h5>
                <p class="text-xs text-slate-400 mt-1">Petakan guru mengajar mapel apa di kelas mana pada semester aktif.</p>
            </a>

            <!-- Manage achievements Card -->
            <a href="{{ route('admin.prestasis') }}" class="group block p-6 bg-slate-950/40 hover:bg-slate-900 border border-slate-800 hover:border-[var(--primary-burgundy)] rounded-2xl transition duration-300">
                <div class="flex justify-between items-start">
                    <span class="p-3 bg-rose-600/10 text-rose-400 rounded-xl group-hover:bg-rose-600 group-hover:text-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </span>
                    <span class="text-blue-500 opacity-0 group-hover:opacity-100 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
                <h5 class="text-white font-bold text-base mt-4">Prestasi Sekolah</h5>
                <p class="text-xs text-slate-400 mt-1">Catat dan pantau prestasi akademik/non-akademik siswa SDN 28 Kinali.</p>
            </a>
        </div>
    </div>

    <!-- Achievements Analytics & Leaderboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaderboard (Top 5 Siswa Berprestasi) -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-[var(--border-light)] pb-3">
                <div>
                    <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Leaderboard - Top 5 Siswa Berprestasi</h4>
                    <p class="text-[10px] text-[var(--text-muted)]">Peringkat siswa berdasarkan total akumulasi poin piagam penghargaan</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded bg-[#E9F5F0] text-[#245E49] border border-[rgba(36,94,73,0.15)]">
                    SDN 28 Kinali
                </span>
            </div>
            
            <div class="space-y-3">
                @forelse($topPrestasi as $index => $tp)
                    <div class="p-3 rounded-xl border border-[var(--border-light)] bg-white flex items-center justify-between transition hover:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#FDF4F5] text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[var(--text-dark-main)]">{{ $tp->nama }}</p>
                                <p class="text-[10px] text-[var(--text-muted)]">Kelas: {{ $tp->nama_kelas }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-[#E9F5F0] text-[#245E49] border border-[rgba(36,94,73,0.25)]">
                            {{ $tp->total_poin }} Poin
                        </span>
                    </div>
                @empty
                    <!-- Fallback Mock Leaderboard items if empty database -->
                    <div class="p-3 rounded-xl border border-[var(--border-light)] bg-white flex items-center justify-between transition hover:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#FDF4F5] text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #1
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[var(--text-dark-main)]">Rian Hidayat (Demo)</p>
                                <p class="text-[10px] text-[var(--text-muted)]">Kelas: Kelas 6-A</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-[#E9F5F0] text-[#245E49] border border-[rgba(36,94,73,0.25)]">
                            115 Poin
                        </span>
                    </div>
                    <div class="p-3 rounded-xl border border-[var(--border-light)] bg-white flex items-center justify-between transition hover:shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-[#FDF4F5] text-[#9F5261] flex items-center justify-center font-bold text-xs">
                                #2
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[var(--text-dark-main)]">Siti Rahma (Demo)</p>
                                <p class="text-[10px] text-[var(--text-muted)]">Kelas: Kelas 5-B</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-[#E9F5F0] text-[#245E49] border border-[rgba(36,94,73,0.25)]">
                            95 Poin
                        </span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="lg:col-span-1 glass-panel p-6 rounded-2xl shadow-sm flex flex-col justify-between">
            <div class="mb-4 border-b border-[var(--border-light)] pb-3">
                <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Kategori Prestasi</h4>
                <p class="text-[10px] text-[var(--text-muted)]">Perbandingan persentase prestasi akademik & non-akademik</p>
            </div>
            
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="prestasiDonutChart"></canvas>
            </div>
            
            <div class="flex justify-around mt-4 text-[10px] font-bold text-[var(--text-dark-main)]">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#9F5261] me-1.5 inline-block"></span> Akademik</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#3D8B6F] me-1.5 inline-block"></span> Non-Akademik</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('prestasiDonutChart').getContext('2d');
        const akademikVal = {{ $akademikCount }};
        const nonAkademikVal = {{ $nonAkademikCount }};
        
        // Show sample data if both are zero
        const chartData = (akademikVal === 0 && nonAkademikVal === 0) ? [60, 40] : [akademikVal, nonAkademikVal];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Akademik', 'Non-Akademik'],
                datasets: [{
                    data: chartData,
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
