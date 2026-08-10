# 1.1 Perancangan Antarmuka (Interface)

Perancangan antarmuka (*interface*) bertujuan untuk memberikan gambaran visual mengenai tata letak komponen, alur navigasi, dan interaksi pengguna sebelum sistem diimplementasikan ke dalam kode program. Desain antarmuka ini dibuat dalam bentuk *wireframe* dengan skema warna putih-abu (*low-fidelity*) untuk berfokus pada struktur informasi dan kemudahan penggunaan (*usability*). Berikut adalah rincian rancangan antarmuka dari Sistem Informasi Manajemen Nilai & Monitoring Prestasi (SIPRESMA 28):

### 1. Tampilan Halaman Landing Page
Halaman *landing page* dirancang sebagai beranda utama yang diakses oleh pengguna umum (publik) sebelum melakukan autentikasi ke dalam sistem. Rancangan antarmuka ini terdiri dari *header* navigasi di bagian atas yang berisi logo "SIPRESMA 28", tautan menu (Tentang, Profil, Informasi, Kontak), serta tombol "Login". Konten utama (*hero section*) menampilkan judul besar sistem, deskripsi singkat, dan dua tombol aksi cepat yaitu "Detail Rapor" dan "Profil Sekolah". Di bawahnya terdapat baris kartu statistik sekolah yang menampilkan total data berupa Siswa Aktif, Guru & Staf, Mata Pelajaran, dan Prestasi Terdaftar. Bagian selanjutnya memuat penjelasan Visi & Misi Utama Sekolah yang disandingkan dengan *placeholder* gambar dokumentasi, diikuti oleh area Arah & Strategi sekolah dalam bentuk tiga kartu informasi (Monitoring Prestasi, Budaya Sekolah, dan Mutu Akademik). Terdapat pula daftar nama Guru & Staf Sekolah dengan foto berbentuk lingkaran, serta kolom Berita & Kegiatan Terbaru yang disusun secara grid dengan gambar pendukung. Pada bagian akhir halaman, terdapat peta lokasi sekolah di sebelah kiri dan formulir Kirim Pesan di sebelah kanan, serta diakhiri dengan bagian *footer* informasi hak cipta. Tampilan halaman *landing page* dapat dilihat pada Gambar 3.1.

![Tampilan Halaman Landing Page](wireframe_landing_page.png)
*Gambar 3.1 Tampilan Halaman Landing Page*

---

### 2. Tampilan Halaman Login
Halaman *login* dirancang sebagai antarmuka pintu masuk yang digunakan oleh seluruh aktor (Admin, Guru Mata Pelajaran, Wali Kelas, Kepala Sekolah) untuk mengakses sistem secara aman sesuai dengan peran masing-masing. Rancangan antarmuka ini berada di tengah layar secara terpusat (*centered layout*) yang memuat logo sistem "SIPRESMA 28" dan subjudul sambutan. Formulir input terdiri dari dua kolom pengisian utama, yaitu kolom email atau *username* dan kolom kata sandi (*password*). Terdapat juga opsi "Ingat Saya" (*Remember Me*) untuk kemudahan akses dan tautan "Lupa Password" jika pengguna kehilangan kredensial login. Di bagian bawah formulir terdapat tombol "Masuk" (*Login*) dengan ukuran lebar penuh untuk memproses data autentikasi pengguna ke halaman *dashboard* masing-masing *role*. Tampilan rancangan halaman *login* dapat dilihat pada Gambar 3.2.

![Tampilan Halaman Login](wireframe_login.png)
*Gambar 3.2 Tampilan Halaman Login*

---

### 3. Tampilan Halaman Dashboard Admin
Halaman *dashboard* admin dirancang sebagai beranda utama bagi pengguna dengan hak akses Administrator setelah berhasil melakukan autentikasi login. Rancangan antarmuka ini menggunakan tata letak *sidebar* di sebelah kiri untuk navigasi menu utama dan panel konten utama di sebelah kanan. Pada panel konten utama, terdapat pesan sambutan hangat untuk Administrator diikuti penjelasan singkat tugas admin. Di bawahnya terdapat baris kartu statistik data master yang mencakup total Pengguna, Guru, Siswa, Kelas, dan Mata Pelajaran. Terdapat pula pintasan cepat (*Quick Access*) berupa kartu navigasi interaktif menuju modul Manajemen Pengguna, Data Akademik, Penugasan Guru, dan Prestasi Sekolah. Di bagian bawah, antarmuka ini menampilkan *leaderboard* "Top 5 Siswa Berprestasi" berdasarkan akumulasi sertifikat penghargaan yang diraih serta visualisasi analitik dalam bentuk diagram. Tampilan halaman *dashboard* admin dapat dilihat pada Gambar 3.3.

