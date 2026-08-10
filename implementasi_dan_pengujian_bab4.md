# BAB IV IMPLEMENTASI DAN PENGUJIAN

## 4.1 Implementasi Dan Pengujian
Tahap ini merupakan bagian dari proses implementasi berdasarkan hasil analisis dan perancangan sistem yang telah disusun sebelumnya. Tujuan utama dari tahap ini adalah untuk memastikan bahwa sistem yang dikembangkan menghasilkan output sesuai dengan perancangan yang telah direncanakan. Implementasi mencakup pembuatan kode aplikasi dan penerapan desain antarmuka yang telah dirancang sebelumnya. Setelah itu, tahap pengujian akan dilakukan, yang merupakan salah satu aspek paling krusial dalam siklus pengembangan sebuah aplikasi.

### 4.1.1 Lingkungan Implementasi
Lingkungan implementasi menjelaskan tentang spesifikasi perangkat keras (*hardware*) dan perangkat lunak (*software*) yang digunakan untuk membangun serta menjalankan Sistem Informasi Manajemen Nilai & Monitoring Prestasi (SIPRESMA 28) di SDN 28 Kinali.

#### a. Perangkat Keras (Hardware)
Proses implementasi dan pengujian sistem pada penelitian ini dilakukan menggunakan perangkat keras berupa laptop dan perangkat telepon pintar dengan spesifikasi sebagai berikut:

**Tabel 4.1 Informasi Perangkat Keras**

| No | Perangkat Keras | Spesifikasi |
|----|-----------------|-------------|
| 1  | Laptop (Perangkat Pengembangan) | Lenovo ThinkPad T14 |
| 2  | Prosesor Laptop | Intel Core i5 / AMD Ryzen 5 |
| 3  | Memori Laptop | 16 GB RAM / 512 GB SSD |
| 4  | Smartphone (Perangkat Uji Responsivitas) | Samsung Galaxy A14 |
| 5  | Prosesor Smartphone | MediaTek Helio G80 |
| 6  | Memori Smartphone | 6 GB RAM / 128 GB ROM |

#### b. Perangkat Lunak (Software)
Adapun perangkat lunak yang digunakan selama proses pembuatan sistem mencakup *framework*, basis data, dan *tools* pendukung lainnya, yaitu:

**Tabel 4.2 Informasi Perangkat Lunak**

| No | Perangkat Lunak | Spesifikasi |
|----|-----------------|-------------|
| 1  | Sistem Operasi | Windows 10 / 11 64-bit |
| 2  | DBMS | MySQL |
| 3  | Server | Laragon / XAMPP (sebagai *local web server*) |
| 4  | Text Editor | Visual Studio Code (VS Code) |
| 5  | Web Browser | Google Chrome / Microsoft Edge |
| 6  | Framework | Laravel v11.x (PHP v8.2, Tailwind CSS, Blade) |
| 7  | Perancangan UML & UI | Draw.io, Figma |

---

### 4.1.2 Implementasi Antar Muka
Bagian ini menyajikan hasil implementasi antarmuka berupa tangkapan layar (*screenshot*) halaman-halaman utama aplikasi SIPRESMA 28 setelah diimplementasikan ke dalam kode program berdasarkan perancangan antarmuka:

#### 1. Halaman Landing Page
Halaman *landing page* menampilkan informasi umum sekolah, visi dan misi, statistik sekolah, profil guru, berita terbaru, peta lokasi, serta formulir kontak. Halaman ini dirancang untuk mempermudah masyarakat umum atau wali murid dalam memperoleh informasi seputar SDN 28 Kinali. Tampilan halaman *landing page* dapat dilihat pada Gambar 4.1.

![Tampilan Halaman Landing Page](/implementasi_landing_page.png)
*Gambar 4.1 Tampilan Halaman Landing Page*

