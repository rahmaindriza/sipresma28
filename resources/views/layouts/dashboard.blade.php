<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPRESMA 28') - SDN 28 Kinali</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
            --primary-hover: #86414E;         /* Burgundy lebih gelap saat di-hover */
            --text-dark-main: #3D2228;       /* Warna teks judul dan isi data agar tajam dan mudah dibaca */
            --text-muted: #7A6266;           /* Sub-judul kecil atau keterangan teks */
            --accent-table-hover: #FFF9FA;   /* Efek sorotan (hover) baris tabel agar tidak monoton putih */

            /* Map legacy variables for maximum compatibility */
            --primary-sekolah: var(--primary-burgundy);
            --remedial-sekolah: #A82E43;       /* Remedial menggunakan merah rose gelap */
            --lulus-sekolah: #245E49;          /* Lulus menggunakan hijau emerald gelap */
            --bg-dark-panel: var(--bg-content-soft);
            --card-dark-panel: var(--card-white);
        }

        body, html {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: var(--bg-content-soft) !important;
            color: var(--text-dark-main) !important;
        }

        /* Sidebar Kiri Styling (TETAP GELAP) */
        aside, aside.bg-slate-900 {
            background-color: var(--bg-sidebar-dark) !important;
            border-right: 1px solid var(--border-dark) !important;
        }

        /* Sidebar Logo Area */
        aside div.bg-slate-950, aside div.h-16 {
            background-color: var(--bg-sidebar-dark) !important;
            border-bottom: 1px solid var(--border-dark) !important;
        }
        aside div.h-16 h1 {
            color: #FFFFFF !important;
        }
        aside div.h-16 p {
            color: #A08B90 !important;
        }

        /* Sidebar Menu Items */
        aside nav a {
            color: #D1C7C9 !important; /* White-ish dim text */
            transition: all 0.2s ease !important;
        }
        aside nav a svg {
            color: #D1C7C9 !important;
            stroke: #D1C7C9 !important;
        }
        aside nav div.text-slate-500 {
            color: var(--text-muted) !important;
            font-weight: 600 !important;
        }

        /* Active tab in Sidebar Navigation (Rose/Burgundy accent) */
        aside a.sidebar-active, aside a.sidebar-active * {
            background-color: var(--primary-burgundy) !important;
            background-image: none !important;
            color: #FFFFFF !important;
            border-left: 4px solid var(--primary-burgundy) !important;
            box-shadow: 0 4px 12px rgba(159, 82, 97, 0.4) !important;
        }
        aside a.sidebar-active svg {
            color: #FFFFFF !important;
            stroke: #FFFFFF !important;
        }

        /* Hover menu item in Sidebar */
        aside nav a:hover:not(.sidebar-active) {
            background-color: var(--border-dark) !important;
            color: #FFFFFF !important;
        }
        aside nav a:hover:not(.sidebar-active) svg {
            color: #FFFFFF !important;
            stroke: #FFFFFF !important;
        }

        /* Sidebar User Profile Footer */
        aside div.p-4.bg-slate-950, aside div.border-t {
            background-color: var(--bg-sidebar-dark) !important;
            border-top: 1px solid var(--border-dark) !important;
        }
        aside div.p-4 p.text-white {
            color: #FFFFFF !important;
        }
        aside div.p-4 p.text-slate-400 {
            color: #A08B90 !important;
        }
        aside div.rounded-full.bg-slate-800 {
            background-color: var(--border-dark) !important;
            color: #FFFFFF !important;
            border: 1px solid var(--border-dark) !important;
        }

        /* Topbar Header Area (TETAP GELAP) */
        header.bg-slate-900, header {
            background-color: var(--bg-topbar-dark) !important;
            border-bottom: 1px solid var(--border-dark) !important;
        }
        header h2 {
            color: #FFFFFF !important;
        }
        header span.bg-blue-900\/40, header span.bg-red-950, header span.border-blue-800, header span {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        /* Main Workspace Content Area (CERAH BERAKSEN) */
        main, div.bg-slate-950, .bg-slate-950 {
            background-color: var(--bg-content-soft) !important;
            color: var(--text-dark-main) !important;
        }

        /* General container / card overrides to match clean white card styling */
        .glass-panel, .card, .bg-slate-900\/40, .bg-slate-900, .bg-slate-950\/40, .bg-slate-950\/45, .contact-card {
            background-color: var(--card-white) !important;
            background: var(--card-white) !important;
            border: 1px solid var(--border-light) !important;
            box-shadow: 0px 2px 4px rgba(61, 34, 40, 0.02) !important;
            color: var(--text-dark-main) !important;
            border-radius: 16px !important;
        }

        /* Global text overrides inside main content for readability and contrast */
        main h1, main h2, main h3, main h4, main h5, main h6, 
        main .text-white, main .font-bold, .text-white-always {
            color: var(--text-dark-main) !important;
        }
        main p, main .text-slate-400, main .text-slate-500, main .text-slate-300, 
        main .text-gray-400, main .text-gray-500, main .text-slate-450 {
            color: var(--text-muted) !important;
        }

        /* Statistics Cards - White base with subtle box shadow */
        .glass-panel.p-6, .bg-[#2D1B1F].p-6 {
            background-color: var(--card-white) !important;
            border: 1px solid var(--border-light) !important;
            box-shadow: 0px 2px 8px rgba(61, 34, 40, 0.03) !important;
            position: relative;
            overflow: hidden;
            border-radius: 16px !important;
        }

        /* Icons in stats boxes (Soft rose circular highlight with burgundy icons) */
        .glass-panel div[style*="background-color: rgba"], 
        .glass-panel .p-3, 
        .bg-[#2D1B1F] .p-3 {
            background-color: #FDF4F5 !important;
            color: var(--primary-burgundy) !important;
            border-radius: 9999px !important;
            border: 1px solid rgba(159, 82, 97, 0.1) !important;
        }
        .glass-panel .p-3 svg, .bg-[#2D1B1F] .p-3 svg {
            color: var(--primary-burgundy) !important;
            stroke: var(--primary-burgundy) !important;
        }

        /* Shortcut cards styling and hover highlights */
        a.group {
            background-color: var(--card-white) !important;
            border: 1px solid var(--border-light) !important;
        }
        a.group:hover {
            border-color: var(--primary-burgundy) !important;
            background-color: #FFF9FA !important;
        }

        /* Table custom styling overrides */
        table {
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-light) !important;
        }
        thead, thead tr, thead th {
            background-color: #FDF4F5 !important;
            color: var(--primary-burgundy) !important;
            font-weight: 700 !important;
            border-bottom: 2px solid var(--border-light) !important;
        }
        tbody tr {
            background-color: #FFFFFF !important;
            border-bottom: 1px solid var(--border-light) !important;
            transition: all 0.15s ease !important;
        }
        tbody tr:hover {
            background-color: var(--accent-table-hover) !important;
        }
        tbody td {
            color: var(--text-dark-main) !important;
            border-color: var(--border-light) !important;
        }

        /* Action link override inside tables */
        tbody td a {
            color: var(--primary-burgundy) !important;
            transition: color 0.15s ease !important;
        }
        tbody td a:hover {
            color: var(--primary-hover) !important;
            text-decoration: underline !important;
        }

        /* Filter forms & input boxes styling */
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], select, textarea {
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-light) !important;
            color: var(--text-dark-main) !important;
            border-radius: 10px !important;
            transition: all 0.2s ease-in-out !important;
        }
        input::placeholder {
            color: var(--text-muted) !important;
            opacity: 0.6;
        }
        input[type="text"]:focus, select:focus, textarea:focus {
            border-color: var(--primary-burgundy) !important;
            box-shadow: 0 0 0 3px rgba(159, 82, 97, 0.15) !important;
            outline: none !important;
        }

        /* Modals in light mode */
        #add-modal > div, #edit-modal > div, .modal-content {
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-light) !important;
            color: var(--text-dark-main) !important;
        }
        #add-modal label, #edit-modal label {
            color: var(--text-dark-main) !important;
        }

        /* Action buttons inside tables */
        tbody td button[onclick*="edit"], tbody td a[href*="edit"], tbody td button[onclick*="editKelas"] {
            color: var(--primary-burgundy) !important;
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }
        tbody td button[onclick*="edit"]:hover, tbody td a[href*="edit"]:hover, tbody td button[onclick*="editKelas"]:hover {
            color: var(--primary-hover) !important;
            text-decoration: underline !important;
        }

        /* Delete / Hapus buttons in tables (maroon soft badge) */
        tbody td form button[type="submit"], tbody td button[onclick*="delete"], tbody td a[class*="text-red"], tbody td form button[onclick*="confirm"] {
            background-color: #FDF0F2 !important;
            color: #A82E43 !important;
            border: 1px solid rgba(168, 46, 67, 0.2) !important;
            padding: 2px 8px !important;
            border-radius: 6px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            display: inline-block !important;
        }
        tbody td form button[type="submit"]:hover, tbody td button[onclick*="delete"]:hover, tbody td a[class*="text-red"]:hover, tbody td form button[onclick*="confirm"]:hover {
            background-color: #F8D7DA !important;
            color: #842029 !important;
            border-color: #F5C2C7 !important;
            text-decoration: none !important;
        }

        /* Primary Action Buttons */
        button[type="submit"], .btn-primary, .btn-electric-blue, .bg-blue-600, button.bg-blue-600, .btn-electric {
            background-color: var(--primary-burgundy) !important;
            border-color: var(--primary-burgundy) !important;
            color: #FFFFFF !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            transition: all 0.2s ease-in-out !important;
        }
        button[type="submit"]:hover, .btn-primary:hover, .btn-electric-blue:hover, .bg-blue-600:hover, button.bg-blue-600:hover, .btn-electric:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            box-shadow: 0 4px 15px rgba(159, 82, 97, 0.3) !important;
        }

        /* Status & KKM Badges overrides */
        .kkm-lulus, 
        .bg-green-950, 
        .bg-green-900\/40,
        [class*="text-green-"], 
        [class*="bg-green-"] {
            background-color: #E9F5F0 !important;
            color: #245E49 !important;
            border: 1px solid rgba(36, 94, 73, 0.25) !important;
        }
        .kkm-lulus span, .bg-green-950 span, [class*="bg-green-"] span {
            background-color: #245E49 !important;
        }

        .kkm-remedial, 
        .bg-red-950, 
        .bg-red-900\/40,
        [class*="text-red-"], 
        [class*="bg-red-"] {
            background-color: #FDF0F2 !important;
            color: #A82E43 !important;
            border: 1px solid rgba(168, 46, 67, 0.25) !important;
        }
        .kkm-remedial span, .bg-red-950 span, [class*="bg-red-"] span {
            background-color: #A82E43 !important;
        }

        /* Role Badges overrides */
        .bg-purple-900\/40 {
            background-color: #F3E8FF !important;
            color: #6B21A8 !important;
            border-color: #E9D5FF !important;
        }
        .bg-blue-900\/40 {
            background-color: #DBEAFE !important;
            color: #1E40AF !important;
            border-color: #BFDBFE !important;
        }
        .bg-yellow-900\/40 {
            background-color: #FEF3C7 !important;
            color: #92400E !important;
            border-color: #FDE68A !important;
        }
        .bg-green-900\/40 {
            background-color: #E2F0D9 !important;
            color: #385723 !important;
            border-color: #C5E0B4 !important;
        }
    </style>
