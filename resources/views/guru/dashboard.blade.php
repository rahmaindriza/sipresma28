@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">
    <div>
        <h3 class="text-xl font-bold text-white">Panel Guru Mata Pelajaran</h3>
        <p class="text-xs text-slate-400 mt-1">Pilih kelas dan mata pelajaran yang Anda ampu untuk mengelola komponen nilai siswa.</p>
    </div>

    @if(isset($error))
        <div class="p-4 rounded-xl bg-red-950/60 border border-red-900 text-red-300 text-sm">
            {{ $error }}
        </div>
    @elseif($assignments->isEmpty())
        <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 text-center space-y-3">
            <svg class="w-12 h-12 text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="text-white font-bold text-base">Belum Ada Penugasan Mengajar</h4>
            <p class="text-sm text-slate-450 max-w-sm mx-auto">Anda belum terdaftar untuk mengajar kelas mana pun pada tahun ajaran aktif ini. Silakan hubungi Administrator sekolah.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($assignments as $assign)
            <div class="glass-panel p-6 rounded-2xl flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <span class="inline-flex px-2 py-0.5 rounded bg-blue-950 text-blue-400 border border-blue-900 text-[10px] font-bold uppercase tracking-wider">
                        {{ $assign->mapel->jenis_mapel }}
                    </span>
                    <h4 class="text-lg font-bold text-white leading-tight">{{ $assign->mapel->nama_mapel }}</h4>
                    <p class="text-sm text-slate-400">Kelas: <span class="text-white font-semibold">{{ $assign->kelas->nama_kelas }}</span></p>
                    <p class="text-xs text-slate-500">KKM Mapel: <span class="font-bold text-slate-300">{{ $assign->mapel->kkm }}</span></p>
                </div>
                <div>
                    <a href="{{ route('guru.grades', $assign->id) }}" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-center text-xs transition">
                        Input / Edit Nilai
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
