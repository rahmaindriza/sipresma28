<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPRESMA 28 - SD Negeri 28 Kinali</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #9F5261; /* Luxury Burgundy */
            --primary-burgundy: #9F5261; /* Burgundy Utama */
            --primary-hover: #86414E;
            --primary-rgb: 159, 82, 97;
            --dark-color: #4A2830; /* Soft Cream Text / Dark Mauve */
            --light-bg: #FAF5F5; /* Soft Cream Background */
            --footer-bg: #1A0E11; /* Deep Cokelat-Burgundy Footer */
            --accent-color: #3D8B6F; /* Emerald Sage Green Soft */
            --accent-hover: #2F6D56;
            --accent-rgb: 61, 139, 111;
            --terracotta-color: #D6455D; /* Rose Red Remedial */
            --terracotta-hover: #B5364B;
            --terracotta-rgb: 214, 69, 93;
            --font-title: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-body);
            color: var(--dark-color);
            background-color: var(--light-bg);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-title);
            font-weight: 700;
            color: var(--dark-color) !important;
        }

        /* Card background styling overrides to match --card-light-cream */
        .stats-card, .feature-card, .news-card, .contact-card, .modal-content {
            background-color: #FFFDFD !important;
            border-color: rgba(159, 82, 97, 0.12) !important;
        }

        /* Section wrapper for Visi Misi dibalut warna soft burgundy */
        .bg-light-section {
            background-color: rgba(159, 82, 97, 0.06) !important;
        }

        /* Lead and text secondary colors override */
        .text-muted, .text-secondary, .section-subtitle, .lead {
            color: #7A535C !important; /* Soft mauve text */
        }
        
        .tokoh-name {
            color: var(--dark-color) !important;
        }

        /* Standardize Bootstrap color classes on landing page to use our school palette */
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .bg-success {
            background-color: var(--accent-color) !important;
        }
        .text-success {
            color: var(--accent-color) !important;
        }
        .bg-danger {
            background-color: var(--terracotta-color) !important;
        }
        .text-danger {
            color: var(--terracotta-color) !important;
        }
        .bg-warning {
            background-color: #F59E0B !important; /* Keep gold accent for trophies */
        }
        .text-warning {
            color: #F59E0B !important;
        }

        /* Balanced Burgundy Navbar & Menu Text */
        .navbar-custom {
            background-color: var(--primary-burgundy) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .navbar-brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #FFFFFF !important;
        }

        .navbar-brand-sub {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.75) !important;
            display: block;
            margin-top: -3px;
        }

        .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85) !important;
            transition: color 0.25s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: #FFFFFF !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #FFFFFF;
            transition: width 0.25s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .navbar-custom .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
        }

        .navbar-custom .navbar-toggler-icon {
            filter: invert(1) grayscale(1) brightness(2);
        }

        .btn-electric {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 24px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(var(--primary-rgb), 0.3);
        }

        .btn-electric:hover {
            background-color: var(--primary-hover);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
        }

        .btn-outline-custom {
            color: #475569;
            background-color: transparent;
            border: 2px solid #E2E8F0;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 24px;
            transition: all 0.25s ease;
        }

        .btn-outline-custom:hover {
            color: #0F172A;
            background-color: #F1F5F9;
            border-color: #CBD5E1;
            transform: translateY(-2px);
        }

        /* Navbar Login Button Custom Style */
        .btn-login-nav {
            background-color: #FFFFFF;
            color: var(--primary-burgundy) !important;
            font-weight: 700; /* tebal (bold) */
            border-radius: 8px;
            padding: 10px 24px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login-nav:hover {
            background-color: #FDF4F5; /* rose soft sangat muda */
            color: var(--primary-burgundy) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Hero Section */
        .hero-section {
            padding: 140px 0 100px 0;
            background: linear-gradient(135deg, rgba(244, 247, 245, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(var(--primary-rgb), 0.08);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            filter: blur(80px);
            z-index: 0;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(var(--accent-rgb), 0.06);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            filter: blur(80px);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-img-container {
            position: relative;
            z-index: 2;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 8px solid #FFFFFF;
        }

        .hero-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.05) 0%, rgba(15, 23, 42, 0.4) 100%);
        }

        /* Stats Cards */
        .stats-section {
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .stats-card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 30px 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            text-align: center;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(var(--primary-rgb), 0.08);
            border-color: rgba(var(--primary-rgb), 0.3);
        }

        .stats-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon-wrapper {
            transform: scale(1.1);
        }

        .stats-number {
            font-size: 2.25rem;
            font-family: var(--font-title);
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 5px;
        }

        .stats-label {
            color: #64748B;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Sections General */
        .section-padding {
            padding: 100px 0;
        }

        .bg-light-section {
            background-color: var(--light-bg);
        }

        .section-header {
            max-width: 700px;
            margin: 0 auto 60px auto;
            text-align: center;
        }

        .section-tag {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary-color);
            display: inline-block;
            margin-bottom: 12px;
            background: rgba(var(--primary-rgb), 0.08);
            padding: 6px 16px;
            border-radius: 30px;
        }

        .section-title {
            font-size: 2.25rem;
            margin-bottom: 15px;
        }

        .section-subtitle {
            color: #64748B;
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Profile Block */
        .profile-img-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            border: 6px solid #FFFFFF;
        }

        /* Visi Misi Card */
        .feature-card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
            border-color: rgba(var(--primary-rgb), 0.2);
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 25px;
            display: inline-block;
        }

        /* Tokoh Utama Grid */
        .tokoh-card {
            text-align: center;
            background: transparent;
            padding: 20px;
            height: 100%;
        }

        .tokoh-avatar-wrapper {
            width: 160px;
            height: 160px;
            margin: 0 auto 25px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid #FFFFFF;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            position: relative;
        }

        .tokoh-card:hover .tokoh-avatar-wrapper {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 15px 30px rgba(var(--primary-rgb), 0.15);
            border-color: var(--primary-color);
        }

        .tokoh-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #0F172A;
        }

        .tokoh-role {
            font-size: 0.88rem;
            color: #64748B;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* News Section */
        .news-card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            border-color: rgba(var(--primary-rgb), 0.2);
        }

        .news-img-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .news-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-img {
            transform: scale(1.05);
        }

        .news-date {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            color: #FFFFFF;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .news-body {
            padding: 25px;
        }

        .news-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .news-desc {
            color: #64748B;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .news-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .news-link:hover {
            color: var(--primary-hover);
            gap: 8px;
        }

        /* Map and Contact */
        .contact-card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
        }

        .map-container {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            height: 100%;
            min-height: 380px;
        }

        .form-control-custom {
            background-color: var(--light-bg);
            border: 1px solid #E2E8F0;
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--dark-color);
            transition: all 0.25s ease;
        }

        .form-control-custom:focus {
            background-color: #FFFFFF;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.15);
            outline: none;
        }

        /* Footer */
        .footer-custom {
            background-color: var(--footer-bg);
            color: #94A3B8;
            padding: 80px 0 30px 0;
            border-top: 1px solid #1E293B;
        }

        .footer-logo-title {
            font-family: var(--font-title);
            font-weight: 800;
            color: #FFFFFF;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .footer-link-group h5 {
            color: #FFFFFF;
            font-size: 1.1rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-link-group h5::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
            bottom: 0;
            left: 0;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #94A3B8;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-links a:hover {
            color: #FFFFFF;
            padding-left: 5px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #1E293B;
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            background-color: var(--primary-color);
            color: #FFFFFF;
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid #1E293B;
            padding-top: 30px;
            margin-top: 60px;
            font-size: 0.9rem;
        }

        /* Scroll Top Button */
        .btn-scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 48px;
            height: 48px;
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            border: none;
            transition: all 0.25s ease;
            opacity: 0;
            visibility: hidden;
        }

        .btn-scroll-top.show {
            opacity: 1;
            visibility: visible;
        }

        .btn-scroll-top:hover {
            background-color: var(--primary-hover);
            transform: translateY(-3px);
        }

        /* Kegiatan Slider Container Styles */
        .slider-relative-container {
            position: relative;
            padding: 0 40px; /* space for arrow buttons */
        }
        
        .kegiatan-slider {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            gap: 24px;
            padding: 15px 5px 25px 5px;
            scrollbar-width: thin; /* Firefox thin scrollbar */
            scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .kegiatan-slider::-webkit-scrollbar {
            height: 6px;
        }
        .kegiatan-slider::-webkit-scrollbar-track {
            background: transparent; 
        }
        .kegiatan-slider::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.2); 
            border-radius: 10px;
        }
        .kegiatan-slider::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.4); 
        }

        .kegiatan-slider-item {
            flex: 0 0 100%;
            scroll-snap-align: start;
            max-width: 100%;
            transition: transform 0.3s ease;
        }

        @media (min-width: 768px) {
            .kegiatan-slider-item {
                flex: 0 0 calc(50% - 12px);
                max-width: calc(50% - 12px);
            }
        }

        @media (min-width: 992px) {
            .kegiatan-slider-item {
                flex: 0 0 calc(33.333% - 16px);
                max-width: calc(33.333% - 16px);
            }
        }

        /* Navigation Arrow Buttons */
        .slider-control-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 50%;
            color: var(--dark-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 10;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slider-control-btn:hover {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .slider-control-btn.btn-prev {
            left: 0;
        }

        .slider-control-btn.btn-next {
            right: 0;
        }

        .slider-control-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .slider-control-btn.disabled {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        @media (max-width: 576px) {
            .slider-relative-container {
                padding: 0;
            }
            .slider-control-btn {
                display: none; /* Swipe is natural on mobile */
            }
        }
    </style>
</head>
<body id="beranda">

    <!-- 1. NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#beranda">
                <div class="me-2 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.jpg') }}" style="height: 40px; width: auto;" alt="Logo">
                </div>
                <div>
                    <span class="navbar-brand-title">SIPRESMA 28</span>
                    <span class="navbar-brand-sub">SDN 28 Kinali</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#informasi">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex justify-content-center mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-login-nav" id="btn-login-auth">Ke Dashboard</a>
                        @else
                            <a href="{{ url('/login') }}" class="btn-login-nav" id="btn-login-auth">Login</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 hero-content text-center text-lg-start">
                    <span class="section-tag">Portal Resmi</span>
                    <h1 class="hero-title">Sistem Informasi Manajemen Nilai & Monitoring Prestasi (SIPRESMA 28)</h1>
                    <p class="section-subtitle mb-4">Portal integrasi penilaian akademik dan pemantauan capaian prestasi siswa SD Negeri 28 Kinali secara transparan, akurat, dan modern.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                        <a href="#profil" class="btn-electric py-3 px-4">Mulai Jelajah</a>
                        <a href="#informasi" class="btn-outline-custom py-3 px-4">Panduan Sistem</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-img-container">
                        <img src="{{ asset('images/sdn28kinali.jpg') }}" alt="SDN 28 Kinali" class="img-fluid w-100">
                        <div class="hero-img-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. STATISTIK SEKOLAH -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <div class="stats-icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stats-number">{{ $siswaCount ?? 350 }}</div>
                        <div class="stats-label">Siswa Aktif</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <div class="stats-icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="stats-number">{{ $guruCount ?? 25 }}</div>
                        <div class="stats-label">Guru & Staf</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <div class="stats-icon-wrapper bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div class="stats-number">{{ $mapelCount ?? 12 }}</div>
                        <div class="stats-label">Mata Pelajaran</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <div class="stats-icon-wrapper bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <div class="stats-number">{{ $prestasiCount ?? 50 }}</div>
                        <div class="stats-label">Prestasi Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. SAMBUTAN & PROFIL SINGKAT -->
    <section class="section-padding" id="profil">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="section-tag">Profil Sekolah</span>
                    <h2 class="section-title">Pendidikan Berkualitas di SDN 28 Kinali</h2>
                    <p class="lead text-muted mb-4">Melahirkan generasi penerus yang cerdas, berkarakter mulia, dan siap berkontribusi positif bagi bangsa dan negara.</p>
                    <p class="text-secondary mb-4">SD Negeri 28 Kinali berkomitmen untuk terus berinovasi dalam mengintegrasikan teknologi digital guna meningkatkan tata kelola akademik. Kami meyakini bahwa transparansi penilaian, interaksi sinergis dengan wali murid, serta pendataan prestasi siswa secara terpadu melalui platform <strong>SIPRESMA 28</strong> merupakan fondasi utama dalam menciptakan ekosistem sekolah yang unggul dan akuntabel.</p>
                    <div class="row g-4 pt-2">
                        <div class="col-md-6 d-flex align-items-start gap-3">
                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fs-6">Karakter Mulia</h5>
                                <p class="text-secondary small mb-0">Pembiasaan budi pekerti yang luhur dan nilai moral keagamaan.</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-start gap-3">
                            <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                                <i class="bi bi-award fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fs-6">Prestasi Unggul</h5>
                                <p class="text-secondary small mb-0">Mendukung potensi terbaik siswa dalam bidang akademik & minat bakat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-img-container">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80" alt="Guru dan Staf SDN 28 Kinali" class="img-fluid w-100 rounded-3">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. VISI, MISI & ARTIKEL STRATEGIS -->
    <section class="section-padding bg-light-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Arah & Strategi</span>
                <h2 class="section-title">Visi, Misi & Fokus Mutu</h2>
                <p class="section-subtitle">Arah strategis yang memandu langkah SDN 28 Kinali dalam mendidik dan mengembangkan siswa secara holistik.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary p-3 rounded-4 d-inline-flex">
                            <i class="bi bi-compass"></i>
                        </div>
                        <h4 class="mb-3">Visi & Misi</h4>
                        <p class="text-secondary mb-3"><strong>Visi:</strong> Terwujudnya insan yang religius, unggul dalam prestasi, berkarakter mulia, dan peduli lingkungan.</p>
                        <p class="text-secondary mb-0"><strong>Misi:</strong> Melaksanakan pembelajaran aktif, membimbing bakat anak secara maksimal, serta menerapkan nilai keimanan yang kokoh dalam aktivitas sehari-hari.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-success bg-opacity-10 text-success p-3 rounded-4 d-inline-flex">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4 class="mb-3">Budaya Sekolah</h4>
                        <p class="text-secondary mb-3">Penerapan budaya 5S (Senyum, Sapa, Salam, Sopan, Santun) serta penanaman integritas dan disiplin waktu di seluruh lingkungan sekolah.</p>
                        <p class="text-secondary mb-0">Melalui pembiasaan rutin, kami membangun sinergi kekeluargaan dan semangat kerja keras bagi seluruh elemen warga sekolah.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger p-3 rounded-4 d-inline-flex">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <h4 class="mb-3">Mutu Akademik</h4>
                        <p class="text-secondary mb-3">Mengedepankan standardisasi pembelajaran kurikulum nasional yang relevan, berbasis evaluasi berlanjut dan terukur secara transparan.</p>
                        <p class="text-secondary mb-0">Melalui platform SIPRESMA 28, orang tua dapat langsung melihat rekap capaian nilai siswa secara berkala dan akurat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. DAFTAR GURU & STAF PENDIDIK -->
    <section class="section-padding" id="guru">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Tenaga Pendidik</span>
                <h2 class="section-title">Guru & Staf Sekolah</h2>
                <p class="section-subtitle">Pendidik profesional penggerak kemajuan mutu pendidikan di SD Negeri 28 Kinali.</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse ($gurusSorted as $guru)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="tokoh-card">
                        <div class="tokoh-avatar-wrapper">
                            @if($guru->foto && file_exists(public_path('uploads/guru/' . $guru->foto)))
                                <img src="{{ asset('uploads/guru/' . $guru->foto) }}" alt="{{ $guru->nama }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 bg-[#FDF4F5] text-[#9F5261] d-flex align-items-center justify-content-center font-bold" style="font-size: 36px;">
                                    {{ strtoupper(substr($guru->nama, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="tokoh-name fw-bold text-dark">{{ $guru->nama }}</h4>
                        <p class="tokoh-role" style="color: #9F5261 !important; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">{{ $guru->jabatan }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-5">
                    <div class="fs-1 text-muted mb-3"><i class="bi bi-people"></i></div>
                    <p>Belum ada data guru atau staf terdaftar.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 7. BERITA & AKTIVITAS TERBARU -->
    <section class="section-padding bg-light-section" id="informasi">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Kabar Sekolah</span>
                <h2 class="section-title">Berita & Aktivitas Terbaru</h2>
                <p class="section-subtitle">Ikuti perkembangan prestasi, agenda sekolah, dan kegiatan belajar mengajar terkini.</p>
            </div>
            <div class="slider-relative-container">
                <!-- Navigation Buttons -->
                <button class="slider-control-btn btn-prev" id="sliderPrevBtn" aria-label="Previous Slide">
                    <i class="bi bi-chevron-left fs-5"></i>
                </button>
                <button class="slider-control-btn btn-next" id="sliderNextBtn" aria-label="Next Slide">
                    <i class="bi bi-chevron-right fs-5"></i>
                </button>

                <div class="kegiatan-slider" id="kegiatanSlider">
                    @forelse($kegiatan_terbaru as $keg)
                    <div class="kegiatan-slider-item">
                        <div class="news-card">
                            <div class="news-img-container">
                                @if($keg->gambar)
                                <img src="{{ asset('storage/' . $keg->gambar) }}" alt="{{ $keg->nama_kegiatan }}" class="news-img">
                                @else
                                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=500&q=80" alt="{{ $keg->nama_kegiatan }}" class="news-img">
                                @endif
                                <span class="news-date">{{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="news-body text-start">
                                <span class="badge bg-primary mb-2 text-uppercase tracking-wider text-[10px]">{{ $keg->kategori }}</span>
                                <h4 class="news-title">{{ $keg->nama_kegiatan }}</h4>
                                <p class="news-desc">{{ \Illuminate\Support\Str::limit($keg->deskripsi, 100) }}</p>
                                <button type="button" class="btn btn-link news-link p-0 text-decoration-none border-0 align-baseline" data-bs-toggle="modal" data-bs-target="#modalKegiatan{{ $keg->id }}">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Static Card 1 -->
                    <div class="kegiatan-slider-item">
                        <div class="news-card">
                            <div class="news-img-container">
                                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=500&q=80" alt="Lomba Sains" class="news-img">
                                <span class="news-date">28 Jun 2026</span>
                            </div>
                            <div class="news-body text-start">
                                <span class="badge bg-primary mb-2 text-uppercase tracking-wider text-[10px]">perlombaan</span>
                                <h4 class="news-title">Juara 1 Lomba Sains Tingkat Kabupaten</h4>
                                <p class="news-desc">Siswa SDN 28 Kinali kembali menorehkan prestasi membanggakan dengan meraih juara pertama pada ajang Kompetisi Sains tingkat Kabupaten.</p>
                                <button type="button" class="btn btn-link news-link p-0 text-decoration-none border-0 align-baseline" data-bs-toggle="modal" data-bs-target="#modalStatic1">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Static Card 2 -->
                    <div class="kegiatan-slider-item">
                        <div class="news-card">
                            <div class="news-img-container">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=500&q=80" alt="Evaluasi Digital" class="news-img">
                                <span class="news-date">15 Jun 2026</span>
                            </div>
                            <div class="news-body text-start">
                                <span class="badge bg-primary mb-2 text-uppercase tracking-wider text-[10px]">resmi</span>
                                <h4 class="news-title">Penilaian Digital Menggunakan SIPRESMA 28</h4>
                                <p class="news-desc">Penerapan portal SIPRESMA 28 secara berkala mempermudah guru melakukan rekap nilai harian serta ujian semester secara paperless dan transparan.</p>
                                <button type="button" class="btn btn-link news-link p-0 text-decoration-none border-0 align-baseline" data-bs-toggle="modal" data-bs-target="#modalStatic2">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Static Card 3 -->
                    <div class="kegiatan-slider-item">
                        <div class="news-card">
                            <div class="news-img-container">
                                <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=500&q=80" alt="Kegiatan Literasi" class="news-img">
                                <span class="news-date">08 Jun 2026</span>
                            </div>
                            <div class="news-body text-start">
                                <span class="badge bg-primary mb-2 text-uppercase tracking-wider text-[10px]">ekstrakurikuler</span>
                                <h4 class="news-title">Gerakan Literasi Sekolah Tiap Sabtu Pagi</h4>
                                <p class="news-desc">Program membaca bersama buku bacaan non-akademik di lapangan sekolah guna memperluas wawasan dan menumbuhkan minat membaca sejak dini.</p>
                                <button type="button" class="btn btn-link news-link p-0 text-decoration-none border-0 align-baseline" data-bs-toggle="modal" data-bs-target="#modalStatic3">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Modals placed outside the slider wrapper to avoid flex layout issues -->
            @foreach($kegiatan_terbaru as $keg)
            <!-- Modal Detail Kegiatan Dinamis -->
            <div class="modal fade text-dark" id="modalKegiatan{{ $keg->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $keg->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0 justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary text-uppercase tracking-wider text-[10px] me-2">{{ $keg->kategori }}</span>
                                <span class="text-secondary small">{{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d F Y') }}</span>
                            </div>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3 text-start">
                            <h3 class="modal-title fw-bold mb-3" id="modalLabel{{ $keg->id }}">{{ $keg->nama_kegiatan }}</h3>
                            
                            <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px; background-color: #f8fafc;">
                                @if($keg->gambar)
                                <img src="{{ asset('storage/' . $keg->gambar) }}" alt="{{ $keg->nama_kegiatan }}" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                                @else
                                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80" alt="{{ $keg->nama_kegiatan }}" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom text-secondary small">
                                <div><i class="bi bi-tag-fill text-primary"></i> <span class="text-capitalize">{{ $keg->jenis_kegiatan }}</span></div>
                                <div>|</div>
                                <div><i class="bi bi-calendar3 text-primary"></i> Semester: {{ $keg->semester_aktif }}</div>
                            </div>

                            <div class="text-secondary lh-lg" style="white-space: pre-line;">
                                {{ $keg->deskripsi }}
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if($kegiatan_terbaru->isEmpty())
            <!-- Modal Static 1 -->
            <div class="modal fade text-dark" id="modalStatic1" tabindex="-1" aria-labelledby="modalLabelStatic1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0 justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary text-uppercase tracking-wider text-[10px] me-2">perlombaan</span>
                                <span class="text-secondary small">28 Juni 2026</span>
                            </div>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3 text-start">
                            <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic1">Juara 1 Lomba Sains Tingkat Kabupaten</h3>
                            
                            <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px; background-color: #f8fafc;">
                                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80" alt="Lomba Sains" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom text-secondary small">
                                <div><i class="bi bi-tag-fill text-primary"></i> Akademik</div>
                                <div>|</div>
                                <div><i class="bi bi-calendar3 text-primary"></i> Semester: 2025/2026 Genap</div>
                            </div>

                            <div class="text-secondary lh-lg">
                                Siswa SDN 28 Kinali kembali menorehkan prestasi membanggakan dengan meraih juara pertama pada ajang Kompetisi Sains tingkat Kabupaten. Kompetisi ini diikuti oleh puluhan sekolah dasar dari berbagai kecamatan, di mana perwakilan dari sekolah kami berhasil menunjukkan keunggulan dalam pemahaman sains dasar, praktikum, dan analisis logis. Sekolah sangat bangga dan berkomitmen untuk terus membimbing bakat anak secara maksimal demi prestasi masa depan.
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Static 2 -->
            <div class="modal fade text-dark" id="modalStatic2" tabindex="-1" aria-labelledby="modalLabelStatic2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0 justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary text-uppercase tracking-wider text-[10px] me-2">resmi</span>
                                <span class="text-secondary small">15 Juni 2026</span>
                            </div>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3 text-start">
                            <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic2">Penilaian Digital Menggunakan SIPRESMA 28</h3>
                            
                            <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px; background-color: #f8fafc;">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80" alt="Evaluasi Digital" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom text-secondary small">
                                <div><i class="bi bi-tag-fill text-primary"></i> Akademik</div>
                                <div>|</div>
                                <div><i class="bi bi-calendar3 text-primary"></i> Semester: 2025/2026 Genap</div>
                            </div>

                            <div class="text-secondary lh-lg">
                                Penerapan portal SIPRESMA 28 secara berkala mempermudah guru melakukan rekap nilai harian serta ujian semester secara paperless dan transparan. Wali murid juga dapat memantau capaian belajar anak secara berkala dan langsung. Inovasi digital ini mendukung target efisiensi tata kelola sekolah dasar yang unggul dan mandiri.
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Static 3 -->
            <div class="modal fade text-dark" id="modalStatic3" tabindex="-1" aria-labelledby="modalLabelStatic3" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0 justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary text-uppercase tracking-wider text-[10px] me-2">ekstrakurikuler</span>
                                <span class="text-secondary small">08 Juni 2026</span>
                            </div>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3 text-start">
                            <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic3">Gerakan Literasi Sekolah Tiap Sabtu Pagi</h3>
                            
                            <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px; background-color: #f8fafc;">
                                <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=800&q=80" alt="Kegiatan Literasi" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom text-secondary small">
                                <div><i class="bi bi-tag-fill text-primary"></i> Non-Akademik</div>
                                <div>|</div>
                                <div><i class="bi bi-calendar3 text-primary"></i> Semester: 2025/2026 Genap</div>
                            </div>

                            <div class="text-secondary lh-lg">
                                Program membaca bersama buku bacaan non-akademik di lapangan sekolah guna memperluas wawasan dan menumbuhkan minat membaca sejak dini. Gerakan literasi ini diikuti oleh seluruh siswa dari Kelas I hingga Kelas VI, staf pengajar, dan didampingi langsung oleh Kepala Sekolah. Kami meyakini minat baca yang tinggi melahirkan generasi yang cerdas dan kaya perspektif.
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- 8. LOKASI PETA & HUBUNGI KAMI -->
    <section class="section-padding" id="kontak">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Hubungi Kami</span>
                <h2 class="section-title">Lokasi & Aspirasi</h2>
                <p class="section-subtitle">Temukan lokasi kami secara langsung atau kirimkan kritik dan saran Anda untuk meningkatkan pelayanan kami.</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="map-container">
                        <!-- Sematan Google Maps SDN 28 Kinali, Sumatera Barat -->
                        <iframe 
                            src="https://maps.google.com/maps?q=-0.15333495126860688,99.76505095952322&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="100%" 
                            style="border:0; min-height:380px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-card">
                        <h4 class="mb-4">Kirim Pesan</h4>
                        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Aspirasi Anda berhasil dikirim! Terima kasih atas masukan yang diberikan.'); this.reset();">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label font-medium small text-secondary">Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-custom" id="name" placeholder="Contoh: Budi Santoso" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label font-medium small text-secondary">Email Resmi / Aktif</label>
                                    <input type="email" class="form-control form-control-custom" id="email" placeholder="Contoh: budi@gmail.com" required>
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label font-medium small text-secondary">Perihal / Subjek</label>
                                    <input type="text" class="form-control form-control-custom" id="subject" placeholder="Perihal pengajuan aspirasi" required>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label font-medium small text-secondary">Isi Pesan / Aspirasi</label>
                                    <textarea class="form-control form-control-custom" id="message" rows="4" placeholder="Tuliskan kritik, saran, atau masukan Anda di sini..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-electric w-100 py-3">Kirim Aspirasi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mt-5">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 p-4 bg-light-section rounded-4">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark">Alamat Sekolah</h6>
                            <p class="text-secondary small mb-0">Kinali, Kabupaten Pasaman Barat, Sumatera Barat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 p-4 bg-light-section rounded-4">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-envelope-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark">Email Sekolah</h6>
                            <p class="text-secondary small mb-0">info@sdn28kinali.sch.id</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 p-4 bg-light-section rounded-4">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-telephone-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark">Nomor Telepon</h6>
                            <p class="text-secondary small mb-0">+62 822-1234-5678</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. FOOTER PEKAT (#0B0E14) -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-2 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/logo.jpg') }}" style="height: 40px; width: auto;" alt="Logo">
                        </div>
                        <span class="footer-logo-title">SIPRESMA 28</span>
                    </div>
                    <p class="mb-4">Portal integrasi digital penilaian akademik dan pemantauan prestasi murid SD Negeri 28 Kinali secara modern, akurat, aman, dan efisien.</p>
                    <div class="d-flex">
                        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-link-group">
                    <h5>Navigasi Cepat</h5>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#profil">Profil Sekolah</a></li>
                        <li><a href="#informasi">Informasi</a></li>
                        <li><a href="#kontak">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 footer-link-group">
                    <h5>Sistem & Akses</h5>
                    <ul class="footer-links">
                        @if (Route::has('login'))
                            @auth
                                <li><a href="{{ url('/dashboard') }}">Dashboard Panel</a></li>
                            @else
                                <li><a href="{{ url('/login') }}">Login </a></li>
                            @endauth
                        @endif
                        <li><a href="#informasi">Panduan Guru</a></li>
                        <li><a href="#informasi">Panduan Wali</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4 position-relative padding-bottom-10" style="font-size: 1.1rem;">SDN 28 Kinali</h5>
                    <p class="small mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Kinali, Kabupaten Pasaman Barat, Sumatera Barat, Indonesia</p>
                    <p class="small mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i> +62 822-1234-5678</p>
                    <p class="small mb-0"><i class="bi bi-envelope-fill text-primary me-2"></i> info@sdn28kinali.sch.id</p>
                </div>
            </div>
            
            <div class="row footer-bottom">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span>&copy; 2026 SIPRESMA 28 - SD Negeri 28 Kinali. All Rights Reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="text-secondary">Didukung oleh Tim Pengembang IT SDN 28 Kinali</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <button class="btn-scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        <i class="bi bi-arrow-up-short fs-4"></i>
    </button>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Scroll Top Button logic
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });

        // Add class active on nav-link based on scroll position
        const sections = document.querySelectorAll('section, header, footer');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        });

        // Kegiatan Slider Horizontal Scroll Logic
        const slider = document.getElementById('kegiatanSlider');
        const prevBtn = document.getElementById('sliderPrevBtn');
        const nextBtn = document.getElementById('sliderNextBtn');
        
        if (slider && prevBtn && nextBtn) {
            const getScrollAmount = () => {
                const firstItem = slider.querySelector('.kegiatan-slider-item');
                if (firstItem) {
                    return firstItem.offsetWidth + 24; // item width + gap
                }
                return 300;
            };
            
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: -getScrollAmount(),
                    behavior: 'smooth'
                });
            });
            
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: getScrollAmount(),
                    behavior: 'smooth'
                });
            });
            
            const updateButtons = () => {
                const scrollLeft = slider.scrollLeft;
                const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
                
                if (maxScrollLeft <= 2) {
                    prevBtn.classList.add('disabled');
                    nextBtn.classList.add('disabled');
                    return;
                }
                
                if (scrollLeft <= 5) {
                    prevBtn.classList.add('disabled');
                } else {
                    prevBtn.classList.remove('disabled');
                }
                
                if (scrollLeft >= maxScrollLeft - 5) {
                    nextBtn.classList.add('disabled');
                } else {
                    nextBtn.classList.remove('disabled');
                }
            };
            
            slider.addEventListener('scroll', updateButtons);
            window.addEventListener('resize', updateButtons);
            
            // Initial check
            setTimeout(updateButtons, 300);
        }
    </script>
</body>
</html>
