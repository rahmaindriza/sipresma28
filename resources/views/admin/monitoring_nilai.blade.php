@extends('layouts.main')

@section('title', 'Monitoring Nilai Siswa Global')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Monitoring Nilai Siswa Global</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Pantau seluruh nilai mata pelajaran siswa di semua kelas secara real-time.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                Semester: {{ $selectedTa ? $selectedTa->tahun . ' (' . $selectedTa->semester . ')' : '-' }}
            </span>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-2xl shadow-sm border border-[var(--border-light)] bg-white">
        <form action="{{ route('admin.nilai.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa atau NISN..." 
                    class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            
            <!-- Kelas Filter -->
            <div class="w-full md:w-48">
                <select name="kelas_id" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($listKelas as $kls)
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}> {{ $kls->nama_kelas }}</option>
                    @endforeach
                    <option value="alumni" {{ request('kelas_id') === 'alumni' ? 'selected' : '' }}>Alumni / Lulus</option>
                </select>
            </div>

            <!-- Tahun Ajaran Filter -->
            <div class="w-full md:w-48">
                <select name="tahun_ajaran_id" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTa->id == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none;">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.nilai.cetak_rekap', request()->all()) }}" target="_blank" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition text-center flex items-center justify-center gap-1.5" style="background-color: #3D5A80 !important; border: none;">
                    <i class="bi bi-printer"></i> Cetak PDF Rekapitulasi
                </a>
                <a href="{{ route('admin.nilai.index') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Search Results / History Selector -->
    @if(request()->filled('search') && count($students) > 0)
    <div class="glass-panel p-6 rounded-2xl shadow-sm border border-[var(--border-light)] bg-white space-y-4">
        <h4 class="text-xs font-bold text-[var(--primary-burgundy)] uppercase tracking-wider mb-0">Hasil Pencarian Riwayat Rapor Siswa</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($students as $siswa)
            <div class="p-4 border border-slate-100 rounded-xl bg-slate-50/50 flex flex-col justify-between gap-3 shadow-xs">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="font-bold text-slate-800 text-sm mb-0">{{ $siswa->nama }}</h5>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5 mb-0">NISN: {{ $siswa->nisn }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider
                        {{ strtolower($siswa->status) == 'alumni' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                        {{ $siswa->status ?? 'Aktif' }}
                    </span>
                </div>
                
                @if(isset($siswa->rapor_history) && count($siswa->rapor_history) > 0)
                <div class="flex flex-col sm:flex-row gap-2 items-end sm:items-center">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] text-slate-400 font-semibold mb-1">Pilih Riwayat Rapor Siswa:</label>
                        <select id="select-history-{{ $siswa->id }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-[var(--primary-burgundy)] transition">
                            @foreach($siswa->rapor_history as $hist)
                                <option value="{{ $hist->tahun_ajaran_id }}" data-kelas-id="{{ $hist->kelas_id }}"
                                    {{ (request('kelas_id') == $hist->kelas_id && request('tahun_ajaran_id') == $hist->tahun_ajaran_id) ? 'selected' : '' }}>
                                    Kelas {{ $hist->nama_kelas }} - Semester {{ strtolower($hist->semester) == 'ganjil' ? '1' : '2' }} ({{ $hist->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-1.5 w-full sm:w-auto pt-2 sm:pt-0">
                        <button type="button" onclick="lihatRaporHistory({{ $siswa->id }}, '{{ addslashes($siswa->nama) }}')" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex-1 sm:flex-none border-0 cursor-pointer">
                            Lihat
                        </button>
                        <button type="button" onclick="cetakRaporHistory({{ $siswa->id }})" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex-1 sm:flex-none border-0 cursor-pointer">
                            Cetak PDF
                        </button>
                    </div>
                </div>
                @else
                <p class="text-[11px] text-slate-400 italic mb-0">Belum ada riwayat nilai/rapor terdaftar untuk siswa ini.</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function lihatRaporHistory(siswaId, name) {
            const select = document.getElementById('select-history-' + siswaId);
            const taId = select.value;
            const option = select.options[select.selectedIndex];
            const kelasId = option.getAttribute('data-kelas-id');
            window.location.href = "{{ route('admin.nilai.index') }}?kelas_id=" + kelasId + "&tahun_ajaran_id=" + taId + "&search=" + encodeURIComponent(name);
        }

        function cetakRaporHistory(siswaId) {
            const select = document.getElementById('select-history-' + siswaId);
            const taId = select.value;
            const printUrl = "{{ route('admin.nilai.print_siswa', ['__siswa_id__']) }}".replace('__siswa_id__', siswaId) + "?tahun_ajaran_id=" + taId;
            window.open(printUrl, '_blank');
        }
    </script>
    @endif

    <!-- Nilai Matrix Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-sm bg-white border border-[var(--border-light)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr class="bg-[#EBF3FC] border-b border-[var(--border-light)] text-[10px] font-bold text-[#3D5A80] uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center" style="color: var(--primary-burgundy) !important;">No</th>
                        <th class="py-3.5 px-4 w-52" style="color: var(--primary-burgundy) !important;">Nama Siswa</th>
                        @foreach($mapels as $m)
                        <th class="py-3.5 px-3 text-center" style="color: var(--primary-burgundy) !important;" title="{{ $m->nama_mapel }}">{{ $m->kode_mapel }}</th>
                        @endforeach
                        <th class="py-3.5 px-4 text-center w-20" style="color: var(--primary-burgundy) !important;">Rerata</th>
                        <th class="py-3.5 px-3 text-center w-16" style="color: var(--primary-burgundy) !important;">Rank</th>
                        <th class="py-3.5 px-4 text-right w-36" style="color: var(--primary-burgundy) !important;">Laporan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)] text-xs text-slate-700">
                    @forelse($students as $siswa)
                        @php
                            $siswaGrades = $grades->get($siswa->id) ?? collect();
                            $siswaRank = $ranks[$siswa->id] ?? ['rank' => '-', 'rata_rata' => 0];
                        @endphp
                        <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-[var(--text-dark-main)] text-sm mb-0">{{ $siswa->nama }}</p>
                                    <button type="button" onclick="showHistoriSiswa({{ $siswa->id }})" title="Lihat Histori Rapor & Prestasi" class="p-1 hover:bg-slate-100 text-blue-600 rounded transition border-0 bg-transparent cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex gap-2 items-center text-[10px] text-slate-400 mt-0.5">
                                    <span class="font-mono">NISN: {{ $siswa->nisn }}</span>
                                    @php
                                        $displayKelasName = $siswa->kelas->nama_kelas ?? '-';
                                        if (request()->filled('kelas_id') && request('kelas_id') !== 'alumni') {
                                            $selectedKls = $listKelas->firstWhere('id', request('kelas_id'));
                                            if ($selectedKls) {
                                                $displayKelasName = $selectedKls->nama_kelas;
                                            }
                                        }
                                    @endphp
                                    <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200">Kelas {{ $displayKelasName }}</span>
                                </div>
                            </td>
                            
                            <!-- Mapel Grades -->
                            @foreach($mapels as $m)
                            @php
                                $g = $siswaGrades->where('mapel_id', $m->id)->first();
                            @endphp
                            <td class="py-3.5 px-3 text-center font-bold">
                                @if($g)
                                    <span class="{{ $g->nilai_akhir < 75 ? 'text-red-600 font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-100' : 'text-slate-800' }}">
                                        {{ round($g->nilai_akhir, 0) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            @endforeach
                            
                            <!-- Rerata -->
                            <td class="py-3.5 px-4 text-center font-extrabold text-blue-600 text-sm">
                                {{ $siswaRank['rata_rata'] }}
                            </td>
                            
                            <!-- Rank -->
                            <td class="py-3.5 px-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded font-extrabold text-xs
                                    {{ $siswaRank['rank'] <= 3 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-250' }}">
                                    {{ $siswaRank['rank'] }}
                                </span>
                            </td>
                            
                             <!-- Laporan Download Button -->
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.nilai.print_siswa', [$siswa->id, 'tahun_ajaran_id' => $selectedTa->id]) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 hover:border-blue-500 font-bold rounded-lg transition text-[10px]">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $mapels->count() + 5 }}" class="py-8 text-center text-slate-450 italic">
                                Belum ada data siswa terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
