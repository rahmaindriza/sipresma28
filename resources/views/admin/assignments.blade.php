@extends('layouts.dashboard')

@section('title', 'Penugasan Guru')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Penugasan Mengajar Guru</h3>
            <p class="text-xs text-slate-400 mt-1">Petakan guru mengajar mata pelajaran tertentu di kelas mana untuk semester aktif.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Penugasan
        </button>
    </div>

    <!-- Assignments Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Guru Pengajar</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6">Tahun Ajaran</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @foreach($assignments as $a)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-white">{{ $a->guru->nama }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2 py-0.5 rounded bg-slate-950 text-slate-300 border border-slate-800 text-xs">
                                {{ $a->kelas->nama_kelas }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-blue-400 font-medium">{{ $a->mapel->nama_mapel }}</td>
                        <td class="py-4 px-6 text-slate-400 text-xs">{{ $a->tahunAjaran->tahun }} ({{ $a->tahunAjaran->semester }})</td>
                        <td class="py-4 px-6 text-right">
                            <form action="{{ route('admin.assignments.destroy', $a->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-slate-900 border border-slate-850 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Tambah Penugasan Guru</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form action="{{ route('admin.assignments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Guru</label>
                <select name="guru_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->nama }} (NIP: {{ $guru->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="kelas_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Mata Pelajaran</label>
                <select name="mapel_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }} [{{ $mapel->jenis_mapel }}]</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Tahun Ajaran</label>
                <select name="tahun_ajaran_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->status === 'aktif' ? 'selected' : '' }}>
                        {{ $ta->tahun }} ({{ $ta->semester }}) {{$ta->status === 'aktif' ? '[AKTIF]' : ''}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="toggleModal('add-modal')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }
</script>
@endsection