#### 2. Halaman Login
Halaman *login* digunakan oleh seluruh pengguna untuk masuk ke dalam sistem sesuai hak akses masing-masing (Admin, Wali Kelas, Guru Mapel, Kepala Sekolah). Halaman ini memuat formulir input email atau *username*, kata sandi, serta tombol masuk. Tampilan halaman *login* dapat dilihat pada Gambar 4.2.

![Tampilan Halaman Login](/implementasi_login.png)
*Gambar 4.2 Tampilan Halaman Login*

#### 3. Tampilan Halaman Dashboard Admin
Halaman *dashboard* admin menampilkan data ringkasan statistik master dan jalan pintas menu pengelolaan sistem untuk administrator. Halaman ini dilengkapi dengan *leaderboard* siswa berprestasi serta bagan analitik. Tampilan halaman *dashboard* admin dapat dilihat pada Gambar 4.3.

![Tampilan Halaman Dashboard Admin](/implementasi_dashboard_admin.png)
*Gambar 4.3 Tampilan Halaman Dashboard Admin*

#### 4. Tampilan Halaman Kelola Pengguna
Halaman kelola pengguna digunakan oleh administrator untuk mengatur akun pengguna sistem. Halaman ini memuat tabel daftar pengguna lengkap dengan status keaktifan akun, filter peran, dan tombol tambah pengguna. Tampilan halaman kelola pengguna dapat dilihat pada Gambar 4.4.

![Tampilan Halaman Kelola Pengguna](/implementasi_kelola_pengguna.png)
*Gambar 4.4 Tampilan Halaman Kelola Pengguna*

#### 5. Tampilan Halaman Kelola Guru
Halaman kelola guru digunakan untuk memanajemen data master profil guru pengajar di sekolah. Halaman ini memuat informasi NIP, nama lengkap, nomor telepon, dan jabatan akademik guru pengajar. Tampilan halaman kelola guru dapat dilihat pada Gambar 4.5.

![Tampilan Halaman Kelola Guru](/implementasi_kelola_guru.png)
*Gambar 4.5 Tampilan Halaman Kelola Guru*

#### 6. Tampilan Halaman Kelola Kelas
Halaman kelola kelas digunakan oleh admin untuk mengatur pembagian kelas beserta guru yang ditunjuk sebagai wali kelas. Halaman ini menyajikan tabel nama kelas, nama wali kelas pengampu, serta NIP wali kelas terkait. Tampilan halaman kelola kelas dapat dilihat pada Gambar 4.6.

![Tampilan Halaman Kelola Kelas](/implementasi_kelola_kelas.png)
*Gambar 4.6 Tampilan Halaman Kelola Kelas*

#### 7. Tampilan Halaman Kelola Siswa
Halaman kelola siswa menampilkan data profil siswa terdaftar secara detail. Halaman ini memuat kolom NISN, nama lengkap, jenis kelamin, kelas aktif, tahun masuk, serta tombol cetak laporan PDF data siswa. Tampilan halaman kelola siswa dapat dilihat pada Gambar 4.7.

![Tampilan Halaman Kelola Siswa](/implementasi_kelola_siswa.png)
*Gambar 4.7 Tampilan Halaman Kelola Siswa*

#### 8. Tampilan Halaman Kelola Penugasan Guru
Halaman kelola penugasan guru menampilkan data pemetaan guru pengajar ke mata pelajaran dan kelas tertentu. Halaman ini dilengkapi filter penelusuran tahun ajaran, kelas, dan mapel aktif. Tampilan halaman kelola penugasan guru dapat dilihat pada Gambar 4.8.

![Tampilan Halaman Kelola Penugasan Guru](/implementasi_kelola_penugasan_guru.png)
*Gambar 4.8 Tampilan Halaman Kelola Penugasan Guru*

#### 9. Tampilan Halaman Kelola Kegiatan
Halaman kelola kegiatan digunakan oleh admin untuk mempublikasikan pengumuman, dokumentasi, atau berita sekolah. Halaman ini menampilkan daftar judul kegiatan, tanggal publikasi, ringkasan isi berita, dan foto pendukung. Tampilan halaman kelola kegiatan dapat dilihat pada Gambar 4.9.

