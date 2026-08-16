<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/70 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white border border-[#D8E6F2] rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden transform transition-all">

        <!-- Modal Header -->
        <div class="flex justify-between items-center bg-[#3D5A80] px-6 py-4 border-b border-[#293E59]">
            <h4 class="text-base font-bold text-white flex items-center gap-2.5">
                <div class="p-1.5 bg-white/10 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <span>Detail Biodata Siswa</span>
            </h4>
            <button onclick="toggleModal('detail-modal')" class="text-white/70 hover:text-white hover:bg-white/10 w-8 h-8 rounded-full flex items-center justify-center transition text-xl border-0 bg-transparent">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 bg-[#FAF9F5] space-y-5 max-h-[80vh] overflow-y-auto">

            <!-- Banner Profil Singkat -->
            <div class="p-4 bg-white rounded-2xl border border-[#D8E6F2] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 shadow-sm">
                <div>
                    <h3 id="detail-nama" class="text-lg font-extrabold text-[#3D5A80] leading-snug">Nama Lengkap Siswa</h3>
                    <p class="text-xs font-semibold text-[#8E797D] mt-0.5">SD Negeri 28 Kinali</p>
                </div>
                <div>
                    <span id="detail-kelas" class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold bg-[#EBF3FC] text-[#3D5A80] border border-[#D8E6F2]">
                        Nama Kelas
                    </span>
                </div>
            </div>

            <!-- Grid Data 2 Kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

                <!-- NIS -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">NIS (Nomor Induk Siswa)</span>
                    <span id="detail-nis" class="text-xs font-bold text-[#2D3748] font-mono">-</span>
                </div>

                <!-- NISN -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">NISN (Nasional)</span>
                    <span id="detail-nisn" class="text-xs font-bold text-[#2D3748] font-mono">-</span>
                </div>

                <!-- NIK -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">NIK Kependudukan</span>
                    <span id="detail-nik" class="text-xs font-bold text-[#2D3748] font-mono">-</span>
                </div>

                <!-- Jenis Kelamin -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Jenis Kelamin</span>
                    <span id="detail-jk" class="text-xs font-bold text-[#2D3748]">-</span>
                </div>

                <!-- Agama -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Agama</span>
                    <span id="detail-agama" class="text-xs font-bold text-[#2D3748]">-</span>
                </div>

                <!-- Tempat Lahir -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Tempat Lahir</span>
                    <span id="detail-tempat_lahir" class="text-xs font-bold text-[#2D3748]">-</span>
                </div>

                <!-- Tanggal Lahir (Full Width pada Mobile, 2 Kolom pada Layar Lebar) -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs sm:col-span-2">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Tanggal Lahir</span>
                    <span id="detail-tanggal_lahir" class="text-xs font-bold text-[#2D3748]">-</span>
                </div>

                <!-- Alamat Lengkap (Full Width) -->
                <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs sm:col-span-2">
                    <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal</span>
                    <div class="bg-[#F8FAF2] border border-[#D8E6F2] rounded-lg p-3">
                        <p id="detail-alamat" class="text-xs font-medium text-[#2D3748] m-0 leading-relaxed whitespace-pre-wrap">-</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end px-6 py-3.5 border-t border-[#D8E6F2] bg-white">
            <button type="button" onclick="toggleModal('detail-modal')" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition border border-slate-200 shadow-xs">Tutup</button>
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
        document.getElementById('detail-nis').innerText = siswa.nis || '-';
        document.getElementById('detail-nisn').innerText = siswa.nisn || '-';
        document.getElementById('detail-nik').innerText = siswa.nik || '-';
        document.getElementById('detail-jk').innerText = siswa.jk || '-';
        document.getElementById('detail-agama').innerText = siswa.agama || '-';
        document.getElementById('detail-tempat_lahir').innerText = siswa.tempat_lahir || '-';

        // Format tanggal lahir ke bahasa Indonesia yang mudah dibaca
        if (siswa.tanggal_lahir) {
            const date = new Date(siswa.tanggal_lahir);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('detail-tanggal_lahir').innerText = date.toLocaleDateString('id-ID', options);
        } else {
            document.getElementById('detail-tanggal_lahir').innerText = '-';
        }

        document.getElementById('detail-alamat').innerText = siswa.alamat || '-';
        toggleModal('detail-modal');
    }
</script>
