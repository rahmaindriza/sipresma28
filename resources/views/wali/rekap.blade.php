@extends('layouts.dashboard')

@section('title', 'Rekap Nilai & Ranking')

@section('content')
<div class="space-y-6">
    @if(isset($error))
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ $error }}</p>
    </div>
    @else
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-slate-900/40 p-6 border border-slate-800 rounded-3xl gap-4">
        <div>
            <h3 class="text-xl font-bold text-white">Rekapitulasi Nilai & Ranking Kelas</h3>
            <p class="text-xs text-slate-400 mt-1">Rekap seluruh mata pelajaran (Umum & Khusus), rata-rata nilai akhir, prestasi, dan cetak dokumen resmi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                {{ $kelas->nama_kelas }}
            </span>
        </div>
    </div>

    <!-- Remedial Notifications / Warnings -->
    @if(count($remedialAlerts) > 0)
    <div class="mb-6 rounded-2xl border border-red-200 bg-white overflow-hidden shadow-sm">
        <!-- Header -->
        <div class="px-6 py-4 bg-red-50/50 border-b border-red-100 flex items-center gap-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-base font-bold text-red-800">Perhatian: Nilai di Bawah KKM (75)</h4>
                <p class="text-xs text-red-600 mt-0.5">Daftar siswa berikut memerlukan penanganan remedial. Klik mata pelajaran untuk melihat detail.</p>
            </div>
        </div>
        
        <!-- Accordions -->
        <div class="divide-y divide-red-100/50 max-h-[28rem] overflow-y-auto">
            @foreach($remedialAlerts as $mapel => $alerts)
            <details class="group bg-white">
                <summary class="flex justify-between items-center px-6 py-3.5 cursor-pointer hover:bg-slate-50 transition-colors select-none">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <span class="font-bold text-slate-800 text-sm">{{ $mapel }}</span>
                        <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-700 text-xs font-semibold">{{ count($alerts) }} Siswa</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="px-6 pb-4 pt-1 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-2">
                        @foreach($alerts as $alert)
                        <div class="flex items-center justify-between py-2 border-b border-slate-200/60 last:border-0">
                            <span class="text-sm font-semibold text-slate-700">{{ $alert['siswa'] }}</span>
                            <span class="font-bold text-red-600 bg-white border border-red-100 px-2 py-0.5 rounded shadow-sm text-xs">{{ $alert['nilai'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </details>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Student Rekapitulasi Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-4 w-12 text-center">No</th>
                        <th class="py-4 px-6 w-56">Nama Siswa</th>
                        @foreach($mapels as $m)
                        <th class="py-4 px-3 text-center" title="{{ $m->nama_mapel }}">{{ $m->kode_mapel }}</th>
                        @endforeach
                        <th class="py-4 px-4 text-center w-20">Rerata</th>
                        <th class="py-4 px-3 text-center w-16">Rank</th>
                        <th class="py-4 px-4 text-right">Laporan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs text-slate-355">
                    @if(count($students) === 0)
                    <tr>
                        <td colspan="{{ count($mapels) + 5 }}" class="py-8 text-center text-slate-500 italic">
                            Belum ada data siswa di kelas ini.
                        </td>
                    </tr>
                    @else
                        @foreach($students as $siswa)
                        @php
                            $siswaGrades = $grades->get($siswa->id) ?? collect();
                            $siswaRank = $ranks[$siswa->id] ?? ['rank' => '-', 'rata_rata' => 0];
                            $siswaAchievements = $achievements->get($siswa->id) ?? collect();
                        @endphp
                        <tr class="hover:bg-slate-900/20 transition duration-150">
                            <td class="py-4 px-4 text-center font-semibold text-slate-450">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-white text-sm">{{ $siswa->nama }}</p>

                                </div>
                                <p class="text-[10px] text-slate-500 mt-0.5 font-mono">NIS / NISN: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</p>
                                
                                <!-- Display Achievements directly under name if any -->
                                @if($siswaAchievements->isNotEmpty())
                                <div class="mt-2 space-y-1">
                                    @foreach($siswaAchievements as $ach)
                                    <div class="inline-flex items-center px-1.5 py-0.5 rounded bg-amber-950/40 text-amber-400 border border-amber-900/40 text-[9px] mr-1">
                                        🏆 {{ $ach->jenis_prestasi }}: {{ Str::limit($ach->keterangan, 25) }}
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            
                            <!-- Mapel Grades -->
                            @foreach($mapels as $m)
                            @php
                                $g = $siswaGrades->where('mapel_id', $m->id)->first();
                            @endphp
                            <td class="py-4 px-3 text-center font-bold">
                                @if($g)
                                    <span class="{{ $g->nilai_akhir < 75 ? 'text-red-600 dark:text-red-400 font-bold bg-red-100 dark:bg-red-950/20 px-1.5 py-0.5 rounded border border-red-200 dark:border-red-900/30' : 'font-extrabold text-slate-800 dark:text-slate-200' }}">
                                        {{ round($g->nilai_akhir, 0) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 font-bold">-</span>
                                @endif
                            </td>
                            @endforeach
                            
                            <!-- Average -->
                            <td class="py-4 px-4 text-center font-extrabold text-blue-400 text-sm">
                                {{ $siswaRank['rata_rata'] }}
                            </td>
                            
                            <!-- Rank -->
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center justify-center min-w-[28px] px-2 py-1 rounded-lg font-extrabold text-xs shadow-sm
                                    {{ $siswaRank['rank'] <= 3 ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-700/80 shadow-amber-200/50' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700/50' }}">
                                    {{ $siswaRank['rank'] }}
                                </span>
                            </td>
                            
                            <!-- Laporan Button -->
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @php
                                        $siswaRapor = $raporSiswas->get($siswa->id);
                                    @endphp
                                    <a href="{{ route('wali.rapor.edit', $siswa->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition shadow-lg shadow-emerald-600/30" style="color: white !important;">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Input Rapor
                                    </a>
                                    <a href="{{ route('wali.print', [$siswa->id, 'tahun_ajaran_id' => $selectedTa->id]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-900 hover:border-blue-500 font-bold rounded-lg transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Cetak PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>


@endsection
