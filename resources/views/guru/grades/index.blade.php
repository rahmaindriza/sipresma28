@extends('layouts.dashboard')

@section('title', 'Input Nilai Mapel - Guru')

@section('content')
<style>
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
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-slate-900/40 p-6 border border-slate-800 rounded-3xl">
        <div>
            <h3 class="text-xl font-bold text-white">Input Nilai Mata Pelajaran Khusus (Guru Mapel)</h3>
            <p class="text-xs text-slate-400 mt-1">Masukkan komponen nilai akademik massal berdasarkan kelas dan mata pelajaran khusus yang Anda ampu.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                Tahun Ajaran: {{ $activeTa ? $activeTa->tahun : '-' }}
            </span>
        </div>
    </div>

    <!-- Alert bobot nilai -->
    <div class="p-5 rounded-2xl bg-blue-950/45 border border-blue-900/45 flex items-start space-x-3.5 shadow-lg">
        <div class="p-2.5 bg-blue-600/20 text-blue-400 rounded-xl shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="text-xs text-slate-300 leading-relaxed">
            <span class="font-bold text-blue-400">Aturan Bobot Penilaian (SDN 28 Kinali):</span> Tugas: 20%, Ulangan Harian (UH): 20%, UTS: 30%, UAS: 30%.
            KKM standar kelulusan minimal mata pelajaran adalah <span class="font-bold text-white">75</span>. Estimasi Nilai Akhir dan status kelulusan akan dihitung secara langsung di tabel saat Anda mengetikkan nilai komponen.
        </div>
    </div>

    <!-- Filters & Action Bar -->
    <div class="p-5 rounded-2xl bg-slate-950/45 border border-slate-800/60 shadow-inner">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5">
            <div class="flex-1 w-full">
                <!-- Dropdown Filter form -->
                <form id="filterForm" method="GET" action="{{ route('guru.grades.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pilih Kelas</label>
                        <select name="kelas_id" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800/80 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" onchange="document.getElementById('filterForm').submit();">
                            <option value="">-- Pilih Kelas --</option>
                            @forelse($all_kelas as $kls)
                                <option value="{{ $kls->id }}" {{ $kelas_id == $kls->id ? 'selected' : '' }}>
                                    {{ $kls->nama_kelas }}
                                </option>
                            @empty
                                <option value="">Tidak ada Kelas</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                        <select name="mapel_id" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800/80 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" onchange="document.getElementById('filterForm').submit();">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @forelse($all_mapel as $mapel)
                                <option value="{{ $mapel->id }}" {{ $mapel_id == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @empty
                                <option value="">Tidak ada Mapel</option>
                            @endforelse
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="shrink-0 w-full lg:w-auto">
                <button type="submit" form="gradeForm" class="w-full lg:w-auto px-5 py-2.5 bg-[#9F5261] hover:bg-[#86414E] text-white text-sm font-semibold rounded-xl transition duration-200 flex items-center justify-center shadow-lg shadow-[#9F5261]/20">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Semua Nilai
                </button>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    @if($kelas_id && $mapel_id)
    <form id="gradeForm" method="POST" action="{{ route('guru.grades.store') }}">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
        <input type="hidden" name="mapel_id" value="{{ $mapel_id }}">

        <div class="bg-slate-950/45 border border-slate-800/60 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-dark-custom">
                    <thead>
                        <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-4 text-center w-16">No</th>
                            <th class="py-4 px-6 w-1/4">Nama Siswa</th>
                            <th class="py-4 px-4 text-center w-36">Nilai Tugas (20%)</th>
                            <th class="py-4 px-4 text-center w-36">Nilai UH (20%)</th>
                            <th class="py-4 px-4 text-center w-36">Nilai UTS (30%)</th>
                            <th class="py-4 px-4 text-center w-36">Nilai UAS (30%)</th>
                            <th class="py-4 px-6 text-center w-40">Nilai Akhir</th>
                            <th class="py-4 px-6 text-center w-32">Status KKM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                        @forelse($all_students as $idx => $student)
                            @php
                                $grade = $all_grades->get($student->id);
                            @endphp
                            <tr class="hover:bg-slate-900/20 transition duration-150 student-row" data-index="{{ $idx }}">
                                <td class="py-4 px-4 text-center font-semibold text-slate-500">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-white text-sm">{{ $student->nama }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5 font-mono">NISN: {{ $student->nisn }}</p>
                                    <input type="hidden" name="grades[{{ $idx }}][siswa_id]" value="{{ $student->id }}">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" name="grades[{{ $idx }}][nilai_tugas]" step="any" min="0" max="100" 
                                           value="{{ $grade ? $grade->nilai_tugas : 0 }}" 
                                           class="w-24 px-3 py-2 bg-slate-900 border border-slate-800/80 rounded-xl text-center text-white focus:outline-none focus:border-blue-500 transition text-sm tugas-input">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" name="grades[{{ $idx }}][nilai_uh]" step="any" min="0" max="100" 
                                           value="{{ $grade ? $grade->nilai_uh : 0 }}" 
                                           class="w-24 px-3 py-2 bg-slate-900 border border-slate-800/80 rounded-xl text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uh-input">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" name="grades[{{ $idx }}][nilai_uts]" step="any" min="0" max="100" 
                                           value="{{ $grade ? $grade->nilai_uts : 0 }}" 
                                           class="w-24 px-3 py-2 bg-slate-900 border border-slate-800/80 rounded-xl text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uts-input">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" name="grades[{{ $idx }}][nilai_uas]" step="any" min="0" max="100" 
                                           value="{{ $grade ? $grade->nilai_uas : 0 }}" 
                                           class="w-24 px-3 py-2 bg-slate-900 border border-slate-800/80 rounded-xl text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uas-input">
                                </td>
                                <td class="py-4 px-6 text-center font-extrabold text-sm estimation-cell">
                                    0.00
                                </td>
                                <td class="py-4 px-6 text-center text-xs font-bold status-cell">
                                    -
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-500 italic">
                                    Tidak ada data siswa ditemukan di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    @else
    <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800/60 text-center space-y-3">
        <svg class="w-12 h-12 text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        <h4 class="text-white font-bold text-base">Silakan Pilih Filter Terlebih Dahulu</h4>
        <p class="text-xs text-slate-450 max-w-sm mx-auto">Pilih kelas dan mata pelajaran yang ingin diinput nilainya melalui menu dropdown filter di atas.</p>
    </div>
    @endif
</div>

@if($kelas_id && $mapel_id)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.student-row');

        function calculateRow(row) {
            const tugas = parseFloat(row.querySelector('.tugas-input').value) || 0;
            const uh = parseFloat(row.querySelector('.uh-input').value) || 0;
            const uts = parseFloat(row.querySelector('.uts-input').value) || 0;
            const uas = parseFloat(row.querySelector('.uas-input').value) || 0;

            // Weighting formula: 20% Tugas, 20% UH, 30% UTS, 30% UAS
            const finalScore = (tugas * 0.20) + (uh * 0.20) + (uts * 0.30) + (uas * 0.30);
            
            const estimationCell = row.querySelector('.estimation-cell');
            const statusCell = row.querySelector('.status-cell');

            estimationCell.textContent = finalScore.toFixed(2);

            if (finalScore >= 75) {
                estimationCell.style.color = '#3D8B6F';
                statusCell.innerHTML = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-bold kkm-lulus">Lulus</span>';
            } else {
                estimationCell.style.color = '#D6455D';
                statusCell.innerHTML = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-bold kkm-remedial">Remedial</span>';
            }
        }

        rows.forEach(row => {
            calculateRow(row);

            // Bind live inputs
            row.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', () => calculateRow(row));
            });
        });
    });
</script>
@endif
@endsection
