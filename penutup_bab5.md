# BAB V
# PENUTUP

## 5.1 Kesimpulan

Berdasarkan hasil analisis, perancangan, implementasi, dan pengujian yang telah dilakukan pada **Sistem Informasi Manajemen Nilai dan Monitoring Prestasi Siswa Berbasis Web pada SD Negeri 28 Kinali (SIPRESMA 28)**, maka dapat ditarik beberapa kesimpulan sebagai berikut:

1. **Integrasi dan Sentralisasi Data**: Berhasil dirancang dan dibangun sistem informasi berbasis web yang mampu mengintegrasikan data akademik (nilai harian, UTS, UAS, dan nilai rapor) serta data prestasi siswa dari berbagai tingkatan kelas ke dalam satu basis data terpusat menggunakan MySQL. Hal ini mengatasi kendala pengolahan data konvensional (terpisah menggunakan Microsoft Excel) dan meminimalisir risiko kehilangan data atau redudansi data.
2. **Kemudahan Monitoring Prestasi & Nilai**: Sistem ini berhasil menyajikan visualisasi data berupa grafik perkembangan nilai rata-rata kelas, grafik sebaran ketuntasan KKM kelas, serta rekapitulasi data prestasi siswa (akademik dan non-akademik) secara interaktif. Hal ini mempermudah guru, wali kelas, maupun Kepala Sekolah dalam memantau dan menganalisis rekam jejak perkembangan kompetensi serta pencapaian prestasi siswa dari waktu ke waktu secara *real-time*.
3. **Detektor KKM dan Evaluasi Cepat**: Fitur detektor KKM otomatis yang ditanamkan pada sistem berhasil memberikan penanda berupa *alert* (notifikasi) bagi siswa yang memperoleh nilai akhir di bawah ambang batas KKM sekolah (nilai 75). Fitur ini terbukti mempermudah guru dan wali kelas dalam mengidentifikasi siswa yang membutuhkan program remedial atau bantuan belajar tambahan secara cepat dan tepat sasaran.
4. **Efisiensi Administrasi & Pelaporan**: Pembangunan sistem dengan hak akses terbagi menjadi 4 aktor (*role-based access control*: Admin, Wali Kelas, Guru Mata Pelajaran, dan Kepala Sekolah) telah mempermudah kolaborasi pengolahan nilai. Proses kalkulasi nilai akhir (dengan pembobotan Tugas 20%, UH 20%, UTS 30%, UAS 30%), pengurutan peringkat (*ranking*) kelas otomatis, serta ekspor lembar rapor digital dan rekapitulasi nilai/prestasi ke dalam format PDF (menggunakan pustaka Laravel-DomPDF) dapat dilakukan secara instan, transparan, dan akurat, sehingga mengurangi beban kerja administratif guru.
5. **Kesesuaian Fungsional**: Berdasarkan pengujian fungsionalitas sistem menggunakan metode *Black Box Testing* terhadap 22 skenario pengujian utama (meliputi sistem autentikasi, manajemen data master, manajemen kelas, penginputan nilai massal, penginputan prestasi, hingga proses kenaikan kelas dan cetak laporan), seluruh fungsi sistem berjalan dengan baik dan menunjukkan hasil yang "Sesuai" dengan kebutuhan pengguna.

---

## 5.2 Saran

Demi pengembangan Sistem Informasi SIPRESMA 28 yang lebih baik dan berkelanjutan di masa yang akan datang, penulis menyarankan beberapa hal sebagai berikut:

1. **Integrasi Basis Data Dapodik**: Untuk pengembangan selanjutnya, sistem disarankan dapat dikembangkan agar terintegrasi secara otomatis dengan basis data Dapodik (Data Pokok Pendidikan) Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi. Hal ini berguna untuk mempermudah sinkronisasi data profil guru, siswa, dan sekolah tanpa perlu melakukan input data manual ulang di sistem lokal.
2. **Fitur Notifikasi Otomatis untuk Orang Tua**: Penambahan modul notifikasi otomatis langsung ke perangkat telepon orang tua/wali murid menggunakan WhatsApp API atau SMS Gateway. Notifikasi ini dapat dikirimkan secara otomatis pada saat nilai ujian, pengumuman remedial, atau sertifikat prestasi siswa selesai diunggah oleh wali kelas.
3. **Pengembangan Aplikasi Mobile (Android & iOS)**: Pengembangan aplikasi SIPRESMA 28 ke dalam versi aplikasi mobile agar mempermudah orang tua murid dalam mengakses *dashboard* perkembangan nilai akademik dan monitoring prestasi anak mereka secara lebih praktis melalui smartphone masing-masing.
4. **Peningkatan Sistem Keamanan Data**: Melakukan pemeliharaan (*maintenance*) berkala, pengujian keamanan sistem secara intensif (*penetration testing*), serta penerapan enkripsi data pada kolom-kolom sensitif (seperti nilai rapor dan data identitas siswa) guna mencegah ancaman kebocoran data penting sekolah.
