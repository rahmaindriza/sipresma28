@extends('layouts.dashboard')

@push('scripts')
<style>
:root {
  /* 1. Komponen Navigasi Utama Gelap */
  --bg-sidebar-dark: #1F1215;      /* Warna dasar sidebar merah-cokelat gelap pekat */
  --bg-topbar-dark: #2D1B1F;       /* Warna header/topbar atas gelap tegas */
  --border-dark: #3D262A;          /* Batas garis komponen gelap */
  
  /* 2. Komponen Halaman Konten Cerah Beraksen */
  --bg-content-soft: #FAF7F7;      /* Latar belakang dasar halaman kanan: krem/rose sangat lembut */
  --card-white: #FFFFFF;           /* Latar kotak card/tabel utama: putih bersih */
  --border-light: #EAE1E3;         /* Garis batas/border di area cerah */
  
  /* 3. Aksen Warna & Teks */
  --primary-burgundy: #9F5261;     /* Warna tombol utama (seperti '+ Tambah Kelas') dan teks header tabel */
  --text-dark-main: #3D2228;       /* Warna teks judul dan isi data agar tajam dan mudah dibaca */
  --text-muted: #7A6266;           /* Sub-judul kecil atau keterangan teks */
  --accent-table-hover: #FFF9FA;   /* Efek sorotan (hover) baris tabel agar tidak monoton putih */
}
</style>
@endpush

