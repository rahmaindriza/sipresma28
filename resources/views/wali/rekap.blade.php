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
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                Kelas: {{ $kelas->nama_kelas }}
            </span>
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-green-900/40 text-green-300 border border-green-800">
                Tahun Ajaran Aktif: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
            </span>
        </div>
    </div>

    <!-- Remedial Notifications / Warnings -->
    <!-- Remedial Notifications / Warnings -->
    @if(count($remedialAlerts) > 0)
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 space-y-3 shadow-lg">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-red-400 shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h4 class="text-base font-bold text-white">Notifikasi Remedial (Siswa di Bawah KKM 75)</h4>
        </div>
        <p class="text-xs text-slate-350">Daftar siswa berikut memiliki nilai mata pelajaran di bawah standar KKM (75). Klik nama mata pelajaran untuk melihat daftar lengkap:</p>
        
        <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
            @foreach($remedialAlerts as $mapel => $alerts)
            <details class="group bg-red-950/40 border border-red-900/30 rounded-xl p-3">
                <summary class="flex justify-between items-center font-bold text-white cursor-pointer uppercase text-[10px] tracking-wider select-none">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        {{ $mapel }} ({{ count($alerts) }} Siswa)
                    </span>
                    <svg class="w-3.5 h-3.5 text-red-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                    @foreach($alerts as $alert)
                    <div class="p-2.5 bg-red-950/60 border border-red-900/40 rounded-xl flex items-center justify-between text-[11px]">
                        <span class="font-bold text-white">{{ $alert['siswa'] }}</span>
                        <span class="px-2.5 py-0.5 bg-red-600/20 border border-red-800 text-red-450 font-bold rounded-lg">{{ $alert['nilai'] }}</span>
                    </div>
                    @endforeach
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
                                    <button type="button" onclick="showHistoriSiswa({{ $siswa->id }})" title="Lihat Histori Rapor & Prestasi" class="p-1 hover:bg-slate-800 text-blue-400 rounded transition border-0 bg-transparent cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
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
                                    <span class="{{ $g->nilai_akhir < 75 ? 'text-red-400 font-bold bg-red-950/20 px-1.5 py-0.5 rounded border border-red-900/30' : 'text-slate-200' }}">
                                        {{ round($g->nilai_akhir, 0) }}
                                    </span>
                                @else
                                    <span class="text-slate-600 font-normal">-</span>
                                @endif
                            </td>
                            @endforeach
                            
                            <!-- Average -->
                            <td class="py-4 px-4 text-center font-extrabold text-blue-400 text-sm">
                                {{ $siswaRank['rata_rata'] }}
                            </td>
                            
                            <!-- Rank -->
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded font-extrabold text-xs
                                    {{ $siswaRank['rank'] <= 3 ? 'bg-amber-900/40 text-amber-400 border border-amber-800' : 'bg-slate-800 text-slate-300 border border-slate-700' }}">
                                    {{ $siswaRank['rank'] }}
                                </span>
                            </td>
                            
                            <!-- Laporan Button -->
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @php
                                        $siswaRapor = $raporSiswas->get($siswa->id);
                                    @endphp
                                    <button type="button" 
                                            onclick="openRaporModal({{ $siswa->id }}, '{{ addslashes($siswa->nama) }}', {{ json_encode($siswaRapor) }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600/10 hover:bg-emerald-600 text-emerald-400 hover:text-white border border-emerald-900 hover:border-emerald-500 font-bold rounded-lg transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Input Rapor
                                    </button>
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

