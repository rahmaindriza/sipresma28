@extends('layouts.dashboard')

@section('title', 'Input Nilai Mapel Umum')

@section('content')
<div class="space-y-6">
    @if(isset($error))
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ $error }}</p>
    </div>
    @else
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-slate-900/40 p-6 border border-slate-800 rounded-3xl">
        <div>
            <h3 class="text-xl font-bold text-white">Input Nilai Mata Pelajaran Umum</h3>
            <p class="text-xs text-slate-400 mt-1">Pilih mata pelajaran untuk melakukan pengisian atau pembaruan komponen nilai akhir siswa.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                Kelas: {{ $kelas->nama_kelas }}
            </span>
        </div>
    </div>

    <!-- Info box -->
    <div class="p-4 rounded-3xl bg-blue-950/20 border border-blue-900/40 flex items-start space-x-3 text-xs text-slate-350 leading-relaxed shadow-sm">
        <div class="p-2 bg-blue-600/20 text-blue-400 rounded-lg shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <span class="font-bold text-blue-400">Panduan Penginputan:</span> Sebagai Wali Kelas, Anda memiliki otoritas penuh untuk menginputkan nilai komponen harian (Tugas 20%, UH 30%), UTS (20%), dan UAS (30%) pada mata pelajaran umum di kelas Anda. Batas KKM kelulusan adalah <span class="font-bold text-white">75</span>.
        </div>
    </div>

    <!-- Subject Grid -->
    <div class="glass-panel p-6 rounded-3xl shadow-xl">
        <h4 class="text-base font-bold text-white mb-6">Mata Pelajaran Umum</h4>
        
        @if(count($mapels) === 0)
        <p class="text-sm text-slate-500 italic py-6 text-center">Tidak ada data mata pelajaran umum yang ditemukan.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mapels as $m)
            <a href="{{ route('wali.grades', $m->id) }}" class="group block p-5 bg-slate-950/45 hover:bg-slate-900 border border-slate-800/80 hover:border-blue-600/50 rounded-2xl transition duration-300">
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Mapel Umum</p>
                <h5 class="text-white font-bold text-base mt-2 leading-tight group-hover:text-blue-400 transition">{{ $m->nama_mapel }}</h5>
                <p class="text-xs text-slate-500 mt-1 font-mono">Kode: {{ $m->kode_mapel }}</p>
                
                <div class="flex justify-between items-center mt-6 text-xs text-slate-500 pt-4 border-t border-slate-900/50">
                    <span>KKM: <span class="text-slate-350 font-bold">{{ $m->kkm }}</span></span>
                    <span class="text-blue-400 font-semibold flex items-center group-hover:translate-x-1 transition duration-200">
                        Input Nilai <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
