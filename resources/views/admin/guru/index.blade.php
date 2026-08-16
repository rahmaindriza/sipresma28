@extends('layouts.main')

@section('title', 'Kelola Guru')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-[#2D3748]">Kelola Data Master Guru</h3>
            <p class="text-xs text-[#8E797D] mt-1">Daftar guru terdaftar, NIP, jabatan, dan jenis kelamin.</p>
        </div>
        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.gurus.create') }}" class="px-4 py-2 bg-[#3D5A80] hover:bg-[#293E59] text-white font-semibold rounded-xl text-xs transition flex items-center shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Tambah Data Guru
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-panel p-4 rounded-3xl shadow-sm border border-[#D8E6F2] bg-white">
        <form action="{{ route('admin.gurus') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau jabatan..."
                    class="w-full px-4 py-2 border border-[#D8E6F2] bg-white rounded-xl text-[#2D3748] placeholder-[#8E797D] focus:outline-none transition text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-[#3D5A80] hover:bg-[#293E59] text-white font-semibold rounded-xl text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('admin.gurus') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Gurus Table -->
    <div class="bg-white border border-[#D8E6F2] rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[#2D3748]">
                <thead>
                    <tr class="bg-[#EBF3FC] border-b border-[#D8E6F2] text-xs font-bold text-[#3D5A80] uppercase tracking-wider">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6 w-24">Foto</th>
                        <th class="py-4 px-6">Nama Guru</th>
                        <th class="py-4 px-6">NIP</th>
                        <th class="py-4 px-6">Jabatan</th>
                        <th class="py-4 px-6">L/P</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8E6F2] text-sm">
                    @foreach($gurus as $g)
                    <tr class="hover:bg-[#F9FBFE] transition duration-150">
                        <td class="py-4 px-6 font-semibold text-[#8E797D]">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6">
                            @if($g->foto && file_exists(public_path('uploads/guru/' . $g->foto)))
                                <img src="{{ asset('uploads/guru/' . $g->foto) }}" alt="Foto {{ $g->nama }}" class="rounded-circle object-fit-cover shadow-sm border border-[#D8E6F2]" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-[#EBF3FC] border border-[#D8E6F2] text-[#3D5A80] d-flex align-items-center justify-content-center font-bold" style="width: 45px; height: 45px; font-size: 14px;">
                                    {{ strtoupper(substr($g->nama, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-bold text-[#2D3748]">{{ $g->nama }}</td>
                        <td class="py-4 px-6 font-semibold text-[#2D3748]">{{ $g->nip }}</td>
                        <td class="py-4 px-6 text-[#2D3748]">{{ $g->jabatan }}</td>
                        <td class="py-4 px-6">{{ $g->jk }}</td>
                        <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                            <button onclick="showDetailGuru({{ json_encode($g) }})" class="px-2.5 py-1.5 border border-[#3D5A80] text-[#3D5A80] hover:bg-[#3D5A80] hover:text-white rounded-lg text-xs font-semibold transition flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <a href="{{ route('admin.gurus.edit', $g->id) }}" class="text-[#3D5A80] hover:text-[#293E59] text-xs font-semibold px-2 py-1 transition">Edit</a>
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

@include('admin.guru.show')
@endsection
