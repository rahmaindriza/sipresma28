@extends('layouts.main')

@section('title', 'Tambah Prestasi Siswa')

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
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Tambah Prestasi Siswa</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Input capaian prestasi akademik maupun non-akademik siswa di {{ $kelas->nama_kelas }}.</p>
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
        <h4 class="text-base font-bold text-[var(--text-dark-main)] border-b border-[var(--border-light)] pb-3">Form Input Prestasi Baru</h4>
        
        <form action="{{ route('wali.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Nama Lomba / Pencapaian</label>
                <input type="text" name="nama_lomba" required placeholder="Contoh: Lomba Cerdas Cermat Matematika" class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Akademik">Akademik</option>
                        <option value="Non-Akademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Jenis Pelaksanaan</label>
                    <select name="jenis_pelaksanaan" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Dalam Sekolah">Dalam Sekolah</option>
                        <option value="Luar Sekolah">Luar Sekolah</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tingkat</label>
                    <select name="tingkat" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Kecamatan">Kecamatan</option>
                        <option value="Kabupaten">Kabupaten</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Juara / Hasil</label>
                    <select name="juara" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Harapan">Harapan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tanggal Penghargaan</label>
                <input type="date" name="tanggal_penghargaan" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Unggah Sertifikat / Bukti Fisik (.jpg, .png, .pdf)</label>
                <input type="file" name="sertifikat" class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="pt-6 flex items-center justify-end gap-3 border-t border-[var(--border-light)]">
                <a href="{{ route('wali.prestasi') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-white font-semibold rounded-xl text-xs transition shadow-sm" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(61, 90, 128, 0.25);">
                    <i class="bi bi-save me-1"></i> Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