<!-- Input Rapor Modal -->
<div id="rapor-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto text-slate-100">
        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
            <div>
                <h4 class="text-lg font-bold text-white">Input Rapor Siswa</h4>
                <p id="rapor-modal-student-name" class="text-xs text-slate-400 mt-0.5">Nama Siswa: ...</p>
            </div>
            <button onclick="toggleRaporModal()" class="text-slate-400 hover:text-white transition text-2xl border-0 bg-transparent cursor-pointer">&times;</button>
        </div>
        <form id="rapor-modal-form" action="" method="POST" class="space-y-4">
            @csrf
            
            <!-- Ketidakhadiran Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-3 uppercase tracking-wider">I. Ketidakhadiran (Hari)</h5>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Sakit</label>
                        <input type="number" name="sakit" id="rapor-sakit" min="0" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Izin</label>
                        <input type="number" name="izin" id="rapor-izin" min="0" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Tanpa Keterangan (Alpha)</label>
                        <input type="number" name="alpha" id="rapor-alpha" min="0" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                </div>
            </div>

            <hr class="border-slate-800">

            <!-- Ekstrakurikuler Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-3 uppercase tracking-wider">II. Kegiatan Ekstrakurikuler</h5>
                <div id="ekskul-container" class="space-y-3">
                    <!-- Dynamic Rows Rendered by Javascript -->
                </div>
                <button type="button" onclick="addEkskulRow('', '')" class="mt-3 inline-flex items-center px-3.5 py-2 bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-900/60 hover:border-blue-500 font-bold rounded-xl transition text-xs cursor-pointer">
                    + Tambah Ekstrakurikuler
                </button>
            </div>

            <hr class="border-slate-800">

            <!-- Catatan Wali Kelas Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-2 uppercase tracking-wider">III. Catatan Wali Kelas</h5>
                <label class="block text-xs text-slate-400 mb-2">Berikan narasi perkembangan dan motivasi belajar siswa pada semester ini.</label>
                <textarea name="catatan_walas" id="rapor-catatan" rows="3" required placeholder="Contoh: Ananda menunjukkan perkembangan dalam belajar. Diharapkan dapat mempertahankan..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm"></textarea>
            </div>

            <hr class="border-slate-800">

            @if($activeTa && (strtolower($activeTa->semester) == 'genap' || $activeTa->semester == '2' || strtolower($activeTa->semester) == 'semester 2'))
            <!-- Keterangan Kelulusan Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-2 uppercase tracking-wider">IV. Keterangan Kelulusan / Kenaikan Kelas</h5>
                <label class="block text-xs text-slate-400 mb-2">
                    @if(str_contains(strtolower($kelas->nama_kelas), '6') || str_contains(strtolower($kelas->nama_kelas), 'vi'))
                        Tuliskan keterangan kelulusan (Contoh: "Lulus / Tamat Belajar").
                    @else
                        Tuliskan keterangan kenaikan kelas (Contoh: "Naik ke Kelas ...", "Tinggal di Kelas ...").
                    @endif
                </label>
                @php
                    $placeholderText = 'Contoh: Lulus / Tamat Belajar';
                    if (!str_contains(strtolower($kelas->nama_kelas), '6') && !str_contains(strtolower($kelas->nama_kelas), 'vi')) {
                        $num = (int) filter_var($kelas->nama_kelas, FILTER_SANITIZE_NUMBER_INT);
                        if ($num > 0 && $num < 6) {
                            $romans = [1 => 'II (Dua)', 2 => 'III (Tiga)', 3 => 'IV (Empat)', 4 => 'V (Lima)', 5 => 'VI (Enam)'];
                            $nextRoman = $romans[$num] ?? ($num + 1);
                            $placeholderText = "Contoh: Naik ke Kelas " . $nextRoman;
                        } else {
                            $placeholderText = "Contoh: Naik ke Kelas II (Dua)";
                        }
                    }
                @endphp
                <input type="text" name="keterangan_kelulusan" id="rapor-kelulusan" placeholder="{{ $placeholderText }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            @endif

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="toggleRaporModal()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition border-0 cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition border-0 cursor-pointer shadow-lg shadow-emerald-900/30">Simpan Rapor</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRaporModal() {
        const modal = document.getElementById('rapor-modal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function openRaporModal(siswaId, studentName, currentRapor) {
        // Set student name header
        document.getElementById('rapor-modal-student-name').textContent = "Nama Siswa: " + studentName;
        
        // Set action url
        const form = document.getElementById('rapor-modal-form');
        form.action = "{{ route('wali.rapor.store', '__siswa_id__') }}".replace('__siswa_id__', siswaId);
        
        // Populate inputs or set to default
        document.getElementById('rapor-sakit').value = currentRapor ? currentRapor.sakit : 0;
        document.getElementById('rapor-izin').value = currentRapor ? currentRapor.izin : 0;
        document.getElementById('rapor-alpha').value = currentRapor ? currentRapor.alpha : 0;
        document.getElementById('rapor-catatan').value = currentRapor ? (currentRapor.catatan_walas || '') : '';
        const kelulusanEl = document.getElementById('rapor-kelulusan');
        if (kelulusanEl) {
            kelulusanEl.value = currentRapor ? (currentRapor.keterangan_kelulusan || '') : '';
        }
        
        // Populate dynamic extracurriculars
        const ekskulContainer = document.getElementById('ekskul-container');
        ekskulContainer.innerHTML = '';
        
        let ekskuls = [];
        if (currentRapor) {
            if (currentRapor.ekstrakurikuler) {
                try {
                    ekskuls = typeof currentRapor.ekstrakurikuler === 'string'
                        ? JSON.parse(currentRapor.ekstrakurikuler)
                        : currentRapor.ekstrakurikuler;
                } catch (e) {
                    ekskuls = [];
                }
            }
            
            // Fallback to old fields
            if ((!ekskuls || ekskuls.length === 0) && (currentRapor.ekskul_1_nama || currentRapor.ekskul_2_nama)) {
                if (currentRapor.ekskul_1_nama) {
                    ekskuls.push({ nama: currentRapor.ekskul_1_nama, ket: currentRapor.ekskul_1_ket || '' });
                }
                if (currentRapor.ekskul_2_nama) {
                    ekskuls.push({ nama: currentRapor.ekskul_2_nama, ket: currentRapor.ekskul_2_ket || '' });
                }
            }
        }
        
        if (ekskuls && ekskuls.length > 0) {
            ekskuls.forEach(item => {
                addEkskulRow(item.nama, item.ket);
            });
        } else {
            // Render 1 empty row for convenience
            addEkskulRow('', '');
        }
        
        // Show modal
        toggleRaporModal();
    }

    function addEkskulRow(nama = '', ket = '') {
        const container = document.getElementById('ekskul-container');
        const row = document.createElement('div');
        row.className = "grid grid-cols-1 md:grid-cols-12 gap-3 items-center border border-slate-800 p-3 rounded-xl bg-slate-950/40 relative";
        
        // Escape quotes
        const escapedNama = nama.replace(/"/g, '&quot;');
        const escapedKet = ket.replace(/"/g, '&quot;');

        row.innerHTML = `
            <div class="md:col-span-4">
                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Nama Ekstrakurikuler</label>
                <input type="text" name="ekskul_nama[]" value="${escapedNama}" placeholder="Contoh: Pramuka" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
            </div>
            <div class="md:col-span-7">
                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Keterangan / Deskripsi</label>
                <input type="text" name="ekskul_ket[]" value="${escapedKet}" placeholder="Contoh: Sangat baik dalam kepramukaan" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
            </div>
            <div class="md:col-span-1 flex justify-end md:justify-center pt-4 md:pt-0">
                <button type="button" onclick="this.closest('.grid').remove()" class="p-1.5 bg-red-650/10 hover:bg-red-600 border border-red-900/60 hover:border-red-500 text-red-400 hover:text-white rounded-lg transition cursor-pointer" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endsection
