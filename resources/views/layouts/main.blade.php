@extends('layouts.dashboard')

@push('scripts')
<style>
:root {
  /* 1. Komponen Navigasi Utama Gelap (Slate-steel blue) */
  --bg-sidebar-dark: #25354F;      /* Warna dasar sidebar steel blue */
  --bg-topbar-dark: #293E59;       /* Warna header/topbar atas */
  --border-dark: #334C6E;          /* Batas garis komponen gelap */
  
  /* 2. Komponen Halaman Konten Cerah Beraksen */
  --bg-content-soft: #F2EFE7;      /* Latar belakang dasar halaman kanan: krem/rose sangat lembut */
  --card-white: #FFFFFF;           /* Latar kotak card/tabel utama: putih bersih */
  --border-light: #D8E6F2;         /* Garis batas/border di area cerah */
  
  /* 3. Aksen Warna & Teks */
  --primary-burgundy: #3D5A80;     /* Warna tombol utama (seperti '+ Tambah Kelas') dan teks header tabel */
  --text-dark-main: #2D3748;       /* Warna teks judul dan isi data agar tajam dan mudah dibaca */
  --text-muted: #64748B;           /* Sub-judul kecil atau keterangan teks */
  --accent-table-hover: #FAF9F6;   /* Efek sorotan (hover) baris tabel agar tidak monoton putih */
}
</style>
@endpush

