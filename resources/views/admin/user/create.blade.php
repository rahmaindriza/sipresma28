@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Tambah Pengguna Baru</h3>
            <p class="text-xs text-White-400 mt-1">Daftarkan akun pengguna baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-slate-700 text-slate-300 hover:bg-slate-800 hover:text-white font-semibold rounded-xl text-xs transition flex items-center bg-slate-900 shadow-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Container Card -->
    <div class="glass-panel border border-slate-800/40 rounded-3xl p-6 shadow-xl">
        @if ($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-semibold text-red-400">
                <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm">Gagal Menyimpan Data:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-400 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" autocomplete="new-password" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Role Akses</label>
                     <select name="role" id="add-role" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru_mapel" {{ old('role') == 'guru_mapel' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-slate-355 uppercase tracking-wider mb-2">Status Akun</label>
                    <select name="status_akun" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="aktif" {{ old('status_akun') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status_akun') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6" id="add-kelas-select-container" style="display: {{ old('role') == 'wali_kelas' ? 'block' : 'none' }};">
                    <label class="block text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Kelas Diampu</label>
                    <select name="kelas_id" id="add-kelas-select" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" {{ old('role') == 'wali_kelas' ? 'required' : '' }}>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800/60 mt-6">
                <a href="{{ route('admin.users') }}" class="px-4 py-2.5 bg-slate-850 hover:bg-slate-800 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addRoleSelect = document.getElementById('add-role');
        const addContainer = document.getElementById('add-kelas-select-container');
        const addKelasSelect = document.getElementById('add-kelas-select');

        addRoleSelect.addEventListener('change', function() {
            if (this.value === 'wali_kelas') {
                addContainer.style.display = 'block';
                addKelasSelect.setAttribute('required', 'required');
            } else {
                addContainer.style.display = 'none';
                addKelasSelect.removeAttribute('required');
                addKelasSelect.value = '';
            }
        });
    });
</script>
@endsection
