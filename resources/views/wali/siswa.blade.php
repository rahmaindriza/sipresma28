@extends('layouts.dashboard')

@section('title', 'Data Siswa Kelas')

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
            <h3 class="text-xl font-bold text-white">Data Siswa Kelas ({{ $kelas->nama_kelas }})</h3>
            <p class="text-xs text-slate-400 mt-1">Daftar lengkap siswa aktif yang terdaftar di kelas Anda.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                TA: {{ $activeTa->tahun }}
            </span>
        </div>
    </div>

    <!-- Search Box -->
    <div class="glass-panel p-4 rounded-3xl shadow-lg">
        <form action="{{ route('wali.siswa') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NISN siswa..." 
                    class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="flex gap-2 shrink-0 flex-wrap">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">
                    Cari Siswa
                </button>
                <a href="{{ route('wali.siswa') }}" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
                <a href="{{ route('wali.siswa.cetak', ['search' => request('search')]) }}" target="_blank" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition text-center flex items-center justify-center shadow-lg shadow-emerald-600/20">
                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Siswa
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6">NISN</th>
                        <th class="py-4 px-6">Nama Lengkap</th>
                        <th class="py-4 px-6 text-center">Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @if(count($students) === 0)
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-500 italic">
                            Tidak ada siswa yang ditemukan.
                        </td>
                    </tr>
                    @else
                        @foreach($students as $siswa)
                        <tr class="hover:bg-slate-900/20 transition duration-150">
                            <td class="py-4 px-6 text-center font-semibold text-slate-450">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-blue-400">{{ $siswa->nisn }}</td>
                            <td class="py-4 px-6 font-medium text-white">{{ $siswa->nama }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium border
                                    {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-900/20 text-blue-400 border-blue-800' : 'bg-pink-900/20 text-pink-400 border-pink-850' }}">
                                    {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
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
