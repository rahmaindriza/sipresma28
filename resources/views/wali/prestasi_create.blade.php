@extends('layouts.dashboard')

@section('title', 'Tambah Prestasi Siswa')

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
            <h3 class="text-xl font-bold text-white">Tambah Prestasi Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Input capaian prestasi akademik maupun non-akademik siswa di kelas {{ $kelas->nama_kelas }}.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('wali.prestasi') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-xs transition border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-2xl mx-auto glass-panel p-8 rounded-3xl shadow-xl space-y-6">
        <h4 class="text-base font-bold text-white border-b border-slate-800 pb-3">Input Prestasi Baru</h4>
        
        <form action="{{ route('wali.prestasi.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Jenis Prestasi</label>
                <select name="jenis_prestasi" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="Akademik">Akademik</option>
                    <option value="Non-Akademik">Non-Akademik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Tanggal Penghargaan</label>
                <input type="date" name="tanggal" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-355 uppercase tracking-wider mb-2">Keterangan Prestasi</label>
                <textarea name="keterangan" required rows="4" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: Juara 1 OSN Matematika Tingkat Kabupaten 2026."></textarea>
            </div>
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('wali.prestasi') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition border border-slate-700">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition shadow-lg shadow-blue-600/10">
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