![Tampilan Halaman Dashboard Admin](wireframe_dashboard_admin.png)
*Gambar 3.3 Tampilan Halaman Dashboard Admin*

---

### 4. Tampilan Halaman Kelola Pengguna
Halaman kelola pengguna dirancang bagi Administrator untuk memanajemen akun pengguna sistem. Antarmuka halaman ini memuat judul modul "Kelola Akun Pengguna" dengan deskripsi fungsionalitas dan tombol "Tambah Pengguna" di sebelah kanan atas. Di bawah judul, terdapat panel pencarian dan filter yang terdiri dari kolom input pencarian teks (nama, email, username) dan dropdown pilihan peran (Admin, Guru Mata Pelajaran, Wali Kelas, Kepala Sekolah), dilengkapi tombol "Cari & Filter" dan "Reset". Bagian utama halaman diisi oleh tabel data pengguna dengan kolom nomor, nama, email, *username*, kata sandi (*password plain* untuk kemudahan kelola internal), *role*, indikator status akun (aktif/nonaktif), dan kolom aksi (Edit, Hapus, dan tombol pengubah status aktif/nonaktif). Untuk menambah atau mengedit pengguna, disediakan tampilan modal *pop-up* formulir input data akun. Tampilan halaman kelola pengguna dapat dilihat pada Gambar 3.4.

![Tampilan Halaman Kelola Pengguna](wireframe_kelola_pengguna.png)
*Gambar 3.4 Tampilan Halaman Kelola Pengguna*

---

### 5. Tampilan Halaman Kelola Guru
Halaman kelola guru dirancang untuk memanajemen data profil guru pengajar di sekolah. Rancangan antarmuka terdiri dari judul halaman "Kelola Data Master Guru", deskripsi modul, dan tombol aksi "Tambah Guru" yang mengarahkan ke form pembuatan data baru. Di bawahnya terdapat kolom pencarian untuk memfilter daftar guru berdasarkan nama atau NIP. Konten utama berupa tabel data yang menampilkan kolom nomor urut, NIP, Nama Guru, Jenis Kelamin, Nomor Telepon, Jabatan (misalnya Kepala Sekolah, Wali Kelas, atau Guru Mapel), serta kolom Aksi yang berisi tombol "Edit" dan "Hapus". Halaman pembuatan dan pengeditan guru dirancang secara terpisah dengan formulir input terperinci yang mencakup data NIP, nama lengkap, jenis kelamin, tempat lahir, tanggal lahir, nomor telepon, alamat rumah, dan jabatan akademik guru. Tampilan halaman kelola guru dapat dilihat pada Gambar 3.5.

![Tampilan Halaman Kelola Guru](wireframe_kelola_guru.png)
*Gambar 3.5 Tampilan Halaman Kelola Guru*

---

### 6. Tampilan Halaman Kelola Kelas
Halaman kelola kelas dirancang bagi admin untuk mengatur pembagian kelas dan menetapkan guru yang bertugas sebagai wali kelas. Antarmuka ini menampilkan judul halaman "Kelola Data Master Kelas" dan tombol "Tambah Kelas" di sebelah kanan atas. Konten utama disajikan dalam bentuk tabel data kelas yang memuat kolom nomor, Nama Kelas (misalnya Kelas I.A, Kelas VI.B), nama Wali Kelas yang ditunjuk (jika belum ada akan menampilkan keterangan "Belum ditentukan" dengan format miring), NIP Wali Kelas, dan kolom Aksi (Edit, Hapus). Jika tombol tambah atau edit diklik, antarmuka akan menampilkan modal *pop-up* berisi formulir input nama kelas dan pilihan dropdown daftar guru yang tersedia untuk dijadikan wali kelas. Tampilan halaman kelola kelas dapat dilihat pada Gambar 3.6.

![Tampilan Halaman Kelola Kelas](wireframe_kelola_kelas.png)
*Gambar 3.6 Tampilan Halaman Kelola Kelas*

---