![Tampilan Halaman Kelola Kegiatan](/implementasi_kelola_kegiatan.png)
*Gambar 4.9 Tampilan Halaman Kelola Kegiatan*

#### 10. Tampilan Halaman Tahun Ajaran
Halaman tahun ajaran menampilkan daftar tahun ajaran akademik beserta semester aktif. Halaman ini memiliki status keaktifan semester dan tombol aksi untuk mengaktifkan periode semester tertentu di sistem. Tampilan halaman tahun ajaran dapat dilihat pada Gambar 4.10.

![Tampilan Halaman Tahun Ajaran](/implementasi_tahun_ajaran.png)
*Gambar 4.10 Tampilan Halaman Tahun Ajaran*

#### 11. Tampilan Halaman Kenaikan Kelas
Halaman kenaikan kelas digunakan untuk memproses kenaikan kelas siswa secara massal pada akhir tahun ajaran. Halaman ini menampilkan dropdown kelas asal dan tujuan serta daftar nama siswa yang dilengkapi checkbox. Tampilan halaman kenaikan kelas dapat dilihat pada Gambar 4.11.

![Tampilan Halaman Kenaikan Kelas](/implementasi_kenaikan_kelas.png)
*Gambar 4.11 Tampilan Halaman Kenaikan Kelas*

#### 12. Tampilan Halaman Monitoring Prestasi
Halaman monitoring prestasi menampilkan daftar pencapaian prestasi akademik dan non-akademik siswa yang telah terinput. Halaman ini menyediakan opsi cetak rekap prestasi ke PDF dan tautan unduhan bukti fisik sertifikat. Tampilan halaman monitoring prestasi dapat dilihat pada Gambar 4.12.

![Tampilan Halaman Monitoring Prestasi](/implementasi_monitoring_prestasi.png)
*Gambar 4.12 Tampilan Halaman Monitoring Prestasi*

#### 13. Tampilan Halaman Monitoring Nilai
Halaman monitoring nilai menyajikan data rekapitulasi nilai rapor akhir seluruh siswa secara transparan. Halaman ini menampilkan rata-rata nilai siswa dan tombol cetak laporan rapor individu. Tampilan halaman monitoring nilai dapat dilihat pada Gambar 4.13.

![Tampilan Halaman Monitoring Nilai](/implementasi_monitoring_nilai.png)
*Gambar 4.13 Tampilan Halaman Monitoring Nilai*

#### 14. Tampilan Halaman Dashboard Wali Kelas
Halaman *dashboard* wali kelas menampilkan halaman sambutan guru wali kelas beserta ringkasan statistik kondisi keaktifan siswa di kelas yang bersangkutan. Tampilan halaman *dashboard* wali kelas dapat dilihat pada Gambar 4.14.

![Tampilan Halaman Dashboard Wali Kelas](/implementasi_dashboard_walas.png)
*Gambar 4.14 Tampilan Halaman Dashboard Wali Kelas*

#### 15. Tampilan Halaman Kelola Nilai
Halaman kelola nilai digunakan oleh guru untuk menginputkan dan memperbarui nilai rapor siswa secara massal. Halaman ini memuat tabel input nilai UH, UTS, UAS, nilai pengetahuan, keterampilan, serta catatan sikap. Tampilan halaman kelola nilai dapat dilihat pada Gambar 4.15.

![Tampilan Halaman Kelola Nilai](/implementasi_kelola_nilai.png)
*Gambar 4.15 Tampilan Halaman Kelola Nilai*

#### 16. Tampilan Halaman Rekap dan Ranking
Halaman rekap dan ranking menyajikan akumulasi nilai rapor akhir kelas dan pemeringkatan ranking otomatis. Halaman ini dilengkapi dengan tombol cetak rekap kelas dan rekap absensi kehadiran siswa. Tampilan halaman rekap dan ranking dapat dilihat pada Gambar 4.16.

