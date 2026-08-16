@extends('layouts.dashboard')

@section('title', 'Input Rapor Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-slate-900/40 p-6 border border-slate-800 rounded-3xl">
        <div>
            <h3 class="text-xl font-bold text-white">Input Rapor Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Nama Siswa: <span class="font-bold text-blue-400">{{ $siswa->nama }}</span> | NISN: {{ $siswa->nisn }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <a href="{{ route('wali.rekap') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-xs transition" style="color: white !important;">
                Kembali ke Rekap
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full p-8 shadow-2xl space-y-6 text-slate-100">
        <form action="{{ route('wali.rapor.store', $siswa->id) }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Ketidakhadiran Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-3 uppercase tracking-wider">I. Ketidakhadiran (Hari)</h5>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Sakit</label>
                        <input type="number" name="sakit" min="0" required value="{{ $rapor ? $rapor->sakit : 0 }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Izin</label>
                        <input type="number" name="izin" min="0" required value="{{ $rapor ? $rapor->izin : 0 }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Tanpa Keterangan (Alpha)</label>
                        <input type="number" name="alpha" min="0" required value="{{ $rapor ? $rapor->alpha : 0 }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    </div>
                </div>
            </div>

            <hr class="border-slate-800">

            <!-- Ekstrakurikuler Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-3 uppercase tracking-wider">II. Kegiatan Ekstrakurikuler</h5>
                <div id="ekskul-container" class="space-y-3">
                    @php
                        $ekskuls = [];
                        if ($rapor) {
                            if ($rapor->ekstrakurikuler) {
                                $ekskuls = is_string($rapor->ekstrakurikuler) ? json_decode($rapor->ekstrakurikuler, true) : $rapor->ekstrakurikuler;
                            }
                            if (empty($ekskuls) && ($rapor->ekskul_1_nama || $rapor->ekskul_2_nama)) {
                                if ($rapor->ekskul_1_nama) {
                                    $ekskuls[] = ['nama' => $rapor->ekskul_1_nama, 'ket' => $rapor->ekskul_1_ket];
                                }
                                if ($rapor->ekskul_2_nama) {
                                    $ekskuls[] = ['nama' => $rapor->ekskul_2_nama, 'ket' => $rapor->ekskul_2_ket];
                                }
                            }
                        }
                    @endphp

                    @if(!empty($ekskuls))
                        @foreach($ekskuls as $eks)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center border border-slate-800 p-3 rounded-xl bg-slate-950/40 relative ekskul-row">
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Nama Ekstrakurikuler</label>
                                <input type="text" name="ekskul_nama[]" value="{{ htmlspecialchars($eks['nama']) }}" placeholder="Contoh: Pramuka" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
                            </div>
                            <div class="md:col-span-7">
                                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Keterangan / Deskripsi</label>
                                <input type="text" name="ekskul_ket[]" value="{{ htmlspecialchars($eks['ket']) }}" placeholder="Contoh: Sangat baik dalam kepramukaan" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
                            </div>
                            <div class="md:col-span-1 flex justify-end md:justify-center pt-4 md:pt-0">
                                <button type="button" onclick="this.closest('.ekskul-row').remove()" class="p-1.5 bg-red-650/10 hover:bg-red-600 border border-red-900/60 hover:border-red-500 text-red-400 hover:text-white rounded-lg transition cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center border border-slate-800 p-3 rounded-xl bg-slate-950/40 relative ekskul-row">
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Nama Ekstrakurikuler</label>
                                <input type="text" name="ekskul_nama[]" value="" placeholder="Contoh: Pramuka" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
                            </div>
                            <div class="md:col-span-7">
                                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Keterangan / Deskripsi</label>
                                <input type="text" name="ekskul_ket[]" value="" placeholder="Contoh: Sangat baik dalam kepramukaan" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-xs">
                            </div>
                            <div class="md:col-span-1 flex justify-end md:justify-center pt-4 md:pt-0">
                                <button type="button" onclick="this.closest('.ekskul-row').remove()" class="p-1.5 bg-red-650/10 hover:bg-red-600 border border-red-900/60 hover:border-red-500 text-red-400 hover:text-white rounded-lg transition cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addEkskulRow('', '')" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-900/60 hover:border-blue-500 font-bold rounded-xl transition text-xs cursor-pointer">
                    + Tambah Ekstrakurikuler
                </button>
            </div>

            <hr class="border-slate-800">

            <!-- Catatan Wali Kelas Section -->
            <div>
                <h5 class="text-sm font-bold text-blue-400 mb-2 uppercase tracking-wider">III. Catatan Wali Kelas</h5>
                <label class="block text-xs text-slate-400 mb-2">Berikan narasi perkembangan dan motivasi belajar siswa pada semester ini.</label>
                <textarea name="catatan_walas" rows="4" required placeholder="Contoh: Ananda menunjukkan perkembangan dalam belajar. Diharapkan dapat mempertahankan..." class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">{{ $rapor ? $rapor->catatan_walas : '' }}</textarea>
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
                <input type="text" name="keterangan_kelulusan" value="{{ $rapor ? $rapor->keterangan_kelulusan : '' }}" placeholder="{{ $placeholderText }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            @endif

            <div class="flex justify-end pt-4 space-x-4">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-blue-900/30">
                    Simpan Rapor Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addEkskulRow(nama = '', ket = '') {
        const container = document.getElementById('ekskul-container');
        const row = document.createElement('div');
        row.className = "grid grid-cols-1 md:grid-cols-12 gap-3 items-center border border-slate-800 p-3 rounded-xl bg-slate-950/40 relative ekskul-row";
        
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
                <button type="button" onclick="this.closest('.ekskul-row').remove()" class="p-1.5 bg-red-650/10 hover:bg-red-600 border border-red-900/60 hover:border-red-500 text-red-400 hover:text-white rounded-lg transition cursor-pointer" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endsection
