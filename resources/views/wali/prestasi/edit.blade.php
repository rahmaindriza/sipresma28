@extends('layouts.main')

@section('title', 'Edit Prestasi Siswa')

@section('content')
<div class="space-y-6">
    @if(isset($error))
    <div class="p-6 rounded-3xl bg-red-950/30 border border-red-900/50 text-red-300 shadow-lg">
        <h4 class="text-lg font-bold text-white mb-2">Pemberitahuan</h4>
        <p class="text-sm">{{ $error }}</p>
    </div>
    @else
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Edit Prestasi Siswa</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Perbarui data prestasi akademik maupun non-akademik siswa di {{ $kelas->nama_kelas }}.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('wali.prestasi') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-3xl mx-auto glass-panel p-8 rounded-3xl shadow-sm border border-[var(--border-light)] bg-white space-y-6">
        <h4 class="text-base font-bold text-[var(--text-dark-main)] border-b border-[var(--border-light)] pb-3">Form Edit Prestasi</h4>
        
        <form action="{{ route('wali.prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    @foreach($students as $siswa)
                    <option value="{{ $siswa->id }}" {{ $prestasi->siswa_id == $siswa->id ? 'selected' : '' }}>{{ $siswa->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Nama Lomba / Pencapaian</label>
                <input type="text" name="nama_lomba" value="{{ $prestasi->nama_lomba }}" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Akademik" {{ $prestasi->kategori == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Non-Akademik" {{ $prestasi->kategori == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Jenis Pelaksanaan</label>
                    <select name="jenis_pelaksanaan" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Dalam Sekolah" {{ $prestasi->jenis_pelaksanaan == 'Dalam Sekolah' ? 'selected' : '' }}>Dalam Sekolah</option>
                        <option value="Luar Sekolah" {{ $prestasi->jenis_pelaksanaan == 'Luar Sekolah' ? 'selected' : '' }}>Luar Sekolah</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tingkat</label>
                    <select name="tingkat" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Kecamatan" {{ $prestasi->tingkat == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                        <option value="Kabupaten" {{ $prestasi->tingkat == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                        <option value="Provinsi" {{ $prestasi->tingkat == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                        <option value="Nasional" {{ $prestasi->tingkat == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Juara / Hasil</label>
                    <select name="juara" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Juara 1" {{ $prestasi->juara == 'Juara 1' ? 'selected' : '' }}>Juara 1</option>
                        <option value="Juara 2" {{ $prestasi->juara == 'Juara 2' ? 'selected' : '' }}>Juara 2</option>
                        <option value="Juara 3" {{ $prestasi->juara == 'Juara 3' ? 'selected' : '' }}>Juara 3</option>
                        <option value="Harapan" {{ $prestasi->juara == 'Harapan' ? 'selected' : '' }}>Harapan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tanggal Penghargaan</label>
                <input type="date" name="tanggal_penghargaan" value="{{ \Carbon\Carbon::parse($prestasi->tanggal_penghargaan)->format('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Ganti Sertifikat / Bukti Fisik (.jpg, .png, .pdf) - Biarkan kosong jika tidak diubah</label>
                <input type="file" name="sertifikat" class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                <p class="text-[10px] text-slate-500 mt-1">
                    @if($prestasi->sertifikat)
                        File saat ini: <a href="{{ asset('uploads/sertifikat/' . $prestasi->sertifikat) }}" target="_blank" class="text-blue-600 hover:underline">{{ $prestasi->sertifikat }}</a>
                    @else
                        Belum ada bukti sertifikat terunggah.
                    @endif
                </p>
            </div>
            
            <div class="pt-6 flex items-center justify-end gap-3 border-t border-[var(--border-light)]">
                <a href="{{ route('wali.prestasi') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-white font-semibold rounded-xl text-xs transition shadow-sm" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(61, 90, 128, 0.25);">
                    <i class="bi bi-save me-1"></i> Perbarui Prestasi
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
