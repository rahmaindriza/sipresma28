@extends('layouts.dashboard')

@section('title', 'Kelola Prestasi Siswa')

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
            <h3 class="text-xl font-bold text-white">Kelola Prestasi Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Daftar capaian prestasi akademik maupun non-akademik siswa di kelas {{ $kelas->nama_kelas }}.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                Total: {{ count($achievements) }} Prestasi
            </span>
            <a href="{{ route('wali.prestasi.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition shadow-lg shadow-blue-600/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Prestasi
            </a>
        </div>
    </div>

    <!-- Main Content: Table List -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl w-full">
        <div class="p-6 border-b border-slate-800 bg-slate-900/40">
            <h4 class="text-base font-bold text-white">Daftar Prestasi Siswa Kelas</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-4 w-12 text-center">No</th>
                        <th class="py-4 px-4">Nama Siswa</th>
                        <th class="py-4 px-4 w-28 text-center">Jenis</th>
                        <th class="py-4 px-4 w-28 text-center">Tanggal</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-4 text-center w-16">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs text-slate-350">
                    @if(count($achievements) === 0)
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">
                            Belum ada data prestasi yang diinputkan.
                        </td>
                    </tr>
                    @else
                        @foreach($achievements as $ach)
                        <tr class="hover:bg-slate-900/20 transition duration-150">
                            <td class="py-4 px-4 text-center font-semibold text-slate-450">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-white">{{ $ach->siswa->nama }}</span>
                                <p class="text-[9px] text-slate-500 font-mono mt-0.5">NISN: {{ $ach->siswa->nisn }}</p>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold border uppercase tracking-wider
                                    {{ $ach->jenis_prestasi === 'Akademik' ? 'bg-amber-900/30 text-amber-400 border-amber-800/40' : 'bg-purple-900/30 text-purple-405 border-purple-800/40' }}">
                                    {{ $ach->jenis_prestasi }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center font-mono text-slate-400">
                                {{ \Carbon\Carbon::parse($ach->tanggal)->translatedFormat('d-m-Y') }}
                            </td>
                            <td class="py-4 px-6 leading-relaxed max-w-xs truncate" title="{{ $ach->keterangan }}">
                                {{ $ach->keterangan }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('wali.prestasi.destroy', $ach->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus data prestasi ini?')" class="p-1.5 hover:bg-slate-800 text-red-400 hover:text-red-300 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
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
@endsection