</head>
<body class="h-full flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="hidden md:flex md:flex-col md:w-64 bg-slate-900 border-r border-slate-800 shrink-0">
        <!-- Logo & Header -->
        <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800">
            <div class="flex items-center space-x-3">
                <div class="shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-8 h-8 object-contain" alt="Logo">
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-wider text-white">SIPRESMA 28</h1>
                    <p class="text-[10px] text-slate-400">SD Negeri 28 Kinali</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @if(auth()->user()->isAdmin())
                <!-- Admin Links -->
                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2 tracking-wider">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Dashboard
                </a>

                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2 tracking-wider">Kontrol Akun</div>
                <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.users') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola Pengguna
                </a>

                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2 tracking-wider">Data Master</div>
                <a href="{{ route('admin.gurus') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.gurus') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Guru
                </a>
                <a href="{{ route('admin.kelas') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.kelas') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelas
                </a>
                <a href="{{ route('admin.siswas') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.siswas') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    Siswa
                </a>
                <a href="{{ route('admin.mapels') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.mapels') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Mata Pelajaran
                </a>
                <a href="{{ route('admin.tahun_ajarans') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.tahun_ajarans') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ajaran
                </a>

                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2 tracking-wider">Penjadwalan & Prestasi</div>
                <a href="{{ route('admin.assignments') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.assignments') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Penugasan Guru
                </a>
                <a href="{{ route('admin.prestasis') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.prestasis') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Prestasi Sekolah
                </a>
                <a href="{{ route('admin.kegiatan.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.kegiatan.*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 11-4 0 2 2 0 014 0zm-2 9a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Kegiatan Sekolah
                </a>

                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2 tracking-wider">Monitoring Akademik</div>
                <a href="{{ route('admin.nilai.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.nilai.index') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Monitoring Nilai
                </a>
                <a href="{{ route('admin.prestasi.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('admin.prestasi.index') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Monitoring Prestasi
                </a>
            @elseif(auth()->user()->isGuruMapel())
                <!-- Guru Mapel Links -->
                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2 tracking-wider">Guru Panel</div>
                <a href="{{ route('guru.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('guru.index') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('guru.grades.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('guru.grades.*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Input Nilai Mapel
                </a>
            @elseif(auth()->user()->isWaliKelas())
                <!-- Wali Kelas Links -->
                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2 tracking-wider">Wali Kelas Panel</div>
                <a href="{{ route('wali.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('wali.dashboard') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('wali.siswa') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('wali.siswa') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    Data Siswa
                </a>
                <a href="{{ route('walas.nilai.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('walas.nilai.index') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Input Nilai Mapel
                </a>
                <a href="{{ route('wali.rekap') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('wali.rekap') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v8m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"></path></svg>
                    Rekap & Ranking
                </a>
                <a href="{{ route('wali.prestasi') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('wali.prestasi') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Kelola Prestasi
                </a>
            @elseif(auth()->user()->isKepalaSekolah())
                <!-- Kepala Sekolah Links -->
                <div class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2 tracking-wider">Kepsek Panel</div>
                <a href="{{ route('kepsek.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition duration-200 text-sm {{ request()->routeIs('kepsek.*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"></path></svg>
                    Dashboard Monitoring
                </a>
            @endif
        </nav>

        <!-- User Profile Footer -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center min-w-0">
                <div class="w-10 h-10 rounded-full bg-slate-800 border border-blue-600 flex items-center justify-center font-bold text-blue-400 shrink-0 uppercase">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="ml-3 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 uppercase truncate">
                        @if(auth()->user()->isAdmin()) Admin
                        @elseif(auth()->user()->isGuruMapel()) Guru Mapel
                        @elseif(auth()->user()->isWaliKelas()) Wali Kelas
                        @elseif(auth()->user()->isKepalaSekolah()) Kepala Sekolah
                        @endif
                    </p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-400 transition p-1.5 hover:bg-slate-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Body -->
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-950">
        <!-- Top header bar -->
        <header class="h-16 flex items-center justify-between px-6 bg-slate-900 border-b border-slate-800">
            <!-- Mobile Menu Toggle Button -->
            <button class="md:hidden text-slate-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <div class="flex items-center space-x-4">
                <h2 class="text-lg font-semibold text-white hidden md:block">Sistem Informasi Manajemen Nilai & Monitoring Prestasi</h2>
            </div>
            
            <!-- Academic Term Info -->
            <div class="flex items-center space-x-3">
                @if(isset($activeTa))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-900/40 text-blue-300 border border-blue-800">
                    TA Aktif: {{ $activeTa->tahun }} ({{ $activeTa->semester }})
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-950 text-red-400 border border-red-900">
                    TA Aktif: Belum Ada
                </span>
                @endif
            </div>
        </header>

        <!-- Page View Body -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-800 text-green-300 flex items-center space-x-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-900/30 border border-red-800 text-red-300 flex items-center space-x-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
