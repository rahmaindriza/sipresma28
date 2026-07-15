@extends('layouts.dashboard')

@section('title', 'Kelola Kegiatan Sekolah')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Kelola Data Kegiatan Sekolah</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola data kegiatan, dokumentasi, agenda akademik dan non-akademik beserta unggahan foto.</p>
        </div>
        <button onclick="toggleModal('add-modal')" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-lg shadow-blue-950/20">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kegiatan
        </button>
    </div>

    <!-- Error Validation Messages -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-900/30 border border-red-800 text-red-300">
            <div class="flex items-center space-x-2 mb-2 font-bold">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Gagal menyimpan data kegiatan:</span>
            </div>
            <ul class="list-disc pl-5 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Kegiatans Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-xl border border-slate-800/40">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6 w-28">Foto Kegiatan</th>
                        <th class="py-4 px-6">Nama Kegiatan</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Jenis</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Semester</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                    @forelse($kegiatans as $keg)
                    <tr class="hover:bg-slate-900/40 transition duration-150">
                        <td class="py-4 px-6 font-semibold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            @if($keg->gambar)
                            <img src="{{ asset('storage/' . $keg->gambar) }}" alt="{{ $keg->nama_kegiatan }}" class="w-16 h-12 object-cover rounded-lg border border-slate-800 shadow-sm">
                            @else
                            <div class="w-16 h-12 bg-slate-950 border border-slate-850 rounded-lg flex items-center justify-center text-slate-600 text-xs">
                                <i class="bi bi-image fs-6"></i>
                            </div>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-bold text-white">{{ $keg->nama_kegiatan }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-950 text-slate-350 border border-slate-800 uppercase tracking-wider text-[10px]">
                                {{ $keg->kategori }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-medium {{ $keg->jenis_kegiatan === 'akademik' ? 'text-blue-400' : 'text-pink-400' }} capitalize">
                                {{ $keg->jenis_kegiatan }}
                            </span>
                        </td>
                        <td class="py-4 px-6">{{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d F Y') }}</td>
                        <td class="py-4 px-6 text-slate-400">{{ $keg->semester_aktif }}</td>
                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                            <button onclick="editKegiatan({{ $keg->id }})" class="text-blue-400 hover:text-blue-300 text-xs font-semibold px-2 py-1 hover:bg-slate-850 rounded-lg transition border border-transparent hover:border-slate-800">
                                Edit
                            </button>
                            <form action="{{ route('admin.kegiatan.destroy', $keg->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data kegiatan ini?')" class="text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 hover:bg-slate-850 rounded-lg transition border border-transparent hover:border-slate-800">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 px-6 text-center text-slate-500 italic">
                            Belum ada data kegiatan sekolah yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Tambah Kegiatan Baru</h4>
            <button onclick="toggleModal('add-modal')" class="text-slate-400 hover:text-white text-xl transition">&times;</button>
        </div>
        <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: Pentas Seni Akhir Tahun">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="akademik">Akademik</option>
                        <option value="non-akademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="ekstrakurikuler">Ekstrakurikuler</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="perlombaan">Perlombaan</option>
                        <option value="resmi">Resmi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Semester Aktif</label>
                    <input type="text" name="semester_aktif" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Contoh: 2026/2027 Ganjil">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" required rows="3" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm" placeholder="Tuliskan detail deskripsi kegiatan disini..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Kegiatan (Max: 2MB, JPG/PNG)</label>
                <input type="file" name="gambar" required class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none focus:border-blue-500 transition text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer">
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800/80">
                <button type="button" onclick="toggleModal('add-modal')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-300 font-semibold rounded-xl text-xs transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-xs transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/80 p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Edit Kegiatan</h4>
            <button onclick="toggleModal('edit-modal')" class="text-slate-400 hover:text-white text-xl transition">&times;</button>
        </div>
        <form id="edit-form" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="edit-nama_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" id="edit-tanggal_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" id="edit-jenis_kegiatan" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="akademik">Akademik</option>
                        <option value="non-akademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" id="edit-kategori" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                        <option value="ekstrakurikuler">Ekstrakurikuler</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="perlombaan">Perlombaan</option>
                        <option value="resmi">Resmi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Semester Aktif</label>
                    <input type="text" name="semester_aktif" id="edit-semester_aktif" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" id="edit-deskripsi" required rows="3" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition text-sm"></textarea>
            </div>

            <div>
                <div class="flex items-center gap-4 mb-2">
                    <div id="edit-gambar-preview-wrapper" class="hidden">
                        <span class="block text-xs font-semibold text-slate-500 uppercase mb-1">Foto Lama</span>
                        <img id="edit-gambar-preview" src="" alt="Preview" class="w-20 h-16 object-cover rounded-lg border border-slate-850">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Ganti Foto Kegiatan (Opsional)</label>
                        <input type="file" name="gambar" class="w-full px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none focus:border-blue-500 transition text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800/80">
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

    function editKegiatan(id) {
        fetch(`/admin/kegiatan/${id}/edit`)
            .then(response => response.json())
            .then(kegiatan => {
                document.getElementById('edit-form').action = `/admin/kegiatan/${id}`;
                document.getElementById('edit-nama_kegiatan').value = kegiatan.nama_kegiatan;
                document.getElementById('edit-tanggal_kegiatan').value = kegiatan.tanggal_kegiatan;
                document.getElementById('edit-jenis_kegiatan').value = kegiatan.jenis_kegiatan;
                document.getElementById('edit-kategori').value = kegiatan.kategori;
                document.getElementById('edit-semester_aktif').value = kegiatan.semester_aktif;
                document.getElementById('edit-deskripsi').value = kegiatan.deskripsi;
                
                const previewWrapper = document.getElementById('edit-gambar-preview-wrapper');
                const previewImg = document.getElementById('edit-gambar-preview');
                
                if (kegiatan.gambar) {
                    previewImg.src = `/storage/${kegiatan.gambar}`;
                    previewWrapper.classList.remove('hidden');
                } else {
                    previewWrapper.classList.add('hidden');
                }

                toggleModal('edit-modal');
            })
            .catch(error => {
                alert('Gagal mengambil data kegiatan.');
                console.error(error);
            });
    }
</script>
@endsection