### 7. Tampilan Halaman Kelola Siswa
Halaman kelola siswa dirancang untuk mengelola seluruh data profil siswa terdaftar di sekolah. Rancangan antarmuka memuat judul modul, deskripsi fungsionalitas, tombol "Tambah Siswa", dan tombol "Cetak Data Siswa" untuk mencetak laporan siswa ke format PDF. Di bawah bagian judul terdapat area pencarian nama siswa atau NISN, serta dropdown filter kelas untuk membatasi tampilan data. Tabel data siswa menampilkan kolom nomor, NISN, Nama Siswa, Jenis Kelamin, Kelas Aktif saat ini, Tahun Masuk sekolah, dan kolom Aksi yang berisi tombol "Edit", "Hapus", dan "Lihat Riwayat" untuk melacak riwayat kenaikan kelas siswa tersebut. Halaman form tambah dan edit siswa dirancang terpisah dengan isian lengkap meliputi NISN, nama lengkap, jenis kelamin, tempat/tanggal lahir, nama orang tua/wali, nomor telepon orang tua, alamat, kelas awal masuk, tahun masuk, dan status keaktifan siswa. Tampilan halaman kelola siswa dapat dilihat pada Gambar 3.7.

![Tampilan Halaman Kelola Siswa](wireframe_kelola_siswa.png)
*Gambar 3.7 Tampilan Halaman Kelola Siswa*

---

### 8. Tampilan Halaman Kelola Penugasan Guru
Halaman kelola penugasan guru dirancang untuk memetakan guru pengajar pada mata pelajaran dan kelas tertentu untuk tahun ajaran aktif. Rancangan antarmuka ini menampilkan judul modul "Kelola Penugasan Mengajar Guru" beserta tombol "Tambah Penugasan". Di bawahnya terdapat panel pencarian dan filter yang lengkap, mencakup pencarian nama guru, dropdown pilihan Tahun Ajaran, dropdown pilihan Kelas, dan dropdown pilihan Mata Pelajaran, serta tombol "Cari & Filter" dan "Reset". Bagian bawah menampilkan tabel penugasan dengan kolom nomor, Guru Pengajar, Kelas, Mata Pelajaran, Tahun Ajaran aktif, dan kolom Aksi (Hapus Penugasan). Halaman ini juga memiliki modal *pop-up* tambah penugasan dengan input dropdown untuk memilih guru, kelas, mata pelajaran, dan tahun ajaran secara dinamis. Tampilan halaman kelola penugasan guru dapat dilihat pada Gambar 3.8.

![Tampilan Halaman Kelola Penugasan Guru](wireframe_kelola_penugasan_guru.png)
*Gambar 3.8 Tampilan Halaman Kelola Penugasan Guru*

---

### 9. Tampilan Halaman Kelola Kegiatan
Halaman kelola kegiatan dirancang bagi administrator untuk mempublikasikan berita, pengumuman, atau dokumentasi kegiatan sekolah yang nantinya tampil di landing page. Antarmuka halaman ini memuat judul modul "Kelola Berita & Kegiatan Sekolah" dan tombol "Tambah Kegiatan". Konten utama ditampilkan dalam bentuk tabel yang memuat kolom nomor, judul kegiatan, tanggal pelaksanaan kegiatan, nama admin penulis, deskripsi singkat berita, preview foto/gambar kegiatan, dan kolom Aksi (Edit, Hapus). Modul ini juga dilengkapi form tambah/edit kegiatan dengan input teks judul, kalender input tanggal, area upload file gambar dokumentasi, serta teks editor (textarea) untuk menulis isi berita secara detail. Tampilan halaman kelola kegiatan dapat dilihat pada Gambar 3.9.

![Tampilan Halaman Kelola Kegiatan](wireframe_kelola_kegiatan.png)
*Gambar 3.9 Tampilan Halaman Kelola Kegiatan*

---

### 10. Tampilan Halaman Tahun Ajaran
Halaman kelola tahun ajaran dirancang untuk mengatur periode aktif akademik sekolah (tahun ajaran dan semester). Antarmuka halaman terdiri dari judul modul, penjelasan, serta tombol "Tambah Tahun Ajaran". Tabel data di bawahnya menampilkan daftar tahun ajaran dengan kolom nomor, Tahun Ajaran (misal 2025/2026), Semester (Ganjil/Genap), status keaktifan tahun ajaran (ditandai dengan label "Aktif" berwarna hijau atau "Nonaktif" berwarna merah), serta kolom Aksi untuk mengubah data, menghapus, atau menetapkan tahun ajaran tersebut sebagai semester aktif secara sistem. Formulir modal tambah/edit tahun ajaran menyediakan input teks untuk tahun ajaran dan dropdown pilihan semester. Tampilan halaman tahun ajaran dapat dilihat pada Gambar 3.10.

![Tampilan Halaman Tahun Ajaran](wireframe_tahun_ajaran.png)
*Gambar 3.10 Tampilan Halaman Tahun Ajaran*

---

