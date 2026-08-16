@extends('layouts.dashboard')

@section('title', 'Detail Prestasi Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Detail Prestasi Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Lihat informasi detail dan bukti sertifikat / piagam penghargaan.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <a href="{{ route('prestasis.edit', $prestasi->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition shadow-lg shadow-blue-600/20">
                <i class="bi bi-pencil-square"></i>
                Edit Data
            </a>
            <a href="{{ route('prestasis') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-xs transition border border-slate-700">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Siswa & Prestasi -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-panel p-6 rounded-3xl shadow-xl border border-slate-800/40 bg-slate-900/50 space-y-5">
                <h4 class="text-sm font-bold text-white border-b border-slate-800 pb-3">Informasi Siswa</h4>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Nama Lengkap Siswa</span>
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                                <i class="bi bi-person"></i>
                            </div>
                            {{ $prestasi->siswa->nama }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">NISN / NIS</span>
                        <span class="font-medium text-slate-300 text-sm">{{ $prestasi->siswa->nisn }} / {{ $prestasi->siswa->nis }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Kelas</span>
                        <span class="font-medium text-slate-300 text-sm">{{ $prestasi->siswa->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="glass-panel p-6 rounded-3xl shadow-xl border border-slate-800/40 bg-slate-900/50 space-y-5">
                <h4 class="text-sm font-bold text-white border-b border-slate-800 pb-3">Detail Pencapaian</h4>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Nama Lomba / Pencapaian</span>
                        <span class="font-bold text-white text-sm">{{ $prestasi->nama_lomba }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Kategori & Jenis</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $prestasi->kategori === 'Akademik' ? 'bg-amber-900/50 text-amber-400 border border-amber-500/20' : 'bg-blue-900/50 text-blue-400 border border-blue-500/20' }}">
                                {{ $prestasi->kategori }}
                            </span>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">
                                {{ $prestasi->jenis_pelaksanaan }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tingkat Kejuaraan & Hasil</span>
                        <span class="font-medium text-slate-300 text-sm">{{ $prestasi->tingkat }} - <strong class="text-blue-400">{{ $prestasi->juara }}</strong></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tanggal Perolehan</span>
                        <span class="font-medium text-slate-300 text-sm"><i class="bi bi-calendar-event me-1 text-slate-400"></i> {{ \Carbon\Carbon::parse($prestasi->tanggal_penghargaan)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preview Sertifikat -->
        <div class="lg:col-span-2">
            <div class="glass-panel p-6 rounded-3xl shadow-xl border border-slate-800/40 bg-slate-900/50 h-full flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <h4 class="text-sm font-bold text-white">Bukti Sertifikat / Piagam</h4>
                    @if($prestasi->sertifikat)
                    <a href="{{ route('prestasi.download', $prestasi->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg border border-slate-700 transition">
                        <i class="bi bi-download me-1.5"></i> Unduh File
                    </a>
                    @endif
                </div>
                
                <div class="flex-1 flex flex-col items-center justify-center bg-slate-950/50 rounded-2xl border border-dashed border-slate-700 p-2 min-h-[400px]">
                    @if($prestasi->sertifikat)
                        @php
                            $ext = strtolower(pathinfo($prestasi->sertifikat, PATHINFO_EXTENSION));
                            $url = asset('uploads/sertifikat/' . $prestasi->sertifikat);
                        @endphp
                        
                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ $url }}" alt="Sertifikat {{ $prestasi->nama_lomba }}" class="max-w-full max-h-[600px] object-contain rounded-xl shadow-lg">
                        @elseif($ext === 'pdf')
                            <iframe src="{{ $url }}" class="w-full h-full min-h-[500px] border-0 rounded-xl bg-white"></iframe>
                        @else
                            <div class="text-center text-slate-500">
                                <i class="bi bi-file-earmark-check text-5xl text-emerald-400 mb-3 block"></i>
                                <p class="text-sm font-medium text-slate-300">File Tersimpan</p>
                                <p class="text-xs text-slate-400 mt-1">Format file tidak mendukung pratinjau langsung.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-slate-500">
                            <i class="bi bi-file-earmark-x text-5xl text-slate-700 mb-3 block"></i>
                            <p class="text-sm font-medium">Belum ada file terlampir</p>
                            <p class="text-xs mt-1 text-slate-600">Sertifikat atau piagam belum diunggah untuk pencapaian ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
