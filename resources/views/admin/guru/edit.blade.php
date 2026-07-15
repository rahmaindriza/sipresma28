@extends('layouts.main')

@section('title', 'Edit Guru')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-[#3D2228]">Edit Data Guru</h3>
            <p class="text-xs text-[#8E797D] mt-1">Perbarui data profil, NIP, jabatan, dan foto guru terdaftar.</p>
        </div>
        <a href="{{ route('admin.gurus') }}" class="px-4 py-2 border border-[#9F5261] text-[#9F5261] hover:bg-[#9F5261] hover:text-white font-semibold rounded-xl text-xs transition flex items-center">
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

        <form action="{{ route('admin.gurus.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $guru->nama) }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <select name="jk" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                        <option value="Laki-laki" {{ old('jk', $guru->jk) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jk', $guru->jk) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                </div>
                <div class="col-md-6">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Jabatan / Peran</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $guru->jabatan) }}" required class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                </div>
            </div>

            <div class="row g-4 align-items-end">
                <div class="col-md-8">
                    <label class="block text-xs font-semibold text-[#3D2228] uppercase tracking-wider mb-2">Unggah Foto Baru (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewImage(event)" class="w-full px-4 py-2.5 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none focus:border-[#9F5261] transition text-sm">
                    <p class="text-xs text-[#8E797D] mt-1.5">Format file yang diperbolehkan: JPG, JPEG, PNG, WEBP. Ukuran file maksimal: 2MB.</p>
                </div>

                <div class="col-md-4 d-flex justify-content-start justify-content-md-end mt-4 mt-md-0">
                    <!-- Current Photo & Preview Block -->
                    <div class="d-flex space-x-4">
                        <!-- Current Photo -->
                        <div>
                            <span class="block text-xs font-semibold text-[#8E797D] uppercase tracking-wider mb-2">Foto Saat Ini</span>
                            <div class="rounded-circle overflow-hidden border border-[#EAE1E3] shadow-sm" style="width: 80px; height: 80px;">
                                @if($guru->foto && file_exists(public_path('uploads/guru/' . $guru->foto)))
                                    <img src="{{ asset('uploads/guru/' . $guru->foto) }}" alt="Foto {{ $guru->nama }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 bg-[#FDF4F5] text-[#9F5261] d-flex align-items-center justify-content-center font-bold" style="font-size: 20px;">
                                        {{ strtoupper(substr($guru->nama, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Live Photo Preview -->
                        <div id="preview-container" style="display: none;">
                            <span class="block text-xs font-semibold text-[#8E797D] uppercase tracking-wider mb-2">Pratinjau Baru</span>
                            <div class="rounded-circle overflow-hidden border border-[#EAE1E3] shadow-sm" style="width: 80px; height: 80px;">
                                <img id="image-preview" src="" alt="Image Preview" class="w-100 h-100 object-fit-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-[#EAE1E3]">
                <a href="{{ route('admin.gurus') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition shadow-sm">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
