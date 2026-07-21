@extends('layouts.main')

@section('title', 'Monitoring Prestasi Siswa Global')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[var(--border-light)] pb-4">
        <div>
            <h3 class="text-xl font-bold text-[var(--text-dark-main)]">Monitoring Prestasi Siswa Global</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Pantau prestasi seluruh siswa lintas kelas secara real-time.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-2">
            <a href="{{ route('kepsek.prestasi.cetak_rekap', request()->all()) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition flex items-center shadow-sm border-0">
                <i class="bi bi-file-pdf me-1.5"></i> Cetak Rekapitulasi PDF
            </a>
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-55 text-emerald-800 border border-emerald-250">
                Semester: {{ $selectedTa ? $selectedTa->tahun . ' (' . $selectedTa->semester . ')' : '-' }}
            </span>
        </div>
    </div>

    <!-- Search Box and Filters -->
    <div class="glass-panel p-4 rounded-2xl shadow-sm border border-[var(--border-light)] bg-white">
        <form action="{{ route('kepsek.prestasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lomba atau nama siswa..." 
                    class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
            </div>
            
            <!-- Kelas Filter -->
            <div class="w-full md:w-48">
                <select name="kelas_id" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($listKelas as $kls)
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>Kelas {{ $kls->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori Filter -->
            <div class="w-full md:w-48">
                <select name="kategori" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Akademik" {{ request('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Non-Akademik" {{ request('kategori') === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
            </div>

            <!-- Tahun Ajaran Filter -->
            <div class="w-full md:w-48">
                <select name="tahun_ajaran_id" class="w-full px-4 py-2 bg-white border border-[var(--border-light)] rounded-xl text-[var(--text-dark-main)] focus:outline-none focus:border-[var(--primary-burgundy)] transition text-sm">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTa->id == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 text-white font-semibold rounded-xl text-xs transition" style="background-color: var(--primary-burgundy) !important; border: none;">
                    Cari & Filter
                </button>
                <a href="{{ route('kepsek.prestasi.index') }}" class="w-full md:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Prestasi Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-sm bg-white border border-[var(--border-light)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="border-radius: 12px; overflow: hidden;">
                <thead>
                    <tr>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 60px;">No</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 100px;">Kelas</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5;">Nama Lomba</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Kategori</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Tingkat</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 100px;">Juara</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Tanggal</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-center" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 120px;">Bukti</th>
                        <th class="py-3.5 px-4 text-xs font-bold uppercase text-right" style="color: var(--primary-burgundy) !important; background-color: #FDF4F5; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-light)]">
                    @forelse($prestasis as $index => $p)
                    <tr class="hover:bg-[var(--accent-table-hover)] transition duration-150">
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-500">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 text-xs font-bold text-[var(--text-dark-main)]">
                            <span>{{ $p->siswa->nama }}</span>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">NISN: {{ $p->siswa->nisn }}</p>
                        </td>
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
                        <td class="py-3.5 px-4 text-xs text-center font-mono text-slate-500">
                            {{ \Carbon\Carbon::parse($p->tanggal_penghargaan)->translatedFormat('d-m-Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($p->sertifikat)
                                <a href="{{ route('prestasi.download', $p->id) }}" class="btn btn-sm px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 rounded-lg text-[10px] font-bold transition inline-flex items-center">
                                    <i class="bi bi-download me-1"></i> Unduh
                                </a>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <button onclick="showDetailPrestasi({
                                nama: '{{ addslashes($p->siswa->nama) }}',
                                nisn: '{{ $p->siswa->nisn }}',
                                kelas: '{{ $p->siswa->kelas->nama_kelas ?? '-' }}',
                                wali_kelas: '{{ addslashes($p->siswa->kelas->waliKelas->nama ?? '-') }}',
                                nama_lomba: '{{ addslashes($p->nama_lomba) }}',
                                kategori: '{{ $p->kategori }}',
                                tingkat: '{{ $p->tingkat }}',
                                juara: '{{ $p->juara }}',
                                tanggal: '{{ \Carbon\Carbon::parse($p->tanggal_penghargaan)->translatedFormat('d-m-Y') }}',
                                sertifikat: '{{ $p->sertifikat ? asset('uploads/sertifikat/' . $p->sertifikat) : '' }}'
                            })" class="text-cyan-600 hover:text-cyan-800 text-[11px] font-bold px-2.5 py-1 hover:bg-cyan-50 border border-transparent hover:border-cyan-200 rounded-lg transition inline-block">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-400">
                            <div class="fs-1 text-slate-350 mb-3"><i class="bi bi-trophy"></i></div>
                            Belum ada rekaman prestasi siswa terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/70 p-4">
    <div class="bg-white border border-[var(--border-light)] rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-6 max-h-[95vh] flex flex-col justify-between">
        <div class="flex justify-between items-center border-b pb-3">
            <h4 class="text-base font-bold text-[var(--text-dark-main)]">Detail Prestasi Siswa</h4>
            <button onclick="toggleModal('detail-modal')" class="text-slate-400 hover:text-slate-700 transition fs-4 border-0 bg-transparent">&times;</button>
        </div>
        
        <div class="flex-1 overflow-y-auto space-y-6 pr-1">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="space-y-2.5">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Lengkap Siswa</span>
                        <span id="detail-nama" class="font-bold text-[var(--text-dark-main)] text-sm"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">NISN</span>
                        <span id="detail-nisn" class="font-medium text-slate-600"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kelas</span>
                        <span id="detail-kelas" class="font-medium text-slate-600"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Wali Kelas</span>
                        <span id="detail-wali_kelas" class="font-medium text-slate-600"></span>
                    </div>
                </div>
                <div class="space-y-2.5">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Lomba / Bidang Kejuaraan</span>
                        <span id="detail-nama_lomba" class="font-bold text-[var(--text-dark-main)]"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kategori</span>
                        <span id="detail-kategori" class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-800 mt-1"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Tingkat Kejuaraan & Juara</span>
                        <span class="font-medium text-slate-700"><span id="detail-tingkat"></span> - <span id="detail-juara" class="font-bold"></span></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Tanggal Perolehan</span>
                        <span id="detail-tanggal" class="font-medium text-slate-600"></span>
                    </div>
                </div>
            </div>
            
            <!-- Certificate Preview -->
            <div class="border-t pt-4 space-y-2">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Berkas Bukti Sertifikat</span>
                <div class="flex items-center justify-center overflow-auto bg-slate-50 rounded-2xl p-2 border border-[var(--border-light)] min-h-[250px]">
                    <img id="detail-sertifikat-image" src="" alt="Sertifikat" class="img-fluid rounded-xl max-h-[40vh] object-contain hidden">
                    <iframe id="detail-sertifikat-pdf" src="" class="w-full h-[40vh] border-0 rounded-xl hidden"></iframe>
                    <div id="detail-sertifikat-empty" class="text-slate-400 italic text-xs py-10 flex flex-col items-center">
                        <i class="bi bi-file-earmark-x text-2xl mb-1.5"></i>
                        Belum ada bukti sertifikat terunggah.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end pt-3 border-t">
            <button type="button" onclick="toggleModal('detail-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border-0">Tutup</button>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
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

    function showDetailPrestasi(data) {
        document.getElementById('detail-nama').textContent = data.nama;
        document.getElementById('detail-nisn').textContent = data.nisn;
        document.getElementById('detail-kelas').textContent = data.kelas;
        document.getElementById('detail-wali_kelas').textContent = data.wali_kelas;
        document.getElementById('detail-nama_lomba').textContent = data.nama_lomba;
        document.getElementById('detail-kategori').textContent = data.kategori;
        document.getElementById('detail-tingkat').textContent = data.tingkat;
        document.getElementById('detail-juara').textContent = data.juara;
        document.getElementById('detail-tanggal').textContent = data.tanggal;

        const imgEl = document.getElementById('detail-sertifikat-image');
        const pdfEl = document.getElementById('detail-sertifikat-pdf');
        const emptyEl = document.getElementById('detail-sertifikat-empty');

        if (data.sertifikat) {
            emptyEl.classList.add('hidden');
            const isPdf = data.sertifikat.toLowerCase().endsWith('.pdf');
            if (isPdf) {
                imgEl.classList.add('hidden');
                pdfEl.classList.remove('hidden');
                pdfEl.src = data.sertifikat;
            } else {
                pdfEl.classList.add('hidden');
                imgEl.classList.remove('hidden');
                imgEl.src = data.sertifikat;
            }
        } else {
            imgEl.classList.add('hidden');
            pdfEl.classList.add('hidden');
            emptyEl.classList.remove('hidden');
        }

        toggleModal('detail-modal');
    }
</script>
@endsection
