@extends('layouts.main')

@section('title', 'Manajemen Kenaikan Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Manajemen Kenaikan Kelas Siswa</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                Pindahkan siswa dari tingkat kelas asal ke kelas tujuan berikutnya secara massal tanpa mengubah/mempengaruhi riwayat nilai rapor dan prestasi terdahulu.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            @if(isset($activeTa))
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-250 shadow-sm">
                📌 Tahun Ajaran Aktif Target: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
            </span>
            @else
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-red-50 text-red-700 border border-red-200 shadow-sm">
                ⚠️ Tahun Ajaran Aktif Target: Belum Ada (Silakan Aktifkan TA Baru Dahulu!)
            </span>
            @endif
        </div>
    </div>

    <!-- Filter Kelas Asal & Kelas Tujuan -->
    <div class="glass-panel p-6 rounded-2xl shadow-sm bg-white border border-[var(--border-light)]">
        <form action="{{ route('admin.kenaikan-kelas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider mb-2">1. Pilih Kelas Asal</label>
                <select name="kelas_asal_id" onchange="this.form.submit()" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Pilih Kelas Asal --</option>
                    @foreach($listKelas as $kls)
                        <option value="{{ $kls->id }}" {{ $kelasAsalId == $kls->id ? 'selected' : '' }}>
                            Kelas {{ $kls->nama_kelas }} (Wali: {{ $kls->waliKelas->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider mb-2">2. Pilih Kelas Tujuan</label>
                <select name="kelas_tujuan_id" onchange="this.form.submit()" required class="w-full px-4 py-2.5 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Pilih Kelas Tujuan --</option>
                    @if($kelasAsal && (strpos($kelasAsal->nama_kelas, '6') !== false || strpos($kelasAsal->nama_kelas, 'VI') !== false))
                        <option value="lulus" {{ $kelasTujuanId === 'lulus' ? 'selected' : '' }} style="color: #245E49; font-weight: bold;">
                            🎓 Lulus / Alumni
                        </option>
                    @else
                        @foreach($listKelas as $kls)
                            @if(!isset($kelasAsalId) || $kls->id != $kelasAsalId)
                                <option value="{{ $kls->id }}" {{ $kelasTujuanId == $kls->id ? 'selected' : '' }}>
                                    Kelas {{ $kls->nama_kelas }} (Wali: {{ $kls->waliKelas->nama ?? '-' }})
                                </option>
                            @endif
                        @endforeach
                        <option value="lulus" {{ $kelasTujuanId === 'lulus' ? 'selected' : '' }} style="color: #245E49; font-weight: bold;">
                            🎓 Lulus / Alumni
                        </option>
                    @endif
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full px-4 py-2.5 text-white font-semibold rounded-xl text-xs transition flex items-center justify-center gap-1.5" style="background-color: var(--primary-burgundy) !important; border: none;">
                    <i class="bi bi-funnel"></i> Tampilkan Siswa
                </button>
                <a href="{{ route('admin.kenaikan-kelas.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center border border-slate-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Student List for Promotion -->
    @if($kelasAsalId)
        <div class="glass-panel rounded-2xl overflow-hidden shadow-sm bg-white border border-[var(--border-light)]">
            <form action="{{ route('admin.kenaikan-kelas.store') }}" method="POST" id="promotion-form">
                @csrf
                <input type="hidden" name="kelas_asal_id" value="{{ $kelasAsalId }}">
                <input type="hidden" name="kelas_tujuan_id" value="{{ $kelasTujuanId }}">

                <div class="p-4 border-b border-[var(--border-light)] bg-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h4 class="text-sm font-bold text-[var(--text-dark-main)]">Daftar Siswa Kelas Asal</h4>
                        <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Centang siswa yang layak atau naik kelas untuk dipindahkan.</p>
                    </div>
                    @if($students->isNotEmpty() && $kelasTujuanId)
                        @if(isset($activeTa))
                            <button type="submit" onclick="return confirmPromotion(event)" class="px-4 py-2 text-white font-semibold rounded-xl text-xs transition flex items-center gap-1.5 shadow-sm border-0 cursor-pointer" style="background-color: #245E49 !important; box-shadow: 0 4px 10px rgba(36, 94, 73, 0.2);">
                                <i class="bi bi-check2-all"></i> Proses Kenaikan Kelas Selected
                            </button>
                        @else
                            <button type="button" disabled class="px-4 py-2 bg-slate-200 text-slate-400 font-semibold rounded-xl text-xs border-0 cursor-not-allowed" title="Aktifkan Tahun Ajaran terlebih dahulu">
                                ⚠️ Proses Kenaikan Kelas Dinonaktifkan
                            </button>
                        @endif
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FDF4F5] border-b border-[var(--border-light)] text-[10px] font-bold text-[#9F5261] uppercase tracking-wider">
                                <th class="py-3.5 px-4 w-12 text-center">
                                    <input type="checkbox" id="select-all" class="rounded border-slate-350 text-[var(--primary-burgundy)] focus:ring-[var(--primary-burgundy)] cursor-pointer">
                                </th>
                                <th class="py-3.5 px-4 w-16 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Siswa</th>
                                <th class="py-3.5 px-4">NISN</th>
                                <th class="py-3.5 px-4">Jenis Kelamin</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-light)] text-xs text-slate-700">
                            @forelse($students as $idx => $student)
                                <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                                    <td class="py-3.5 px-4 text-center">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $student->id }}" class="siswa-checkbox rounded border-slate-350 text-[var(--primary-burgundy)] focus:ring-[var(--primary-burgundy)] cursor-pointer">
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-semibold text-slate-500">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4 font-bold text-[var(--text-dark-main)]">{{ $student->nama }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $student->nisn }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-500">{{ $student->jk }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border bg-green-50 text-green-700 border-green-200">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 italic">
                                        <i class="bi bi-people-fill fs-2 block mb-2 text-slate-350"></i>
                                        Tidak ada siswa berstatus Aktif di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    @else
        <div class="p-8 text-center text-slate-400 bg-white border border-[var(--border-light)] rounded-2xl shadow-sm">
            <i class="bi bi-info-circle fs-2 text-[var(--primary-burgundy)] mb-2 block"></i>
            Silakan pilih <strong>Kelas Asal</strong> terlebih dahulu untuk memuat daftar siswa.
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                siswaCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
            });
        }
    });

    function confirmPromotion(e) {
        e.preventDefault();

        const selectedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        if (selectedCount === 0) {
            alert('Silakan pilih minimal satu siswa terlebih dahulu.');
            return false;
        }

        const kelasTujuanSelect = document.querySelector('select[name="kelas_tujuan_id"]');
        const kelasTujuanText = kelasTujuanSelect.options[kelasTujuanSelect.selectedIndex].text.trim();

        if (!kelasTujuanSelect.value) {
            alert('Silakan pilih Kelas Tujuan terlebih dahulu.');
            return false;
        }

        if (confirm(`Apakah Anda yakin ingin memproses kenaikan kelas untuk ${selectedCount} siswa terpilih ke: ${kelasTujuanText}?`)) {
            document.getElementById('promotion-form').submit();
        }
    }
</script>
@endsection
