@extends('layouts.main')

@section('title', 'Kelola Prestasi Siswa')

@section('content')
<div class="space-y-6">
    @if(isset($error))
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ $error }}</p>
    </div>
    @else
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Kelola Prestasi Siswa</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                Daftar pencapaian prestasi akademik & non-akademik siswa di {{ $kelas->nama_kelas }}.
            </p>
        </div>
        <a href="{{ route('wali.prestasi.create') }}" class="mt-4 sm:mt-0 px-4 py-2 font-semibold rounded-xl text-xs transition flex items-center shadow-sm" style="background-color: var(--primary-burgundy) !important; color: white !important; border: none; box-shadow: 0 4px 10px rgba(61, 90, 128, 0.25);">
            <i class="bi bi-plus-lg me-1.5"></i>
            Tambah Prestasi
        </a>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-2xl shadow-sm border border-[var(--border-light)]">
        <form action="{{ route('wali.prestasi') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lomba atau nama siswa..."
                    class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="w-full md:w-56">
                <select name="kategori" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Akademik" {{ request('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Non-Akademik" {{ request('kategori') === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
            </div>
            <!-- Tahun Ajaran Filter (Locked to Active Year) -->
            <div class="w-full md:w-56">
                <select name="tahun_ajaran_id_disabled" disabled class="w-full px-4 py-2 bg-slate-100 border border-[var(--border-light)] rounded-xl text-slate-500 cursor-not-allowed text-sm">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTa->id == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }}) [AKTIF]
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTa->id }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none;">
                    Cari & Filter
                </button>
                <a href="{{ route('wali.prestasi') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Prestasis Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">No</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Kelas</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Nama Lomba</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Kategori</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Tingkat</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Juara</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Bukti</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #EBF3FC;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)]">
                    @forelse($prestasis as $index => $p)
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-500">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">{{ $p->siswa->nama }}</td>
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
                        <td class="py-3.5 px-4 text-center">
                            @if($p->sertifikat)
                                <a href="{{ route('prestasi.download', $p->id) }}" class="btn btn-sm px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 rounded-lg text-[10px] font-bold transition inline-flex items-center">
                                    <i class="bi bi-download me-1"></i> Unduh
                                </a>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('wali.prestasi.show', $p->id) }}" class="text-cyan-600 hover:text-cyan-800 text-[11px] font-bold px-2.5 py-1 hover:bg-cyan-50 border border-transparent hover:border-cyan-200 rounded-lg transition inline-flex items-center">
                                    <i class="bi bi-eye mr-1"></i> Detail
                                </a>

                                <a href="{{ route('wali.prestasi.edit', $p->id) }}" class="text-blue-600 hover:text-blue-800 text-[11px] font-bold px-2.5 py-1 hover:bg-blue-50 border border-transparent hover:border-blue-200 rounded-lg transition inline-flex items-center">
                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                </a>

                                <form action="{{ route('wali.prestasi.destroy', $p->id) }}" method="POST" class="inline-block m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini?')" class="text-red-600 hover:text-red-800 text-[11px] font-bold px-2.5 py-1 hover:bg-red-50 border border-transparent hover:border-red-200 rounded-lg transition inline-flex items-center">
                                        <i class="bi bi-trash mr-1"></i> Hapus
                                    </button>
                                </form>

                                <a href="{{ route('wali.prestasi.cetak', $p->siswa_id) }}" target="_blank" class="text-[11px] font-bold px-2.5 py-1 rounded-lg transition inline-flex items-center whitespace-nowrap" style="color: #15803d !important; background-color: #dcfce7 !important; border: 1px solid #bbf7d0 !important;">
                                    <i class="bi bi-file-pdf mr-1"></i> cetak aPDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            <div class="fs-1 text-slate-350 mb-3"><i class="bi bi-trophy"></i></div>
                            Belum ada data prestasi terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
</div>
@endsection
