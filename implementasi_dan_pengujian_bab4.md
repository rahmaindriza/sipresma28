# 4.3 Analisa Pengujian (Black Box Testing)

Pengujian sistem (Black Box Testing) dilakukan untuk memastikan bahwa seluruh fitur fungsional pada aplikasi Sistem Informasi Manajemen Nilai dan Monitoring Prestasi (SIPRESMA 28) berjalan sesuai dengan yang diharapkan. Pengujian mencakup seluruh level *user* mulai dari Admin, Guru Mata Pelajaran, Wali Kelas, hingga Kepala Sekolah.

Berikut adalah tabel analisa pengujian perangkat lunak secara keseluruhan:

### 4.3.1 Pengujian Modul Autentikasi & Keamanan (Login)
| No | Skenario Pengujian | Input Data / Aksi | Hasil yang Diharapkan (Expected Result) | Hasil Sebenarnya (Actual Result) | Kesimpulan |
|:---:|---|---|---|---|:---:|
| 1 | Login dengan kredensial yang benar | Memasukkan *Username* & *Password* valid | Sistem menerima login dan mengarahkan ke dashboard sesuai *role* (hak akses) pengguna. | Sesuai (Redirect sukses) | **Valid** |
| 2 | Login dengan kredensial yang salah | Memasukkan *Username* salah / *Password* salah | Muncul pesan *error* "Username atau password salah" dan tidak bisa masuk. | Sesuai | **Valid** |
| 3 | Pembatasan Hak Akses (Middleware) | User mencoba mengakses URL `/admin` tanpa hak admin | Sistem menolak akses dan memunculkan *error 403 (Unauthorized)* / kembali ke menu awal. | Sesuai | **Valid** |
| 4 | Fitur Keluar (Logout) | Klik tombol Logout | Sesi diakhiri dan pengguna diarahkan kembali ke halaman Login. | Sesuai | **Valid** |

### 4.3.2 Pengujian Modul Administrator (Admin)
| No | Skenario Pengujian | Input Data / Aksi | Hasil yang Diharapkan (Expected Result) | Hasil Sebenarnya (Actual Result) | Kesimpulan |
|:---:|---|---|---|---|:---:|
| 1 | Tambah & Aktifkan Tahun Ajaran | Input Tahun (2026/2027), Semester (Ganjil), klik tombol "Aktifkan" | Data TA tersimpan, dan status TA lain otomatis menjadi 'Nonaktif'. Sistem menggunakan TA ini secara default. | Sesuai | **Valid** |
| 2 | Kelola Pengguna (Guru/Walas/Kepsek) | Input NIP, Nama, Jenis Kelamin, dan Foto Guru | Data guru tersimpan, dan sistem secara otomatis men-generate akun pengguna (*user*) untuk login. | Sesuai | **Valid** |
| 3 | Kelola Kelas & Wali Kelas | Input Nama Kelas dan Pilih Wali Kelas | Kelas terbuat, histori riwayat wali kelas otomatis tersimpan. | Sesuai | **Valid** |
| 4 | Kelola Mata Pelajaran | Input Nama, Jenis Mapel, dan batas nilai KKM | Data mata pelajaran tersimpan ke database. | Sesuai | **Valid** |
| 5 | Tambah & Kelola Siswa | Input Biodata, NISN, NIS, & menetapkan siswa ke suatu Kelas | Siswa terdaftar pada kelas yang dipilih. | Sesuai | **Valid** |
| 6 | Kelola Penugasan Guru Mengajar | Memilih Guru, Mapel, dan Kelas yang diajarkan | Guru mendapat akses/tugas untuk mengisi nilai pada kelas terkait di dashboardnya. | Sesuai | **Valid** |
| 7 | Proses Kenaikan Kelas (Naik/Tinggal) | Centang Siswa, Pilih Status "Naik Kelas", Pilih Kelas Tujuan | Siswa dimutasikan ke kelas baru, status tahun ajaran diupdate. | Sesuai | **Valid** |
| 8 | Cetak Data Biodata Siswa | Klik tombol "Cetak PDF" pada tabel siswa | Sistem men-*generate* file PDF berisi daftar biodata siswa. | Sesuai | **Valid** |
| 9 | Monitoring & Input Prestasi (Admin) | Input data lomba, juara, dan upload sertifikat | Prestasi siswa tersimpan, poin bertambah sesuai dengan bobot juara & tingkat. | Sesuai | **Valid** |

