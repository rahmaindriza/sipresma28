@extends('layouts.main')

@section('title', 'Input Nilai Mata Pelajaran Umum')

@section('content')
<style>
    /* Styling overrides for luxury Deep Burgundy theme */
    .bg-card-dark {
        background-color: var(--card-dark-burgundy) !important;
    }
    .text-white-custom {
        color: #FFFFFF !important;
    }
    .text-accent-blue {
        color: var(--primary-burgundy) !important;
    }
    .btn-electric-blue {
        background-color: var(--primary-burgundy) !important;
        color: #FFFFFF !important;
        transition: all 0.3s ease;
    }
    .btn-electric-blue:hover {
        background-color: var(--primary-hover) !important;
        box-shadow: 0 4px 15px rgba(159, 82, 97, 0.35) !important;
    }
    /* Table body styling overrides */
    table.table-dark-custom tbody tr {
        background-color: var(--card-dark-burgundy) !important;
        border-bottom: 1px solid var(--border-dark-burgundy) !important;
    }
    table.table-dark-custom tbody tr:hover {
        background-color: rgba(159, 82, 97, 0.08) !important;
    }
    table.table-dark-custom thead tr {
        border-bottom: 2px solid var(--border-dark-burgundy) !important;
    }
</style>

<div class="space-y-6 p-6 rounded-3xl bg-card-dark border border-slate-800/40 shadow-xl">
    <!-- Header Title Area -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800/60 pb-5">
        <div>
            <h3 class="text-xl font-bold text-white">Input Nilai Mata Pelajaran Umum (Wali Kelas)</h3>
            <p class="text-xs text-slate-400 mt-1">
                Wali Kelas: <span class="text-white font-semibold">{{ auth()->user()->name }}</span> 
                @if($kelas)
                    | Kelas Diampu: <span class="text-accent-blue font-semibold">{{ $kelas->nama_kelas }}</span>
                @endif
            </p>
        </div>
        @if($activeTa)
        <div class="mt-3 md:mt-0">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-900/30 text-blue-300 border border-blue-800/50">
                Tahun Ajaran: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
            </span>
        </div>
        @endif
    </div>

    <!-- Filters & Action Bar -->
    <div class="p-5 rounded-2xl bg-slate-950/45 border border-slate-800/60 shadow-inner">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5">
            <div class="flex-1 w-full">
                <!-- Dropdown Filter form -->
                <form id="filterForm" method="GET" action="{{ route('walas.nilai.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mata Pelajaran Umum</label>
                        <select name="mapel_id" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800/80 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" onchange="document.getElementById('filterForm').submit();">
                            @forelse($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }} (KKM: 75)
                                </option>
                            @empty
                                <option value="">Tidak ada Mapel Umum</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kelas</label>
                        <select name="kelas_id" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800/80 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" onchange="document.getElementById('filterForm').submit();">
                            @forelse($kelas_list as $kls)
                                <option value="{{ $kls->id }}" {{ $kelasId == $kls->id ? 'selected' : '' }}>
                                    Kelas {{ $kls->nama_kelas }}
                                </option>
                            @empty
                                <option value="">Tidak ada Kelas</option>
                            @endforelse
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="shrink-0 w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                <a href="{{ route('walas.nilai.cetak', ['mapel_id' => $mapelId]) }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition duration-200 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Rekap Nilai
                </a>
                <button type="submit" form="gradeForm" class="w-full sm:w-auto px-5 py-2.5 btn-electric-blue text-white text-sm font-semibold rounded-xl transition duration-200 flex items-center justify-center shadow-lg">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Semua Nilai
                </button>
            </div>
        </div>
    </div>

    <!-- Main Form and Grade Table -->
    <form id="gradeForm" method="POST" action="{{ route('walas.nilai.store') }}">
        @csrf
        <input type="hidden" name="mapel_id" value="{{ $mapelId }}">
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

        <div class="bg-slate-950/45 border border-slate-800/60 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-dark-custom">
                    <thead>
                        <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-4 text-center w-16">No</th>
                            <th class="py-4 px-4 w-44">NISN</th>
                            <th class="py-4 px-6">Nama Siswa</th>
                            <th class="py-4 px-4 text-center w-28">Tugas (20%)</th>
                            <th class="py-4 px-4 text-center w-28">UH (20%)</th>
                            <th class="py-4 px-4 text-center w-28">UTS (30%)</th>
                            <th class="py-4 px-4 text-center w-28">UAS (30%)</th>
                            <th class="py-4 px-6 text-center w-36">Nilai Akhir</th>
                            <th class="py-4 px-6 text-center w-36">Status KKM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-sm text-slate-350">
                        @forelse($siswas as $index => $siswa)
                            @php
                                $grade = $siswa->nilai_tugas !== null || $siswa->nilai_uh !== null || $siswa->nilai_uts !== null || $siswa->nilai_uas !== null ? (object)[
                                    'tugas' => $siswa->nilai_tugas,
                                    'uh' => $siswa->nilai_uh,
                                    'uts' => $siswa->nilai_uts,
                                    'uas' => $siswa->nilai_uas,
                                ] : null;
                            @endphp
                            <tr class="hover:bg-slate-900/25 transition duration-150 student-row" data-siswa-id="{{ $siswa->siswa_id }}">
                                <td class="py-4 px-4 text-center font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="py-4 px-4 font-mono text-xs text-slate-400">{{ $siswa->nisn }}</td>
                                <td class="py-4 px-6 font-semibold text-white">
                                    {{ $siswa->nama_siswa }}
                                    <input type="hidden" name="grades[{{ $index }}][siswa_id]" value="{{ $siswa->siswa_id }}">
                                </td>
                                
                                <!-- Input Tugas -->
                                <td class="py-3 px-2 text-center">
                                    <input type="number" 
                                           step="0.01"
                                           name="grades[{{ $index }}][tugas]" 
                                           value="{{ $grade ? $grade->tugas : 0 }}" 
                                           min="0" max="100" 
                                           class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800/90 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/25 transition text-sm tugas-input">
                                </td>

                                <!-- Input UH -->
                                <td class="py-3 px-2 text-center">
                                    <input type="number" 
                                           step="0.01"
                                           name="grades[{{ $index }}][uh]" 
                                           value="{{ $grade ? $grade->uh : 0 }}" 
                                           min="0" max="100" 
                                           class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800/90 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/25 transition text-sm uh-input">
                                </td>

                                <!-- Input UTS -->
                                <td class="py-3 px-2 text-center">
                                    <input type="number" 
                                           step="0.01"
                                           name="grades[{{ $index }}][uts]" 
                                           value="{{ $grade ? $grade->uts : 0 }}" 
                                           min="0" max="100" 
                                           class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800/90 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/25 transition text-sm uts-input">
                                </td>

                                <!-- Input UAS -->
                                <td class="py-3 px-2 text-center">
                                    <input type="number" 
                                           step="0.01"
                                           name="grades[{{ $index }}][uas]" 
                                           value="{{ $grade ? $grade->uas : 0 }}" 
                                           min="0" max="100" 
                                           class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800/90 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/25 transition text-sm uas-input">
                                </td>

                                <!-- Readonly Nilai Akhir -->
                                <td class="py-3 px-6 text-center">
                                    <input type="text" 
                                           value="{{ $siswa->nilai_akhir ? $siswa->nilai_akhir : '0.00' }}" 
                                           readonly 
                                           class="w-24 bg-transparent border-0 text-center font-bold text-white focus:outline-none final-score-input">
                                </td>

                                <!-- Status KKM Badge -->
                                <td class="py-3 px-6 text-center status-kkm-badge">
                                    @if($siswa->nilai_akhir !== null)
                                        @if($siswa->nilai_akhir >= 75)
                                            <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg kkm-lulus">Lulus</span>
                                        @else
                                            <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg kkm-remedial">Remedial</span>
                                        @endif
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg kkm-remedial">Remedial</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-16 text-center text-slate-500 font-semibold">
                                    <svg class="w-10 h-10 mb-3 text-slate-700 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Tidak ada data siswa ditemukan untuk kelas yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.student-row');

        function calculateRow(row) {
            const tugasInput = row.querySelector('.tugas-input');
            const uhInput = row.querySelector('.uh-input');
            const utsInput = row.querySelector('.uts-input');
            const uasInput = row.querySelector('.uas-input');
            const finalScoreInput = row.querySelector('.final-score-input');
            const badgeContainer = row.querySelector('.status-kkm-badge');

            // Float parsing to support step="0.01" decimal inputs
            const tugas = parseFloat(tugasInput.value) || 0;
            const uh = parseFloat(uhInput.value) || 0;
            const uts = parseFloat(utsInput.value) || 0;
            const uas = parseFloat(uasInput.value) || 0;

            // Weighted formula calculation: Tugas (20%), UH (20%), UTS (30%), UAS (30%)
            const finalScore = (tugas * 0.20) + (uh * 0.20) + (uts * 0.30) + (uas * 0.30);
            
            // Set the read-only final score
            finalScoreInput.value = finalScore.toFixed(2);

            // Update badge color and content dynamically based on the KKM threshold of 75
            if (finalScore >= 75) {
                badgeContainer.innerHTML = '<span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg kkm-lulus">Lulus</span>';
            } else {
                badgeContainer.innerHTML = '<span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg kkm-remedial">Remedial</span>';
            }
        }

        rows.forEach(row => {
            // Compute initially loaded score
            calculateRow(row);

            const inputs = row.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Prevent inputs outside 0-100 range
                    let val = parseFloat(this.value);
                    if (val > 100) this.value = 100;
                    if (val < 0) this.value = 0;

                    calculateRow(row);
                });
            });
        });
    });
</script>
@endsection
