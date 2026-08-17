@extends('layouts.main')

@section('title', 'Kelola Siswa')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-[#2D3748]">Kelola Data Master Siswa</h3>
            <p class="text-xs text-[#8E797D] mt-1">Daftar siswa terdaftar, NISN, jenis kelamin, dan kelas.</p>
        </div>
        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.siswas.cetak', ['kelas_id' => request('kelas_id'), 'search' => request('search')]) }}" target="_blank" style="color:#FFFFFF !important;" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-lg shadow-emerald-950/20">
                <svg class="w-4 h-4 mr-1.5" style="color:#FFFFFF !important; stroke:#FFFFFF !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span style="color:#FFFFFF !important;">Cetak Data Siswa</span>
            </a>
            <a href="{{ route('admin.siswas.create') }}" class="px-4 py-2 bg-[#3D5A80] hover:bg-[#293E59] text-white font-semibold rounded-xl text-xs transition flex items-center shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Tambah Data Siswa
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-sm border border-[#D8E6F2] bg-white">
        <form action="{{ route('admin.siswas') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau NISN..."
                    class="w-full px-4 py-2 border border-[#D8E6F2] bg-white rounded-xl text-[#2D3748] placeholder-[#8E797D] focus:outline-none transition text-sm" placeholder="Cari nama, NIK, NIS, atau NISN...">
            </div>
            <div class="w-full md:w-56">
                <select name="kelas_id" class="w-full px-4 py-2 border border-[#D8E6F2] bg-white rounded-xl text-[#2D3748] focus:outline-none transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-[#3D5A80] hover:bg-[#293E59] text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.siswas') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Siswas Table -->
    <div class="bg-white border border-[#D8E6F2] rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[#2D3748]">
                <thead>
                    <tr class="bg-[#EBF3FC] border-b border-[#D8E6F2] text-xs font-bold text-[#3D5A80] uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6">NIS / NISN</th>
                        <th class="py-4 px-6">JK</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8E6F2] text-sm">
                    @foreach($siswas as $s)
                    <tr class="hover:bg-[#F9FBFE] transition duration-150">
                        <td class="py-4 px-6 font-semibold text-[#8E797D]">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#2D3748]">{{ $s->nama }}</span>

                            </div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-700">{{ $s->nis ?? '-' }} / {{ $s->nisn }}</td>
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
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#EBF3FC] text-[#3D5A80] border border-[#D8E6F2]">
                                    {{ $s->kelas->nama_kelas ?? '-' }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                            <button onclick="showDetailSiswa({{ json_encode($s) }})" class="px-2.5 py-1.5 border border-[#3D5A80] text-[#3D5A80] hover:bg-[#3D5A80] hover:text-white rounded-lg text-xs font-semibold transition flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <a href="{{ route('admin.siswas.edit', $s->id) }}" class="text-[#3D5A80] hover:text-[#293E59] text-xs font-semibold px-2 py-1 transition">Edit</a>
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

@include('admin.siswa.show')
@endsection