### 4.3.3 Pengujian Modul Guru Mata Pelajaran
| No | Skenario Pengujian | Input Data / Aksi | Hasil yang Diharapkan (Expected Result) | Hasil Sebenarnya (Actual Result) | Kesimpulan |
|:---:|---|---|---|---|:---:|
| 1 | Melihat Daftar Penugasan | Mengakses menu "Input Nilai Mapel" | Menampilkan tabel mata pelajaran dan kelas yang diampunya pada semester aktif. | Sesuai | **Valid** |
| 2 | Input Nilai Harian/Ujian Siswa | Memasukkan nilai Angka (Tugas, UH, UTS, UAS) | Sistem menyimpan nilai secara asinkron (Ajax/form) dengan benar. | Sesuai | **Valid** |
| 3 | Perhitungan Nilai Akhir (Otomatis) | Mengisi keempat komponen nilai ujian & deskripsi capaian | Sistem mengkalkulasi Nilai Akhir secara otomatis serta menentukan Status Lulus KKM. | Sesuai | **Valid** |
| 4 | Edit / Ubah Nilai Siswa | Merubah angka nilai di kolom *input* | Nilai Akhir dan status langsung diperbarui mengikuti angka baru. | Sesuai | **Valid** |

### 4.3.4 Pengujian Modul Wali Kelas
| No | Skenario Pengujian | Input Data / Aksi | Hasil yang Diharapkan (Expected Result) | Hasil Sebenarnya (Actual Result) | Kesimpulan |
|:---:|---|---|---|---|:---:|
| 1 | Melihat Daftar Siswa Bimbingan | Mengakses menu Dashboard/Siswa | Menampilkan daftar siswa yang berada di kelas perwaliannya saja. | Sesuai | **Valid** |
| 2 | Monitoring Nilai Rekap Kelas | Mengakses menu "Rekap Nilai" | Menampilkan nilai-nilai yang telah di-input oleh guru mapel secara terintegrasi. | Sesuai | **Valid** |
| 3 | Input Prestasi Siswa (Validasi Kelas) | Memilih siswa di kelas lain (manipulasi) | Sistem menolak dengan *error* "Hanya dapat menambah prestasi siswa di kelas Anda". | Sesuai | **Valid** |
| 4 | Kelola Ekstrakurikuler & Absensi | Mengisi Sakit, Izin, Alpha, & Catatan Wali Kelas | Data kehadiran dan ekstrakurikuler tersimpan ke tabel `rapor_siswas`. | Sesuai | **Valid** |
| 5 | Cetak Rapor Hasil Belajar (PDF) | Mengklik tombol "Cetak PDF" di menu Rekap Nilai | Menghasilkan Dokumen Rapor resmi (PDF) dengan format lengkap (logo, kop surat, tabel capaian, ttd). | Sesuai | **Valid** |

### 4.3.5 Pengujian Modul Kepala Sekolah
| No | Skenario Pengujian | Input Data / Aksi | Hasil yang Diharapkan (Expected Result) | Hasil Sebenarnya (Actual Result) | Kesimpulan |
|:---:|---|---|---|---|:---:|
| 1 | Monitoring Seluruh Nilai Akademik | Mengakses menu Laporan Nilai | Kepala Sekolah dapat melihat grafik/statistik dan rekapitulasi nilai seluruh kelas. | Sesuai | **Valid** |
| 2 | Monitoring Prestasi (*Leaderboard*) | Mengakses menu Daftar Prestasi | Sistem menampilkan tabel prestasi dan daftar 5 siswa terbaik peraih penghargaan. | Sesuai | **Valid** |
| 3 | Cetak Seluruh Laporan Rekapitulasi | Mengklik tombol Cetak Laporan | Laporan dalam bentuk dokumen PDF / cetak ter-generate dengan rapi. | Sesuai | **Valid** |
