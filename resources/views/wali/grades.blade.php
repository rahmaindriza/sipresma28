@extends('layouts.dashboard')

@section('title', 'Input Nilai Mapel Umum')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('wali.nilai') }}" class="p-2 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-white">Input Nilai Mapel Umum (Wali Kelas)</h3>
            <p class="text-xs text-slate-400 mt-1">Kelas: <span class="text-white font-semibold">{{ $kelas->nama_kelas }}</span> | Mapel Umum: <span class="text-white font-semibold">{{ $mapel->nama_mapel }}</span></p>
        </div>
    </div>

    <!-- Info card -->
    <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-900/45 flex items-start space-x-3">
        <div class="p-2 bg-indigo-600/20 text-indigo-400 rounded-lg shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="text-xs text-slate-350 leading-relaxed">
            <span class="font-bold text-indigo-400">Pengisian Nilai Mapel Umum:</span> Sebagai wali kelas, Anda berwenang mengisi nilai mata pelajaran umum untuk siswa Anda. Bobot: Tugas: 20%, UH: 30%, UTS: 20%, UAS: 30%. KKM: <span class="font-bold text-white">{{ $mapel->kkm }}</span>.
        </div>
    </div>

    <!-- Input Form -->
    <form action="{{ route('wali.grades.store', $mapel->id) }}" method="POST" class="space-y-6">
        @csrf
        <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 w-1/4">Nama Siswa</th>
                            <th class="py-4 px-4 text-center">Tugas (20%)</th>
                            <th class="py-4 px-4 text-center">UH (30%)</th>
                            <th class="py-4 px-4 text-center">UTS (20%)</th>
                            <th class="py-4 px-4 text-center">UAS (30%)</th>
                            <th class="py-4 px-6 text-center w-40">Estimasi Akhir</th>
                            <th class="py-4 px-6 text-center w-28">Remedial?</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                        @foreach($students as $index => $student)
                        @php
                            $grade = $grades->get($student->id);
                        @php
                        <tr class="hover:bg-slate-900/20 transition duration-150 student-row" data-index="{{ $index }}">
                            <td class="py-4 px-6">
                                <p class="font-semibold text-white">{{ $student->nama }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">NISN: {{ $student->nisn }}</p>
                                <input type="hidden" name="grades[{{ $index }}][siswa_id]" value="{{ $student->id }}">
                            </td>
                            <td class="py-4 px-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" 
                                       name="grades[{{ $index }}][tugas]" 
                                       value="{{ $grade ? $grade->tugas : 0 }}" 
                                       class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 transition text-sm tugas-input">
                            </td>
                            <td class="py-4 px-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" 
                                       name="grades[{{ $index }}][uh]" 
                                       value="{{ $grade ? $grade->uh : 0 }}" 
                                       class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uh-input">
                            </td>
                            <td class="py-4 px-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" 
                                       name="grades[{{ $index }}][uts]" 
                                       value="{{ $grade ? $grade->uts : 0 }}" 
                                       class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uts-input">
                            </td>
                            <td class="py-4 px-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" 
                                       name="grades[{{ $index }}][uas]" 
                                       value="{{ $grade ? $grade->uas : 0 }}" 
                                       class="w-20 px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-center text-white focus:outline-none focus:border-blue-500 transition text-sm uas-input">
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-base text-slate-400 estimation-cell">
                                0.00
                            </td>
                            <td class="py-4 px-6 text-center remedial-cell font-semibold text-xs">
                                -
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('wali.nilai') }}" class="px-5 py-3 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</a>
            <button type="submit" style="background-color: var(--lulus-sekolah); box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);" class="px-5 py-3 text-white font-semibold rounded-xl text-xs transition hover:opacity-90">
                Simpan & Rekap Nilai
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kkm = {{ $mapel->kkm }};
        const rows = document.querySelectorAll('.student-row');

        function calculateRow(row) {
            const tugas = parseFloat(row.querySelector('.tugas-input').value) || 0;
            const uh = parseFloat(row.querySelector('.uh-input').value) || 0;
            const uts = parseFloat(row.querySelector('.uts-input').value) || 0;
            const uas = parseFloat(row.querySelector('.uas-input').value) || 0;

            const finalEstimation = (tugas * 0.20) + (uh * 0.30) + (uts * 0.20) + (uas * 0.30);
            
            const estimationCell = row.querySelector('.estimation-cell');
            const remedialCell = row.querySelector('.remedial-cell');

            estimationCell.textContent = finalEstimation.toFixed(2);

            if (finalEstimation < kkm) {
                estimationCell.style.color = "var(--remedial-sekolah)";
                remedialCell.textContent = "Ya";
                remedialCell.style.color = "var(--remedial-sekolah)";
                remedialCell.style.backgroundColor = "rgba(220, 38, 38, 0.15)";
                remedialCell.style.borderColor = "var(--remedial-sekolah)";
                remedialCell.className = "py-4 px-6 text-center remedial-cell font-bold text-xs border rounded-lg inline-block py-0.5 px-2";
            } else {
                estimationCell.style.color = "var(--lulus-sekolah)";
                remedialCell.textContent = "Tidak";
                remedialCell.style.color = "var(--lulus-sekolah)";
                remedialCell.style.backgroundColor = "rgba(5, 150, 105, 0.15)";
                remedialCell.style.borderColor = "var(--lulus-sekolah)";
                remedialCell.className = "py-4 px-6 text-center remedial-cell font-bold text-xs border rounded-lg inline-block py-0.5 px-2";
            }
        }

        rows.forEach(row => {
            calculateRow(row);

            row.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', () => calculateRow(row));
            });
        });
    });
</script>
@endsection
