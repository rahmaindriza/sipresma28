@extends('layouts.main')

@section('title', 'Tambah Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-[#3D2228]">Tambah Siswa Baru</h3>
            <p class="text-xs text-[#8E797D] mt-1">Daftarkan data siswa baru dengan lengkap ke dalam sistem informasi.</p>
        </div>
        <a href="{{ route('admin.siswas') }}" class="px-4 py-2 border border-[#9F5261] text-[#9F5261] hover:bg-[#9F5261] hover:text-white font-semibold rounded-xl text-xs transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white border border-[#EAE1E3] rounded-3xl p-6 shadow-sm">
        @if ($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-semibold text-red-900">
                <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm">Gagal Menyimpan Data:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-750 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.siswas.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm" placeholder="Contoh: Ahmad Fajar">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <select name="jk" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jk') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jk') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">NISN (10 Karakter)</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" required maxlength="10" minlength="10" class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm" placeholder="Contoh: 0123456789">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">NIK (16 Karakter)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" minlength="16" class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm" placeholder="Contoh: 130601xxxxxxxxxx">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm" placeholder="Contoh: Padang">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Agama</label>
                    <select name="agama" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                        <option value="">-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $a)
                        <option value="{{ $a }}" {{ old('agama') === $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Kelas</label>
                    <select name="kelas_id" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Alamat Lengkap</label>
                <textarea name="alamat" required rows="4" class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm" placeholder="Contoh: Jl. Raya Kinali No. 28...">{{ old('alamat') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-[#EAE1E3]">
                <a href="{{ route('admin.siswas') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
