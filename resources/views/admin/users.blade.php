@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">
    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg space-y-1">
        <div class="flex items-center space-x-2 font-semibold">
            <svg class="w-5 h-5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-white text-sm">Gagal Menyimpan Data:</span>
        </div>
        <ul class="list-disc list-inside text-xs text-red-450 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Akun Pengguna</h3>
            <p class="text-xs text-slate-400 mt-1">Tambahkan akun, perbarui data login, atau aktifkan/nonaktifkan status akses akun pengguna.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-lg border border-slate-800/40">
        <form action="{{ route('admin.users') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, username..." 
                    class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div class="w-full md:w-56">
                <select name="role" class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru_mapel" {{ request('role') === 'guru_mapel' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                    <option value="wali_kelas" {{ request('role') === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    <option value="kepala_sekolah" {{ request('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.users') }}" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Username</th>
                        <th class="py-4 px-6">Password</th>
                        <th class="py-4 px-6">Role</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-medium text-white">{{ $u->name }}</td>
                        <td class="py-4 px-6 text-slate-300">{{ $u->email }}</td>
                        <td class="py-4 px-6">{{ $u->username }}</td>
                        <td class="py-4 px-6 font-mono text-xs text-slate-300">{{ $u->password_plain ?? 'N/A (Lama)' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider
                                @if($u->role === 'admin') bg-purple-900/40 text-purple-300 border border-purple-800
                                @elseif($u->role === 'guru_mapel') bg-blue-900/40 text-blue-300 border border-blue-800
                                @elseif($u->role === 'wali_kelas') bg-yellow-900/40 text-yellow-300 border border-yellow-800
                                @elseif($u->role === 'kepala_sekolah') bg-green-900/40 text-green-300 border border-green-800
                                @endif">
                                {{ str_replace('_', ' ', $u->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition
                                    {{ $u->status_akun === 'aktif' ? 'bg-green-950 text-green-400 border border-green-800 hover:bg-green-900/40' : 'bg-red-950 text-red-400 border border-red-900 hover:bg-red-900/40' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $u->status_akun === 'aktif' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    {{ ucfirst($u->status_akun) }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button onclick="editUser(this)" data-user="{{ json_encode($u) }}" class="text-blue-400 hover:text-blue-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Edit</button>
                            @if(auth()->user()->id !== $u->id)
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-800 rounded transition">Hapus</button>
                            </form>
                            @endif
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
            <h4 class="text-lg font-bold text-white">Tambah Pengguna Baru</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" autocomplete="new-password" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Role Akses</label>
                <select name="role" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="admin">Admin</option>
                    <option value="guru_mapel">Guru Mata Pelajaran</option>
                    <option value="wali_kelas">Wali Kelas</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-355 uppercase tracking-wider mb-2">Status Akun</label>
                <select name="status_akun" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
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
            <h4 class="text-lg font-bold text-white">Edit Pengguna</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-white transition">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" id="edit-email" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" id="edit-username" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-355 uppercase tracking-wider mb-2">Password (Kosongkan jika tidak diganti)</label>
                <input type="password" name="password" autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Password baru">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Role Akses</label>
                <select name="role" id="edit-role" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="admin">Admin</option>
                    <option value="guru_mapel">Guru Mata Pelajaran</option>
                    <option value="wali_kelas">Wali Kelas</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-355 uppercase tracking-wider mb-2">Status Akun</label>
                <select name="status_akun" id="edit-status" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
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

    function editUser(button) {
        const user = JSON.parse(button.getAttribute('data-user'));
        let actionUrl = "{{ route('admin.users.update', ':id') }}";
        actionUrl = actionUrl.replace(':id', user.id).replace('%3Aid', user.id);
        document.getElementById('edit-form').action = actionUrl;
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-username').value = user.username;
        document.getElementById('edit-role').value = user.role;
        document.getElementById('edit-status').value = user.status_akun;
        toggleModal('edit-modal');
    }
</script>
@endsection
