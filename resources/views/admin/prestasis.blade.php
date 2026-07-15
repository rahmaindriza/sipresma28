@extends('layouts.dashboard')

@section('title', 'Kelola Prestasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Prestasi Siswa</h3>
            <p class="text-xs text-slate-400 mt-1">Daftar pencapaian akademik maupun non-akademik siswa SD Negeri 28 Kinali.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Prestasi
        </button>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-lg border border-slate-800/40">
        <form action="{{ route('admin.prestasis') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan atau nama siswa..." 
                    class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="w-full md:w-56">
                <select name="jenis_prestasi" class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Semua Jenis Prestasi --</option>
                    <option value="Akademik" {{ request('jenis_prestasi') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Non-Akademik" {{ request('jenis_prestasi') === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.prestasis') }}" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Prestasis Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6">Jenis Prestasi</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @foreach($prestasis as $p)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 text-slate-400 text-xs">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                        <td class="py-4 px-6 font-semibold text-white">{{ $p->siswa->nama }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2 py-0.5 rounded bg-slate-950 text-slate-300 border border-slate-800 text-xs">
                                {{ $p->siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider
                                {{ $p->jenis_prestasi === 'Akademik' ? 'bg-amber-900/40 text-amber-300 border border-amber-800' : 'bg-blue-900/40 text-blue-300 border border-blue-800' }}">
                                {{ $p->jenis_prestasi }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-300 max-w-xs truncate">{{ $p->keterangan }}</td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button onclick="editPrestasi({{ json_encode($p) }})" class="text-blue-400 hover:text-blue-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Edit</button>
                            <form action="{{ route('admin.prestasis.destroy', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Hapus</button>
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
            <h4 class="text-lg font-bold text-white">Tambah Prestasi Siswa</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form action="{{ route('admin.prestasis.store') }}" method="POST" class="space-y-4">
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
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Keterangan / Detail Penghargaan</label>
                <textarea name="keterangan" required rows="3" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: Juara 1 Lomba Menggambar Kaligrafi tingkat Kecamatan Kinali"></textarea>
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
            <h4 class="text-lg font-bold text-white">Edit Prestasi</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Siswa</label>
                <select name="siswa_id" id="edit-siswa_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} (Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Jenis Prestasi</label>
                <select name="jenis_prestasi" id="edit-jenis_prestasi" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="Akademik">Akademik</option>
                    <option value="Non-Akademik">Non-Akademik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="edit-tanggal" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Keterangan</label>
                <textarea name="keterangan" id="edit-keterangan" required rows="3" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm"></textarea>
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

    function editPrestasi(prestasi) {
        document.getElementById('edit-form').action = `/admin/prestasis/${prestasi.id}`;
        document.getElementById('edit-siswa_id').value = prestasi.siswa_id;
        document.getElementById('edit-jenis_prestasi').value = prestasi.jenis_prestasi;
        document.getElementById('edit-tanggal').value = prestasi.tanggal;
        document.getElementById('edit-keterangan').value = prestasi.keterangan;
        toggleModal('edit-modal');
    }
</script>
@endsection