![Tampilan Halaman Rekap dan Ranking](/implementasi_rekap_dan_ranking.png)
*Gambar 4.16 Tampilan Halaman Rekap dan Ranking*

#### 17. Tampilan Halaman Dashboard Kepala Sekolah
Halaman *dashboard* kepala sekolah menampilkan sambutan kepala sekolah dan panel ringkasan statistik sekolah beserta grafik rata-rata pencapaian rapor kelas. Tampilan halaman *dashboard* kepala sekolah dapat dilihat pada Gambar 4.17.

![Tampilan Halaman Dashboard Kepala Sekolah](/implementasi_dashboard_kepsek.png)
*Gambar 4.17 Tampilan Halaman Dashboard Kepala Sekolah*

#### 18. Tampilan Halaman Dashboard Guru Mapel
Halaman *dashboard* guru mapel menyajikan daftar kelas dan mata pelajaran yang diampu oleh guru pengajar beserta tombol pintasan untuk kelola nilai. Tampilan halaman *dashboard* guru mata pelajaran dapat dilihat pada Gambar 4.18.

![Tampilan Halaman Dashboard Guru Mapel](/implementasi_dashboard_guru_mapel.png)
*Gambar 4.18 Tampilan Halaman Dashboard Guru Mapel*

#### 19. Halaman Form Tambah Siswa
Halaman form tambah siswa digunakan oleh administrator untuk mendaftarkan profil siswa baru secara detail ke dalam database. Formulir ini memuat isian Nama Lengkap, Jenis Kelamin, NISN, NIK, Tempat Lahir, Tanggal Lahir, Agama, Pilihan Kelas, dan Alamat Lengkap siswa. Tampilan halaman form tambah siswa dapat dilihat pada Gambar 4.19.

![Tampilan Halaman Form Tambah Siswa](/implementasi_tambah_siswa.png)
*Gambar 4.19 Tampilan Halaman Form Tambah Siswa*

#### 20. Halaman Form Tambah Guru
Halaman form tambah guru digunakan oleh administrator untuk menginput profil guru pengajar baru serta mengaitkannya dengan akun login pengguna. Formulir ini terdiri dari isian Nama Lengkap, Jenis Kelamin, NIP, Jabatan/Peran, Hubungkan Akun Pengguna, dan pengunggahan berkas foto profil. Tampilan halaman form tambah guru dapat dilihat pada Gambar 4.20.

![Tampilan Halaman Form Tambah Guru](/implementasi_tambah_guru.png)
*Gambar 4.20 Tampilan Halaman Form Tambah Guru*

#### 21. Tampilan Cetak Nilai PDF Siswa
Halaman cetak nilai PDF siswa merupakan dokumen hasil cetak (*output report*) berupa berkas PDF hasil belajar (rapor) siswa secara individu. Dokumen ini memuat informasi identitas lengkap siswa, rincian perolehan nilai mata pelajaran (pengetahuan, keterampilan, dan nilai akhir), rekapitulasi tingkat kehadiran siswa, serta catatan sikap dan keputusan dari wali kelas. Tampilan hasil cetak nilai PDF siswa dapat dilihat pada Gambar 4.21.

![Tampilan Cetak Nilai PDF Siswa](/implementasi_cetak_nilai_siswa_pdf.png)
*Gambar 4.21 Tampilan Hasil Cetak Nilai PDF Siswa*

#### 22. Tampilan Cetak Rekap Nilai Per Kelas
Halaman cetak rekap nilai per kelas merupakan dokumen hasil cetak (*output report*) berupa berkas PDF laporan rekapitulasi nilai rapor seluruh siswa dalam satu kelas pengampuan. Dokumen ini menyajikan data rekapitulasi nilai rata-rata tiap siswa, total perolehan nilai, ranking kelas, dan catatan kehadiran yang disusun secara tabelaris untuk keperluan laporan kepada kepala sekolah. Tampilan hasil cetak rekap nilai per kelas dapat dilihat pada Gambar 4.22.

