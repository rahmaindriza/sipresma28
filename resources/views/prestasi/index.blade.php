@extends('layouts.main')

@section('title', 'Monitoring Prestasi Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Monitoring Prestasi Siswa</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                @if(auth()->user()->isAdmin())
                    Daftar pencapaian prestasi akademik & non-akademik siswa SD Negeri 28 Kinali (Semua Kelas).
                @else
                    Daftar pencapaian prestasi akademik & non-akademik siswa di kelas Anda.
                @endif
            </p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-sm" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(159, 82, 97, 0.25);">
            <i class="bi bi-plus-lg me-1.5"></i>
            Tambah Prestasi
        </button>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-2xl shadow-sm border border-[var(--border-light)]">
        <form action="{{ auth()->user()->isAdmin() ? route('admin.prestasis') : route('wali.prestasi') }}" method="GET" class="flex flex-col md:flex-row gap-3">
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
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none;">
                    Cari & Filter
                </button>
                <a href="{{ auth()->user()->isAdmin() ? route('admin.prestasis') : route('wali.prestasi') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-350 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
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
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">No</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Kelas</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Lomba</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Kategori</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Tingkat</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Juara</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Poin</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Bukti</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-right" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Aksi</th>
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
                        <td class="py-3.5 px-4 text-xs font-bold text-center">
                            <span class="px-2 py-0.5 rounded bg-slate-100 border text-slate-700">
                                {{ $p->poin }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($p->sertifikat)
                                <button onclick="previewSertifikat('{{ asset('uploads/sertifikat/' . $p->sertifikat) }}')" class="btn btn-sm px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 rounded-lg text-[10px] font-bold transition">
                                    <i class="bi bi-file-earmark-image me-1"></i> Preview
                                </button>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <button onclick="editPrestasi({{ json_encode($p) }})" class="text-blue-600 hover:text-blue-800 text-[11px] font-bold px-2.5 py-1 hover:bg-blue-50 border border-transparent hover:border-blue-200 rounded-lg transition">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            
                            @php
                                $deleteRoute = auth()->user()->isAdmin() ? route('admin.prestasis.destroy', $p->id) : route('wali.prestasi.destroy', $p->id);
                                $cetakRoute = auth()->user()->isAdmin() ? route('admin.prestasis.cetak', $p->siswa_id) : route('wali.prestasi.cetak', $p->siswa_id);
                            @endphp

                            <form action="{{ $deleteRoute }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini?')" class="text-red-600 hover:text-red-800 text-[11px] font-bold px-2.5 py-1 hover:bg-red-50 border border-transparent hover:border-red-200 rounded-lg transition">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>

                            <a href="{{ $cetakRoute }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 text-[11px] font-bold px-2.5 py-1 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 rounded-lg transition inline-block">
                                <i class="bi bi-file-pdf"></i> PDF Rapor
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-400">
                            <div class="fs-1 text-slate-350 mb-3"><i class="bi bi-trophy"></i></div>
                            Belum ada data prestasi terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/70 p-4">
    <div class="bg-white border border-[var(--border-light)] rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b pb-3">
            <h4 class="text-base font-bold text-[var(--text-dark-main)]">Tambah Prestasi Siswa</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-slate-700 transition fs-4 border-0 bg-transparent">&times;</button>
        </div>
        <form action="{{ auth()->user()->isAdmin() ? route('admin.prestasis.store') : route('wali.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} (Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Nama Lomba / Pencapaian</label>
                <input type="text" name="nama_lomba" required placeholder="Contoh: Lomba Cerdas Cermat Matematika" class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
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
            <div class="grid grid-cols-2 gap-4">
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
                <input type="file" name="sertifikat" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="toggleModal('add-modal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(159, 82, 97, 0.25);">
                    <i class="bi bi-save me-1"></i> Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/70 p-4">
    <div class="bg-white border border-[var(--border-light)] rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b pb-3">
            <h4 class="text-base font-bold text-[var(--text-dark-main)]">Edit Prestasi Siswa</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-slate-700 transition fs-4 border-0 bg-transparent">&times;</button>
        </div>
        <form id="edit-form" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Pilih Siswa</label>
                <select name="siswa_id" id="edit-siswa_id" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} (Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Nama Lomba / Pencapaian</label>
                <input type="text" name="nama_lomba" id="edit-nama_lomba" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" id="edit-kategori" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Akademik">Akademik</option>
                        <option value="Non-Akademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Jenis Pelaksanaan</label>
                    <select name="jenis_pelaksanaan" id="edit-jenis_pelaksanaan" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Dalam Sekolah">Dalam Sekolah</option>
                        <option value="Luar Sekolah">Luar Sekolah</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tingkat</label>
                    <select name="tingkat" id="edit-tingkat" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Kecamatan">Kecamatan</option>
                        <option value="Kabupaten">Kabupaten</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Juara / Hasil</label>
                    <select name="juara" id="edit-juara" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Harapan">Harapan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Tanggal Penghargaan</label>
                <input type="date" name="tanggal_penghargaan" id="edit-tanggal_penghargaan" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-2">Ganti Sertifikat / Bukti Fisik (Biarkan kosong jika tidak diubah)</label>
                <input type="file" name="sertifikat" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                <p id="edit-sertifikat-info" class="text-[10px] text-slate-500 mt-1"></p>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="toggleModal('edit-modal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none; box-shadow: 0 4px 10px rgba(159, 82, 97, 0.25);">
                    <i class="bi bi-save me-1"></i> Perbarui Prestasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-white border rounded-3xl w-full max-w-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] flex flex-col justify-between">
        <div class="flex justify-between items-center border-b pb-2">
            <h4 class="text-base font-bold text-[var(--text-dark-main)]">Preview Bukti Sertifikat / Piagam</h4>
            <button onclick="toggleModal('preview-modal')" class="text-slate-400 hover:text-slate-700 transition fs-4 border-0 bg-transparent">&times;</button>
        </div>
        <div class="flex-1 flex items-center justify-center overflow-auto bg-slate-100 rounded-2xl p-2 min-h-[300px]">
            <img id="preview-image" src="" alt="Sertifikat" class="img-fluid rounded-xl max-h-[50vh] object-contain hidden">
            <iframe id="preview-pdf" src="" class="w-full h-[55vh] border-0 rounded-xl hidden"></iframe>
        </div>
        <div class="flex justify-end pt-2 border-t">
            <button type="button" onclick="toggleModal('preview-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">Tutup</button>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function editPrestasi(prestasi) {
        const isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
        const actionUrl = isAdmin ? `/admin/prestasis/${prestasi.id}` : `/wali/prestasi/${prestasi.id}`;
        
        document.getElementById('edit-form').action = actionUrl;
        document.getElementById('edit-siswa_id').value = prestasi.siswa_id;
        document.getElementById('edit-nama_lomba').value = prestasi.nama_lomba;
        document.getElementById('edit-kategori').value = prestasi.kategori;
        document.getElementById('edit-jenis_pelaksanaan').value = prestasi.jenis_pelaksanaan;
        document.getElementById('edit-tingkat').value = prestasi.tingkat;
        document.getElementById('edit-juara').value = prestasi.juara;
        document.getElementById('edit-tanggal_penghargaan').value = prestasi.tanggal_penghargaan;
        
        const certInfo = document.getElementById('edit-sertifikat-info');
        if (prestasi.sertifikat) {
            certInfo.textContent = `File saat ini: ${prestasi.sertifikat}`;
        } else {
            certInfo.textContent = 'Belum ada bukti sertifikat terunggah.';
        }
        
        toggleModal('edit-modal');
    }

    function previewSertifikat(url) {
        const isPdf = url.toLowerCase().endsWith('.pdf');
        const imgEl = document.getElementById('preview-image');
        const pdfEl = document.getElementById('preview-pdf');

        if (isPdf) {
            imgEl.classList.add('hidden');
            pdfEl.classList.remove('hidden');
            pdfEl.src = url;
        } else {
            pdfEl.classList.add('hidden');
            imgEl.classList.remove('hidden');
            imgEl.src = url;
        }
        toggleModal('preview-modal');
    }
</script>
@endsection
