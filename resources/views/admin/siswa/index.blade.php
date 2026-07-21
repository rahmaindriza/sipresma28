@extends('layouts.main')

@section('title', 'Kelola Siswa')

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
            <h3 class="text-xl font-bold text-[#3D2228]">Kelola Data Master Siswa</h3>
            <p class="text-xs text-[#8E797D] mt-1">Daftar siswa terdaftar, NISN, jenis kelamin, dan kelas.</p>
        </div>
        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.siswas.cetak', ['kelas_id' => request('kelas_id'), 'search' => request('search')]) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-lg shadow-emerald-950/20">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Data Siswa
            </a>
            <a href="{{ route('admin.siswas.create') }}" class="px-4 py-2 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition flex items-center shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Data Siswa
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-sm border border-[#EAE1E3] bg-white">
        <form action="{{ route('admin.siswas') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau NISN..." 
                    class="w-full px-4 py-2 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] placeholder-[#8E797D] focus:outline-none transition text-sm">
            </div>
            <div class="w-full md:w-56">
                <select name="kelas_id" class="w-full px-4 py-2 border border-[#EAE1E3] bg-white rounded-xl text-[#3D2228] focus:outline-none transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-[#9F5261] hover:bg-[#86414E] text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.siswas') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Siswas Table -->
    <div class="bg-white border border-[#EAE1E3] rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[#3D2228]">
                <thead>
                    <tr class="bg-[#FDF4F5] border-b border-[#EAE1E3] text-xs font-bold text-[#9F5261] uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6">NISN</th>
                        <th class="py-4 px-6">JK</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAE1E3] text-sm">
                    @foreach($siswas as $s)
                    <tr class="hover:bg-[#FFF9FA] transition duration-150">
                        <td class="py-4 px-6 font-semibold text-[#8E797D]">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#3D2228]">{{ $s->nama }}</span>
                                <button type="button" onclick="showHistoriSiswa({{ $s->id }})" title="Lihat Histori Rapor & Prestasi" class="p-1 hover:bg-[#FDF4F5] text-blue-600 rounded transition border-0 bg-transparent cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold">{{ $s->nisn }}</td>
                        <td class="py-4 px-6">{{ $s->jk }}</td>
                        <td class="py-4 px-6">
                            @if($s->status === 'Lulus')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                    Alumni / Lulus
                                </span>
                            @elseif($s->status === 'Keluar')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                    Keluar
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FDF4F5] text-[#9F5261] border border-[#EAE1E3]">
                                    Kelas {{ $s->kelas->nama_kelas ?? '-' }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                            <button onclick="showDetailSiswa({{ json_encode($s) }})" class="px-2.5 py-1.5 border border-[#9F5261] text-[#9F5261] hover:bg-[#9F5261] hover:text-white rounded-lg text-xs font-semibold transition flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <a href="{{ route('admin.siswas.edit', $s->id) }}" class="text-[#9F5261] hover:text-[#86414E] text-xs font-semibold px-2 py-1 transition">Edit</a>
                            <form action="{{ route('admin.siswas.destroy', $s->id) }}" method="POST" class="inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')" class="text-red-800 hover:text-red-950 text-xs font-semibold px-2 py-1 bg-[#FDF0F2] border border-red-200 rounded-lg transition">Hapus</button>
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
                Detail Informasi Siswa
            </h4>
            <button onclick="toggleModal('detail-modal')" class="text-white/80 hover:text-white transition text-2xl">&times;</button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-6">
            <!-- Seksi Header Nama -->
            <div class="pb-3 border-b border-[#EAE1E3]">
                <h3 id="detail-nama" class="text-2xl font-bold text-[#9F5261]">Nama Lengkap Siswa</h3>
                <p id="detail-kelas" class="text-sm font-medium text-[#7A6266] mt-1">Nama Kelas</p>
            </div>
            
            <!-- Grid 2 Kolom -->
            <div class="row g-4 text-sm text-[#3D2228]">
                <!-- Kolom Kiri -->
                <div class="col-md-6 space-y-4">
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">NISN</span>
                        <span id="detail-nisn" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">NIK</span>
                        <span id="detail-nik" class="text-sm font-bold text-[#3D2228] font-mono">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Jenis Kelamin</span>
                        <span id="detail-jk" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Agama</span>
                        <span id="detail-agama" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="col-md-6 space-y-4">
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Tempat Lahir</span>
                        <span id="detail-tempat_lahir" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-1">Tanggal Lahir</span>
                        <span id="detail-tanggal_lahir" class="text-sm font-bold text-[#3D2228]">-</span>
                    </div>
                </div>

                <!-- Alamat Lengkap (Full Width) -->
                <div class="col-12">
                    <span class="block text-xs text-[#8E797D] font-bold uppercase tracking-wider mb-2">Alamat Lengkap</span>
                    <div class="bg-[#FDF4F5] border border-[#EAE1E3] rounded-xl p-3.5 shadow-sm">
                        <p id="detail-alamat" class="text-sm font-medium text-[#3D2228] m-0 whitespace-pre-wrap">-</p>
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

    function showDetailSiswa(siswa) {
        document.getElementById('detail-nama').innerText = siswa.nama;
        document.getElementById('detail-kelas').innerText = 'Kelas: ' + (siswa.kelas ? siswa.kelas.nama_kelas : '-');
        document.getElementById('detail-nisn').innerText = siswa.nisn;
        document.getElementById('detail-nik').innerText = siswa.nik;
        document.getElementById('detail-jk').innerText = siswa.jk;
        document.getElementById('detail-agama').innerText = siswa.agama;
        document.getElementById('detail-tempat_lahir').innerText = siswa.tempat_lahir;
        
        // Format tanggal lahir to local ID readable
        if (siswa.tanggal_lahir) {
            const date = new Date(siswa.tanggal_lahir);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('detail-tanggal_lahir').innerText = date.toLocaleDateString('id-ID', options);
        } else {
            document.getElementById('detail-tanggal_lahir').innerText = '-';
        }
        
        document.getElementById('detail-alamat').innerText = siswa.alamat;
        toggleModal('detail-modal');
    }
</script>
@endsection