![Tampilan Cetak Rekap Nilai Per Kelas](/implementasi_cetak_rekap_nilai_kelas_pdf.png)
*Gambar 4.22 Tampilan Hasil Cetak Rekap Nilai Per Kelas*

---

## 4.2 Analisa Pengujian
Bagian ini memaparkan hasil pengujian fungsionalitas aplikasi SIPRESMA 28 menggunakan metode *Black Box Testing*. Seluruh fitur utama sistem dipecah secara detail ke dalam skenario pengujian fungsional CRUD (*Create*, *Read*, *Update*, *Delete*) untuk memverifikasi kesesuaian sistem dengan rancangan:

**Tabel 4.3 Hasil Pengujian Fungsionalitas Sistem (Black Box Testing)**

| No | Skenario Uji | Langkah Uji | Data Uji | Hasil Yang Diharapkan | Hasil |
|----|--------------|-------------|----------|-----------------------|-------|
| 1. | Login (Admin) | 1. Masukkan email/username<br>2. Masukkan password yang valid<br>3. Tekan *Login*. | email: admin@gmail.com<br>password: password | Sistem memverifikasi kredensial dan menavigasi ke Beranda Admin. | Sesuai |
| 2. | Login (Wali Kelas) | 1. Masukkan email/username<br>2. Masukkan password yang valid<br>3. Tekan *Login*. | email: walas@gmail.com<br>password: password | Sistem memverifikasi kredensial dan menavigasi ke Beranda Wali Kelas. | Sesuai |
| 3. | Login (Guru Mapel) | 1. Masukkan email/username<br>2. Masukkan password yang valid<br>3. Tekan *Login*. | email: gurumapel@gmail.com<br>password: password | Sistem memverifikasi kredensial dan menavigasi ke Beranda Guru Mapel. | Sesuai |
| 4. | Login (Kepala Sekolah) | 1. Masukkan email/username<br>2. Masukkan password yang valid<br>3. Tekan *Login*. | email: kepsek@gmail.com<br>password: password | Sistem memverifikasi kredensial dan menavigasi ke Beranda Kepala Sekolah. | Sesuai |
| 5. | Lihat Data Pengguna | 1. Buka menu Kelola Pengguna<br>2. Amati daftar data pengguna. | - | Sistem menampilkan daftar email, nama, username, dan peran akun. | Sesuai |
| 6. | Tambah Pengguna Baru | 1. Klik tombol "Tambah Pengguna"<br>2. Isi form lengkap data akun<br>3. Klik Simpan. | Nama: Budi, Email: budi@gmail.com,<br>Username: budi, Role: wali_kelas | Akun pengguna baru tersimpan dan tampil di tabel daftar pengguna. | Sesuai |
| 7. | Edit Data Pengguna | 1. Klik Edit pada baris pengguna<br>2. Ubah data nama / email<br>3. Klik Simpan. | Mengubah nama akun "Budi" menjadi "Budi Santoso" | Sistem memperbarui data pengguna di database dan menampilkan perubahan. | Sesuai |
| 8. | Hapus Data Pengguna | 1. Klik Hapus pada baris pengguna<br>2. Konfirmasi penghapusan. | Memilih akun Budi Santoso | Akun terhapus dari database dan tidak lagi tampil di tabel pengguna. | Sesuai |
| 9. | Ubah Status Keaktifan Akun | 1. Klik tombol status (Aktif/Nonaktif) pada baris pengguna. | Mengubah status akun menjadi "Nonaktif" | Status akun berubah dan sistem menolak akses login akun tersebut. | Sesuai |
| 10.| Lihat Data Guru | 1. Buka menu Kelola Guru<br>2. Amati daftar guru pada tabel. | - | Sistem menampilkan daftar nama, NIP, jenis kelamin, dan jabatan guru. | Sesuai |
| 11.| Tambah Data Guru | 1. Klik Tambah Guru<br>2. Isi NIP, nama, JK, no telp, jabatan<br>3. Klik Simpan. | NIP: 198205122009022003,<br>Nama: Sri Desy Lara, S.Pd., JK: P | Data guru tersimpan dan terelasi otomatis dengan akun user terkait. | Sesuai |
| 12.| Edit Data Guru | 1. Klik Edit pada baris guru<br>2. Ubah no telp / jabatan<br>3. Klik Simpan. | Mengubah no telp Sri Desy Lara, S.Pd. menjadi 081234567890 | Sistem memperbarui profil guru dan menampilkan data terbaru. | Sesuai |
| 13.| Hapus Data Guru | 1. Klik Hapus pada baris guru<br>2. Konfirmasi penghapusan. | Memilih guru Sri Desy Lara, S.Pd. | Data profil guru terhapus dari database. | Sesuai |
| 14.| Lihat Data Kelas | 1. Buka menu Kelola Kelas<br>2. Amati daftar kelas pada tabel. | - | Sistem menampilkan kelas beserta wali kelas dan NIP wali kelas. | Sesuai |
| 15.| Tambah Data Kelas | 1. Klik Tambah Kelas<br>2. Isi nama kelas & pilih wali kelas<br>3. Klik Simpan. | Nama Kelas: Kelas VI.A,<br>Wali Kelas: Jafris | Kelas baru berhasil ditambahkan dan data wali kelas ter-update. | Sesuai |
| 16.| Edit Data Kelas | 1. Klik Edit pada baris kelas<br>2. Pilih wali kelas baru<br>3. Klik Simpan. | Mengubah wali kelas VI.A menjadi Husna Indriani, S.Pd. | Data wali kelas pengampu untuk kelas tersebut berhasil diperbarui. | Sesuai |
| 17.| Hapus Data Kelas | 1. Klik Hapus pada baris kelas<br>2. Konfirmasi penghapusan. | Memilih Kelas VI.A | Data kelas terhapus dari basis data. | Sesuai |
| 18.| Lihat Data Siswa | 1. Buka menu Kelola Siswa<br>2. Amati daftar siswa di tabel. | - | Sistem menampilkan NISN, nama, kelas aktif, dan tahun masuk siswa. | Sesuai |
| 19.| Tambah Data Siswa | 1. Klik Tambah Siswa<br>2. Isi NISN, nama, JK, kelas, tahun masuk<br>3. Klik Simpan. | NISN: 1234567890,<br>Nama: Andi, Kelas: Kelas VI.A | Profil siswa berhasil terdaftar dan masuk ke dalam kelas aktif. | Sesuai |
| 20.| Edit Data Siswa | 1. Klik Edit pada baris siswa<br>2. Ubah data alamat / nama orang tua<br>3. Klik Simpan. | Mengubah alamat Andi menjadi "Kinali, Pasaman Barat" | Sistem memperbarui profil siswa dan menampilkan data ter-update. | Sesuai |
| 21.| Hapus Data Siswa | 1. Klik Hapus pada baris siswa<br>2. Konfirmasi penghapusan. | Memilih siswa Andi | Data siswa terhapus permanen dari basis data. | Sesuai |
| 22.| Lihat Riwayat Siswa | 1. Klik tombol "Lihat Riwayat" pada baris siswa terkait. | Siswa: Andi | Sistem memuat pop-up log riwayat kenaikan kelas siswa dari tahun ke tahun. | Sesuai |
| 23.| Lihat Data Mapel | 1. Buka menu Kelola Mapel<br>2. Amati daftar pelajaran pada tabel. | - | Sistem menampilkan daftar nama seluruh mata pelajaran. | Sesuai |
| 24.| Tambah Data Mapel | 1. Klik Tambah Mapel<br>2. Input nama mata pelajaran<br>3. Klik Simpan. | Nama Mapel: Pendidikan Jasmani Olahraga dan Kesehatan (PJOK) | Mata pelajaran baru berhasil tersimpan dan tampil pada daftar tabel. | Sesuai |
| 25.| Edit Data Mapel | 1. Klik Edit pada baris mapel<br>2. Ubah nama mata pelajaran<br>3. Klik Simpan. | Mengubah "PJOK" menjadi "Pendidikan Jasmani" | Nama mata pelajaran diperbarui dalam database. | Sesuai |
| 26.| Hapus Data Mapel | 1. Klik Hapus pada baris mapel<br>2. Konfirmasi penghapusan. | Memilih mata pelajaran Pendidikan Jasmani | Data mata pelajaran terhapus dari basis data. | Sesuai |
| 27.| Lihat Data Tahun Ajaran | 1. Buka menu Tahun Ajaran<br>2. Amati periode tahun ajaran aktif. | - | Sistem menampilkan tahun, semester, status aktif, dan tombol aksi. | Sesuai |
| 28.| Tambah Data Tahun Ajaran | 1. Klik Tambah Tahun Ajaran<br>2. Isi tahun & semester (ganjil/genap)<br>3. Klik Simpan. | Tahun Ajaran: 2026/2027,<br>Semester: Ganjil | Tahun ajaran baru berhasil ditambahkan ke database. | Sesuai |
| 29.| Edit Data Tahun Ajaran | 1. Klik Edit pada baris tahun ajaran<br>2. Ubah tahun akademik<br>3. Klik Simpan. | Mengubah tahun ajaran dari "2026/2027" menjadi "2027/2028" | Sistem memperbarui tahun ajaran akademik dalam basis data. | Sesuai |
| 30.| Hapus Data Tahun Ajaran | 1. Klik Hapus pada baris tahun ajaran<br>2. Konfirmasi penghapusan. | Memilih tahun ajaran 2027/2028 Ganjil | Periode tahun ajaran tersebut berhasil dihapus. | Sesuai |
| 31.| Lihat Data Penugasan Guru | 1. Buka menu Penugasan Guru<br>2. Amati tabel pemetaan mengajar. | - | Sistem menampilkan guru, kelas diajar, mapel diampu, dan tahun ajaran. | Sesuai |
| 32.| Tambah Penugasan Guru | 1. Klik Tambah Penugasan<br>2. Pilih guru, kelas, mapel, tahun ajaran<br>3. Klik Simpan. | Guru: Jafris, Kelas: Kelas VI.A,<br>Mapel: Matematika, TA: 2025/2026 | Penugasan mengajar terdaftar di semester aktif untuk penginputan nilai. | Sesuai |
| 33.| Hapus Penugasan Guru | 1. Klik Hapus pada baris penugasan<br>2. Konfirmasi penghapusan. | Memilih penugasan Jafris - Kelas VI.A | Pemetaan mengajar guru tersebut berhasil dihapus. | Sesuai |
| 34.| Lihat Data Berita & Kegiatan | 1. Buka menu Kelola Kegiatan<br>2. Amati tabel berita kegiatan. | - | Sistem menampilkan daftar berita kegiatan yang terbit. | Sesuai |
| 35.| Tambah Berita & Kegiatan | 1. Klik Tambah Kegiatan<br>2. Isi judul, tanggal, foto, isi berita<br>3. Klik Simpan. | Judul: Kunjungan Perpustakaan Keliling,<br>Tanggal: 03-08-2026, Foto: perpus.jpg | Berita kegiatan sekolah berhasil dipublikasikan ke landing page. | Sesuai |
| 36.| Edit Berita & Kegiatan | 1. Klik Edit pada baris kegiatan<br>2. Ubah judul atau isi kegiatan<br>3. Klik Simpan. | Mengubah judul menjadi "Kunjungan Mobil Perpustakaan" | Sistem memperbarui konten berita kegiatan secara publik. | Sesuai |
| 37.| Hapus Berita & Kegiatan | 1. Klik Hapus pada baris kegiatan<br>2. Konfirmasi penghapusan. | Memilih kegiatan Kunjungan Mobil Perpustakaan | Postingan berita kegiatan berhasil dihapus dari database. | Sesuai |
| 38.| Lihat Siswa Kelas Asal | 1. Buka menu Kenaikan Kelas<br>2. Pilih Kelas Asal di dropdown. | Kelas Asal: Kelas V.A | Sistem memuat daftar nama seluruh siswa aktif pada kelas tersebut. | Sesuai |
| 39.| Proses Kenaikan Kelas Massal | 1. Centang nama-nama siswa<br>2. Pilih kelas tujuan & tahun ajaran baru<br>3. Klik Kenaikan Kelas. | Kelas Tujuan: Kelas VI.A,<br>Tahun Ajaran Baru: 2026/2027 | Kelas aktif siswa ter-update secara massal dan riwayat kelas tercatat. | Sesuai |
| 40.| Tampilkan Form Input Nilai | 1. Pilih mapel dan kelas pengampu<br>2. Klik tampilkan form input. | Mapel: Matematika, Kelas: Kelas VI.A | Sistem memuat form input nilai UH, UTS, UAS, sikap seluruh siswa. | Sesuai |
| 41.| Simpan & Update Nilai Massal | 1. Input nilai rapor siswa<br>2. Klik Simpan Nilai. | Siswa Andi: UH=80, UTS=85, UAS=80 | Nilai rapor siswa berhasil tersimpan secara massal ke database. | Sesuai |
| 42.| Tampilkan Rekap & Peringkat | 1. Buka menu Rekap & Ranking<br>2. Amati daftar pemeringkatan. | Kelas: Kelas VI.A,<br>Semester: Ganjil 2025/2026 | Nilai rata-rata rapor kelas terhitung otomatis dan ranking diurutkan. | Sesuai |
| 43.| Lihat Daftar Prestasi Siswa | 1. Buka menu Monitoring Prestasi<br>2. Amati tabel prestasi siswa. | - | Sistem menampilkan daftar prestasi lengkap dengan bukti sertifikat. | Sesuai |
| 44.| Tambah/Upload Prestasi Siswa | 1. Klik Tambah Prestasi<br>2. Masukkan detail & upload sertifikat<br>3. Klik Simpan. | Siswa: Andi, Judul: Juara 1 Lomba Sains,<br>Kategori: Academic, Bukti: sertifikat.pdf | Prestasi tercatat di profil siswa dan file bukti sertifikat terunggah. | Sesuai |
| 45.| Edit Data Prestasi Siswa | 1. Klik Edit pada baris prestasi<br>2. Ubah tingkat atau judul<br>3. Klik Simpan. | Mengubah tingkat prestasi dari "Kabupaten" menjadi "Provinsi" | Perubahan data prestasi siswa berhasil disimpan di database. | Sesuai |
| 46.| Hapus Data Prestasi Siswa | 1. Klik Hapus pada baris prestasi<br>2. Konfirmasi penghapusan. | Memilih prestasi Andi | Data prestasi siswa terhapus permanen dari basis data. | Sesuai |
| 47.| Download Bukti Sertifikat | 1. Klik tombol "Unduh Bukti" pada baris prestasi siswa. | Prestasi: Juara I Lomba Matematika | Browser mendownload file sertifikat asli yang diunggah ke server. | Sesuai |
| 48.| Cetak Rapor PDF Siswa | 1. Cari nama siswa di kelas ampu<br>2. Klik tombol Cetak Rapor. | Siswa: Andi, Kelas: Kelas VI.A | Sistem men-generate dan mengunduh berkas laporan rapor PDF siswa. | Sesuai |
| 49.| Cetak Rekap Nilai Rapor | 1. Buka monitoring nilai<br>2. Klik tombol Cetak Rekap Nilai. | Kelas: Kelas VI.A | Sistem menghasilkan dan mengunduh berkas PDF rekap nilai kelas. | Sesuai |
| 50.| Cetak Rekap Prestasi Kelas | 1. Buka monitoring prestasi<br>2. Klik tombol Cetak Rekap Prestasi. | Kelas: Kelas VI.A | Sistem menghasilkan dan mengunduh berkas PDF rekap prestasi kelas. | Sesuai |