### 11. Tampilan Halaman Kenaikan Kelas
Halaman kenaikan kelas dirancang untuk memfasilitasi proses kenaikan kelas siswa secara massal (*batch update*) pada pergantian tahun ajaran. Rancangan antarmuka ini terdiri dari judul modul "Proses Kenaikan Kelas Siswa" dan panduan operasional. Di bawah judul, terdapat dropdown pemilihan kelas asal, kelas tujuan kenaikan, dan tahun ajaran baru yang ditargetkan. Setelah kelas asal dipilih, sistem akan menampilkan tabel daftar siswa kelas tersebut lengkap dengan kolom nomor, NISN, nama siswa, dan kolom checkbox pilihan di sebelah kiri setiap baris siswa. Terdapat checkbox "Pilih Semua" di header tabel untuk mempercepat pemilihan. Di bagian bawah tabel, dirancang tombol "Proses Kenaikan Kelas" untuk memindahkan seluruh siswa yang dipilih ke kelas baru secara serentak. Tampilan halaman kenaikan kelas dapat dilihat pada Gambar 3.11.

![Tampilan Halaman Kenaikan Kelas](wireframe_kenaikan_kelas.png)
*Gambar 3.11 Tampilan Halaman Kenaikan Kelas*

---

### 12. Tampilan Halaman Monitoring Prestasi
Halaman monitoring prestasi dirancang untuk melacak dan memantau seluruh pencapaian prestasi akademik dan non-akademik siswa yang telah diinput. Antarmuka ini dapat diakses oleh Admin maupun Kepala Sekolah. Rancangan antarmuka memuat filter pencarian siswa (berdasarkan nama atau NISN), kategori prestasi (akademik/non-akademik), dan filter tahun ajaran, serta tombol "Cetak Rekap PDF" untuk mencetak laporan rekapitulasi prestasi. Konten disajikan dalam tabel data dengan kolom nomor, NISN, nama siswa, kelas, judul prestasi, kategori, tingkat prestasi (kecamatan, kabupaten, provinsi, nasional, internasional), tanggal perolehan, berkas bukti sertifikat (dalam bentuk tombol unduh bukti), dan aksi cetak sertifikat individu. Tampilan halaman monitoring prestasi dapat dilihat pada Gambar 3.12.

![Tampilan Halaman Monitoring Prestasi](wireframe_monitoring_prestasi.png)
*Gambar 3.12 Tampilan Halaman Monitoring Prestasi*

---

### 13. Tampilan Halaman Monitoring Nilai
Halaman monitoring nilai dirancang bagi pihak manajemen sekolah (Admin dan Kepala Sekolah) untuk memantau rekap nilai rapor seluruh siswa secara transparan. Rancangan antarmuka ini menyediakan dropdown pilihan kelas dan tahun ajaran untuk memfilter data nilai yang ingin ditampilkan, serta tombol "Cetak Rekap Nilai" dalam format PDF. Tabel utama menampilkan kolom nomor, NISN, nama siswa, rata-rata nilai aspek pengetahuan, rata-rata nilai aspek keterampilan, total nilai keseluruhan, peringkat kelas/keterangan kelulusan, dan kolom Aksi yang menyediakan tombol "Lihat Detail Rapor" serta "Cetak Rapor PDF" individu siswa. Tampilan halaman monitoring nilai dapat dilihat pada Gambar 3.13.

![Tampilan Halaman Monitoring Nilai](wireframe_monitoring_nilai.png)
*Gambar 3.13 Tampilan Halaman Monitoring Nilai*

---

### 14. Tampilan Halaman Dashboard Wali Kelas
Halaman *dashboard* wali kelas dirancang sebagai beranda utama bagi guru yang mendapatkan tugas tambahan sebagai wali kelas dari suatu kelas aktif. Antarmuka menggunakan *sidebar* navigasi khusus wali kelas dan panel konten di sebelah kanan. Pada panel konten utama, disajikan pesan sambutan hangat yang menyebutkan nama wali kelas dan kelas yang diampu (misalnya: Wali Kelas VI.A). Di bawahnya terdapat panel informasi statistik ringkas dalam bentuk kartu, seperti jumlah siswa aktif di kelas tersebut, jumlah siswa laki-laki, jumlah siswa perempuan, jumlah siswa berprestasi di kelasnya, dan indikator persentase nilai yang sudah diinput. Halaman ini memberikan ringkasan status akademik kelas sebelum wali kelas masuk ke menu pengelolaan lebih lanjut. Tampilan halaman *dashboard* wali kelas dapat dilihat pada Gambar 3.14.

![Tampilan Halaman Dashboard Wali Kelas](wireframe_dashboard_walas.png)
*Gambar 3.14 Tampilan Halaman Dashboard Wali Kelas*

---

