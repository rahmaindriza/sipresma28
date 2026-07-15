@extends('layouts.dashboard')

@section('title', 'Kelola Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Data Master Kelas</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola data kelas sekolah beserta wali kelas penanggung jawab.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelas
        </button>
    </div>

    <!-- Kelas Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama Kelas</th>
                        <th class="py-4 px-6">Wali Kelas</th>
                        <th class="py-4 px-6">NIP Wali Kelas</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @foreach($kelas as $k)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-white">{{ $k->nama_kelas }}</td>
                        <td class="py-4 px-6">
                            @if($k->waliKelas)
                            <span class="text-blue-400 font-medium">{{ $k->waliKelas->nama }}</span>
                            @else
                            <span class="text-slate-550 italic">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">{{ $k->waliKelas->nip ?? '-' }}</td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button onclick="editKelas({{ json_encode($k) }})" class="text-blue-400 hover:text-blue-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Edit</button>
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data kelas ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Hapus</button>
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
            <h4 class="text-lg font-bold text-white">Tambah Kelas Baru</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Kelas</label>
                <input type="text" name="nama_kelas" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: Kelas I.A atau Kelas VI.B">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Pilih Wali Kelas (Guru)</label>
                <select name="wali_kelas_id" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Tanpa Wali Kelas --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->nama }} (NIP: {{ $guru->nip ?? '-' }})</option>
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

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-slate-900 border border-slate-850 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Edit Kelas</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="edit-nama_kelas" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Wali Kelas</label>
                <select name="wali_kelas_id" id="edit-wali_kelas_id" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Tanpa Wali Kelas --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->nama }} (NIP: {{ $guru->nip ?? '-' }})</option>
                    @endforeach
                </select>
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

    function editKelas(kelas) {
        document.getElementById('edit-form').action = `/admin/kelas/${kelas.id}`;
        document.getElementById('edit-nama_kelas').value = kelas.nama_kelas;
        document.getElementById('edit-wali_kelas_id').value = kelas.wali_kelas_id || '';
        toggleModal('edit-modal');
    }
</script>
@endsection
