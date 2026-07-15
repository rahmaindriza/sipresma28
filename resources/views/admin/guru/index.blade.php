@extends('layouts.main')

@section('title', 'Kelola Guru')

@section('content')
<div class="space-y-6">
    <!-- Success or Error Alert -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-800 shadow-md flex items-center space-x-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 shadow-md space-y-1">
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

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-[#3D2228]">Kelola Data Master Guru</h3>
            <p class="text-xs text-[#8E797D] mt-1">Daftar guru terdaftar, NIP, jabatan, dan jenis kelamin.</p>
        </div>
        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.gurus.create') }}" class="px-4 py-2 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition flex items-center shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Data Guru
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-sm border border-[#EAE1E3] bg-white">
        <form action="{{ route('admin.gurus') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau jabatan..." 
                    class="w-full px-4 py-2 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] placeholder-[#8E797D] focus:outline-none transition text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.gurus') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Gurus Table -->
    <div class="bg-white border border-[#EAE1E3] rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[#3D2228]">
                <thead>
                    <tr class="bg-[#FDF4F5] border-b border-[#EAE1E3] text-xs font-bold text-[#9F5261] uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6 w-24">Foto</th>
                        <th class="py-4 px-6">Nama Guru</th>
                        <th class="py-4 px-6">NIP</th>
                        <th class="py-4 px-6">Jabatan</th>
                        <th class="py-4 px-6">L/P</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAE1E3] text-sm">
                    @foreach($gurus as $g)
                    <tr class="hover:bg-[#FFF9FA] transition duration-150">
                        <td class="py-4 px-6 font-semibold text-[#8E797D]">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            @if($g->foto && file_exists(public_path('uploads/guru/' . $g->foto)))
                                <img src="{{ asset('uploads/guru/' . $g->foto) }}" alt="Foto {{ $g->nama }}" class="rounded-circle object-fit-cover shadow-sm border border-[#EAE1E3]" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-[#FDF4F5] border border-[#EAE1E3] text-[#9F5261] d-flex align-items-center justify-content-center font-bold" style="width: 45px; height: 45px; font-size: 14px;">
                                    {{ strtoupper(substr($g->nama, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-bold text-[#3D2228]">{{ $g->nama }}</td>
                        <td class="py-4 px-6 font-semibold text-[#3D2228]">{{ $g->nip }}</td>
                        <td class="py-4 px-6 text-[#3D2228]">{{ $g->jabatan }}</td>
                        <td class="py-4 px-6">{{ $g->jk }}</td>
                        <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                            <button onclick="showDetailGuru({{ json_encode($g) }})" class="px-2.5 py-1.5 border border-[#9F5261] text-[#9F5261] hover:bg-[#9F5261] hover:text-white rounded-lg text-xs font-semibold transition flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <a href="{{ route('admin.gurus.edit', $g->id) }}" class="text-[#9F5261] hover:text-[#86414E] text-xs font-semibold px-2 py-1 transition">Edit</a>
                            <form action="{{ route('admin.gurus.destroy', $g->id) }}" method="POST" class="inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini beserta foto profilnya?')" class="text-red-800 hover:text-red-950 text-xs font-semibold px-2 py-1 bg-[#FDF0F2] border border-red-200 rounded-lg transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/60 p-4">
    <div class="bg-white border border-[#EAE1E3] rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center bg-[#9F5261] px-6 py-4">
            <h4 class="text-lg font-bold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Detail Informasi Guru
            </h4>
            <button onclick="toggleModal('detail-modal')" class="text-white/80 hover:text-white transition text-2xl">&times;</button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="row g-4">
                <!-- Sisi Kiri: Foto Guru -->
                <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center border-end border-[#EAE1E3] pb-4 pb-md-0">
                    <div id="detail-foto-container" class="rounded-circle overflow-hidden border border-[#EAE1E3] shadow-sm mb-3" style="width: 140px; height: 140px;">
                        <img id="detail-foto" src="" alt="Foto Guru" class="w-100 h-100 object-fit-cover">
                        <div id="detail-foto-placeholder" class="w-100 h-100 bg-[#FDF4F5] text-[#9F5261] d-flex align-items-center justify-content-center font-bold" style="font-size: 36px;">
                            G
                        </div>
                    </div>
                    <span id="detail-badge-jk" class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FDF4F5] text-[#9F5261] border border-[#EAE1E3]">
                        Jenis Kelamin
                    </span>
                </div>
                
                <!-- Sisi Kanan: Detail Data Lengkap -->
                <div class="col-md-8 space-y-4 ps-md-4">
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Nama Lengkap</span>
                        <h4 id="detail-nama" class="text-xl font-bold text-[#3D2228]">-</h4>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">NIP (Nomor Induk Pegawai)</span>
                        <span id="detail-nip" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Jabatan</span>
                        <span id="detail-jabatan" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Jenis Kelamin</span>
                        <span id="detail-jk" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end px-6 py-4 border-t border-[#EAE1E3] bg-[#FAFAF9]">
            <button type="button" onclick="toggleModal('detail-modal')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition">Tutup</button>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function showDetailGuru(guru) {
        document.getElementById('detail-nama').innerText = guru.nama;
        document.getElementById('detail-nip').innerText = guru.nip;
        document.getElementById('detail-jabatan').innerText = guru.jabatan;
        document.getElementById('detail-jk').innerText = guru.jk;
        document.getElementById('detail-badge-jk').innerText = guru.jk;

        const imgEl = document.getElementById('detail-foto');
        const placeholderEl = document.getElementById('detail-foto-placeholder');

        if (guru.foto) {
            imgEl.src = `/uploads/guru/${guru.foto}`;
            imgEl.style.display = 'block';
            placeholderEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            placeholderEl.style.display = 'flex';
            placeholderEl.innerText = guru.nama.substring(0, 2).toUpperCase();
        }

        toggleModal('detail-modal');
    }
</script>
@endsection