### 15. Tampilan Halaman Kelola Nilai
Halaman kelola nilai dirancang bagi wali kelas maupun guru mata pelajaran untuk melakukan pengisian dan pembaruan nilai rapor siswa. Rancangan antarmuka memuat informasi identitas kelas, mata pelajaran yang diampu, semester, dan tahun ajaran aktif. Di bawahnya terdapat tabel daftar siswa di kelas tersebut dengan kolom nomor, NISN, nama siswa, kolom input numerik untuk Nilai Harian (UH), Nilai Ujian Tengah Semester (UTS), Nilai Ujian Akhir Semester (UAS), Nilai Akhir Pengetahuan, Nilai Akhir Keterampilan, serta kolom input teks catatan sikap/keterangan siswa. Di bagian bawah tabel, dirancang tombol "Simpan Nilai" untuk menyimpan seluruh data nilai siswa secara massal ke dalam database. Tampilan halaman kelola nilai dapat dilihat pada Gambar 3.15.

![Tampilan Halaman Kelola Nilai](wireframe_kelola_nilai.png)
*Gambar 3.15 Tampilan Halaman Kelola Nilai*

---

### 16. Tampilan Halaman Rekap dan Ranking
Halaman rekap dan ranking dirancang khusus untuk wali kelas guna melihat akumulasi nilai rapor akhir kelasnya dan menentukan peringkat siswa secara otomatis. Antarmuka ini memuat judul halaman "Rekap Nilai dan Ranking Kelas" diikuti oleh tombol unduh PDF rekapitulasi kelas. Tabel utama menyajikan kolom nomor, peringkat (*ranking*) yang diurutkan secara otomatis dari total nilai tertinggi, NISN, nama siswa, nilai total rapor, rata-rata nilai, jumlah ketidakhadiran (Sakit, Izin, Tanpa Keterangan), keputusan kelulusan atau kenaikan kelas, dan aksi untuk mencetak laporan rapor individu. Tampilan halaman rekap dan ranking dapat dilihat pada Gambar 3.16.

![Tampilan Halaman Rekap dan Ranking](wireframe_rekap_dan_ranking.png)
*Gambar 3.16 Tampilan Halaman Rekap dan Ranking*

---

### 17. Tampilan Halaman Dashboard Kepala Sekolah
Halaman *dashboard* kepala sekolah dirancang sebagai panel pemantauan utama bagi Kepala Sekolah untuk mengawasi perkembangan akademik dan prestasi sekolah secara keseluruhan. Antarmuka ini menggunakan *sidebar* navigasi yang berisi menu Dashboard, Monitoring Nilai, dan Monitoring Prestasi. Di panel konten utama, disajikan ucapan selamat datang untuk Kepala Sekolah beserta *widget* statistik sekolah meliputi total siswa terdaftar, total guru pengajar, total kelas aktif, dan total prestasi sekolah yang diraih pada tahun ajaran aktif. Di bagian bawah *dashboard*, ditampilkan bagan grafik rata-rata pencapaian nilai rapor antar kelas serta daftar prestasi terbaru sekolah untuk membantu Kepala Sekolah melakukan evaluasi kinerja akademik. Tampilan halaman *dashboard* kepala sekolah dapat dilihat pada Gambar 3.17.

![Tampilan Halaman Dashboard Kepala Sekolah](wireframe_dashboard_kepsek.png)
*Gambar 3.17 Tampilan Halaman Dashboard Kepala Sekolah*

---

### 18. Tampilan Halaman Dashboard Guru Mapel
Halaman *dashboard* guru mapel dirancang sebagai beranda utama bagi guru pengajar mata pelajaran non-wali kelas setelah melakukan autentikasi login. Antarmuka menggunakan *layout sidebar* yang menyajikan menu Dashboard Guru dan Kelola Nilai Mapel. Pada panel konten utama, ditampilkan pesan sambutan selamat datang untuk guru mapel yang bersangkutan beserta daftar kelas dan mata pelajaran yang menjadi tanggung jawab mengajarnya pada semester aktif. Terdapat kartu informasi ringkas yang menampilkan jumlah kelas yang diajar, total siswa yang diampu, dan jumlah mata pelajaran yang diajarkan, serta tombol akses cepat di setiap baris jadwal mengajar untuk langsung menuju ke halaman penginputan nilai mapel terkait. Tampilan halaman *dashboard* guru mata pelajaran dapat dilihat pada Gambar 3.18.

![Tampilan Halaman Dashboard Guru Mapel](wireframe_dashboard_guru_mapel.png)
*Gambar 3.18 Tampilan Halaman Dashboard Guru Mapel*
