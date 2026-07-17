@extends('layouts.main')

@section('title', 'Monitoring Prestasi Siswa Global')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Monitoring Prestasi Siswa Global</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Pantau prestasi seluruh siswa lintas kelas secara real-time.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.prestasi.cetak_rekap', request()->all()) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-sm border-0">
                <i class="bi bi-file-pdf me-1.5"></i> Cetak Rekapitulasi PDF
            </a>
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-55 text-emerald-800 border border-emerald-250">
                Semester Aktif: {{ $activeTa ? $activeTa->tahun . ' (' . $activeTa->semester . ')' : '-' }}
            </span>
        </div>
    </div>

    <!-- Search Box and Filters -->
    <div class="glass-panel p-4 rounded-2xl shadow-sm border border-[var(--border-light)]">
        <form action="{{ route('admin.prestasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lomba atau nama siswa..." 
                    class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            
            <!-- Kelas Filter -->
            <div class="w-full md:w-48">
                <select name="kelas_id" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($listKelas as $kls)
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>Kelas {{ $kls->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori Filter -->
            <div class="w-full md:w-48">
                <select name="kategori" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Akademik" {{ request('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Non-Akademik" {{ request('kategori') === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none;">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.prestasi.index') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Prestasi Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 60px;">No</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 100px;">Kelas</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Lomba</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Kategori</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Tingkat</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 100px;">Juara</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 80px;">Poin</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)]">
                    @forelse($prestasis as $index => $p)
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-500">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 text-xs">
                            <span class="font-bold text-[var(--text-dark-main)]">{{ $p->siswa->nama }}</span>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">NISN: {{ $p->siswa->nisn }}</p>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-500 font-semibold">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-xs text-[var(--text-dark-main)]">{{ $p->nama_lomba }}</td>
                        <td class="py-3.5 px-4 text-xs">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $p->kategori === 'Akademik' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                {{ $p->kategori }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">{{ $p->tingkat }}</td>
                        <td class="py-3.5 px-4 text-xs text-slate-600 font-medium">{{ $p->juara }}</td>
                        <td class="py-3.5 px-4 text-xs font-bold text-center">
                            <span class="px-2 py-0.5 rounded bg-slate-100 border text-slate-700">
                                {{ $p->poin }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-center font-mono text-slate-500">
                            {{ \Carbon\Carbon::parse($p->tanggal_penghargaan)->translatedFormat('d-m-Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            <div class="fs-1 text-slate-350 mb-3"><i class="bi bi-trophy"></i></div>
                            Belum ada rekaman prestasi siswa terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
