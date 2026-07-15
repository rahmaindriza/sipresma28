@extends('layouts.dashboard')

@section('title', 'Kelola Mapel')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Mata Pelajaran</h3>
            <p class="text-xs text-slate-400 mt-1">Daftar mata pelajaran sekolah beserta jenis mapel (umum/khusus) dan KKM standar.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Mapel
        </button>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-lg border border-slate-800/40">
        <form action="{{ route('admin.mapels') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode mapel..." 
                    class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="w-full md:w-56">
                <select name="jenis_mapel" class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Semua Jenis Mapel --</option>
                    <option value="umum" {{ request('jenis_mapel') === 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="khusus" {{ request('jenis_mapel') === 'khusus' ? 'selected' : '' }}>Khusus</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.mapels') }}" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Mapels Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Kode Mapel</th>
                        <th class="py-4 px-6">Nama Mata Pelajaran</th>
                        <th class="py-4 px-6">Jenis Mapel</th>
                        <th class="py-4 px-6 text-center">KKM</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @foreach($mapels as $m)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-blue-400 uppercase">{{ $m->kode_mapel }}</td>
                        <td class="py-4 px-6 text-white font-medium">{{ $m->nama_mapel }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider
                                {{ $m->jenis_mapel === 'umum' ? 'bg-indigo-950 text-indigo-400 border border-indigo-900' : 'bg-rose-950 text-rose-400 border border-rose-900' }}">
                                {{ $m->jenis_mapel }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-white">{{ $m->kkm }}</td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button onclick="editMapel({{ json_encode($m) }})" class="text-blue-400 hover:text-blue-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Edit</button>
                            <form action="{{ route('admin.mapels.destroy', $m->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus mapel ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Hapus</button>
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
            <h4 class="text-lg font-bold text-white">Tambah Mapel Baru</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form action="{{ route('admin.mapels.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Kode Mapel</label>
                <input type="text" name="kode_mapel" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: MTK atau PJOK">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: Matematika">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Jenis Mapel</label>
                <select name="jenis_mapel" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="umum">Umum (Umum diinput oleh Wali Kelas)</option>
                    <option value="khusus">Khusus (Khusus diinput oleh Guru Mapel)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Kriteria Ketuntasan Minimal (KKM)</label>
                <input type="number" name="kkm" value="75" required min="0" max="100" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="toggleModal('add-modal')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-slate-900 border border-slate-850 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Edit Mata Pelajaran</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Kode Mapel</label>
                <input type="text" name="kode_mapel" id="edit-kode" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" id="edit-nama" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Jenis Mapel</label>
                <select name="jenis_mapel" id="edit-jenis" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="umum">Umum</option>
                    <option value="khusus">Khusus</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">KKM</label>
                <input type="number" name="kkm" id="edit-kkm" required min="0" max="100" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="toggleModal('edit-modal')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function editMapel(mapel) {
        document.getElementById('edit-form').action = `/admin/mapels/${mapel.id}`;
        document.getElementById('edit-kode').value = mapel.kode_mapel;
        document.getElementById('edit-nama').value = mapel.nama_mapel;
        document.getElementById('edit-jenis').value = mapel.jenis_mapel;
        document.getElementById('edit-kkm').value = mapel.kkm;
        toggleModal('edit-modal');
    }
</script>
@endsection
