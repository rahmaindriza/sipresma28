<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-950/70 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white border border-[#D8E6F2] rounded-3xl w-full max-w-xl shadow-2xl overflow-hidden transform transition-all">

        <!-- Modal Header -->
        <div class="flex justify-between items-center bg-[#3D5A80] px-6 py-4.5 border-b border-[#293E59]">
            <h4 class="text-base font-bold text-white flex items-center gap-2.5">
                <div class="p-1.5 bg-white/10 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span>Detail Profil Guru</span>
            </h4>
            <button onclick="toggleModal('detail-modal')" class="text-white/70 hover:text-white hover:bg-white/10 w-8 h-8 rounded-full flex items-center justify-center transition text-xl border-0 bg-transparent">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 bg-[#FAF9F5]">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">

                <!-- Sisi Kiri: Foto Guru -->
                <div class="sm:col-span-4 flex flex-col items-center justify-center text-center p-4 bg-white rounded-2xl border border-[#D8E6F2] shadow-sm">
                    <div id="detail-foto-container" class="relative rounded-2xl overflow-hidden border-2 border-[#D8E6F2] shadow-md mb-3 bg-[#EBF3FC]" style="width: 120px; height: 140px;">
                        <img id="detail-foto" src="" alt="Foto Guru" class="w-full h-full object-cover">
                        <div id="detail-foto-placeholder" class="w-full h-full text-[#3D5A80] flex items-center justify-center font-extrabold text-2xl tracking-wider">
                            G
                        </div>
                    </div>

                    <span id="detail-badge-jk" class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold bg-[#EBF3FC] text-[#3D5A80] border border-[#D8E6F2] uppercase tracking-wider">
                        Jenis Kelamin
                    </span>
                </div>

                <!-- Sisi Kanan: Card Data Lengkap -->
                <div class="sm:col-span-8 space-y-3">

                    <!-- Nama Lengkap -->
                    <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                        <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Nama Lengkap</span>
                        <h4 id="detail-nama" class="text-base font-bold text-[#2D3748]">-</h4>
                    </div>

                    <!-- NIP -->
                    <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                        <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">NIP (Nomor Induk Pegawai)</span>
                        <span id="detail-nip" class="text-xs font-bold text-[#2D3748] font-mono">-</span>
                    </div>

                    <!-- Grid 2 Kolom untuk Jabatan & JK -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                            <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Jabatan</span>
                            <span id="detail-jabatan" class="text-xs font-bold text-[#2D3748] block truncate">-</span>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#D8E6F2] shadow-xs">
                            <span class="block text-[10px] text-[#8E797D] font-extrabold uppercase tracking-wider mb-0.5">Jenis Kelamin</span>
                            <span id="detail-jk" class="text-xs font-bold text-[#2D3748] block truncate">-</span>
                        </div>
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
            placeholderEl.innerText = guru.nama ? guru.nama.substring(0, 2).toUpperCase() : 'GU';
        }

        toggleModal('detail-modal');
    }
</script>
