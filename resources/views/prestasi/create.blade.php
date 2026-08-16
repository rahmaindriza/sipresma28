@extends('layouts.dashboard')

@section('title', 'Tambah Prestasi Siswa')

@section('content')
<div class="space-y-6">
    @if(session('error'))
    <div class="p-6 rounded-3xl bg-red-900/30 border border-red-800 text-red-300 shadow-xl">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ session('error') }}</p>
    </div>
    @endif
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Tambah Prestasi Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Input pencapaian lomba atau penghargaan baru untuk siswa.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('prestasis') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-xs transition border border-slate-700">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-3xl mx-auto glass-panel p-8 rounded-3xl shadow-xl border border-slate-800/40 bg-slate-900/50 space-y-6">
        <h4 class="text-base font-bold text-white border-b border-slate-800 pb-3">Form Input Prestasi Baru</h4>
        
        <form action="{{ route('prestasis.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} (Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Lomba / Pencapaian</label>
                <input type="text" name="nama_lomba" required placeholder="Contoh: Lomba Cerdas Cermat Matematika" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="Akademik">Akademik</option>
                        <option value="Non-Akademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Jenis Pelaksanaan</label>
                    <select name="jenis_pelaksanaan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="Dalam Sekolah">Dalam Sekolah</option>
                        <option value="Luar Sekolah">Luar Sekolah</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Tingkat</label>
                    <select name="tingkat" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="Kecamatan">Kecamatan</option>
                        <option value="Kabupaten">Kabupaten</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Juara / Hasil</label>
                    <select name="juara" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Harapan">Harapan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Tanggal Penghargaan</label>
                <input type="date" name="tanggal_penghargaan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Unggah Sertifikat / Bukti Fisik (.jpg, .png, .pdf)</label>
                <input type="file" name="sertifikat" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('prestasis') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition border border-slate-700">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition shadow-lg shadow-blue-600/20">
                    <i class="bi bi-save mr-1.5"></i> Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
