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
            --primary-color: #3D5A80; /* Steel Blue Accent */
            --primary-burgundy: #3D5A80; 
            --primary-hover: #293E59; /* Darker Steel Blue */
            --primary-rgb: 61, 90, 128;
            --dark-color: #2D3748; /* Slate 800 for high-end text */
            --light-bg: #FFFFFF; /* Pure White Main Background */
            --beige-bg: #F2EFE7; /* Soft Warm Beige Highlight */
            --pale-blue: #D8E6F2; /* Light Pale Blue */
            --sky-blue: #CBD9E6; /* Accent Sky Blue */
            --footer-bg: #F2EFE7; /* Warm Beige Footer */
            --accent-color: #3D8B6F; /* Emerald Sage Green */
            --accent-hover: #2F6D56;
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
            color: var(--dark-color);
        }

        /* Lead and text secondary colors */
        .text-muted, .text-secondary {
            color: #64748B !important; /* Soft gray-slate */
        }

        /* Standardize colors to use the steel blue/sky blue palette */
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .text-blue-accent {
            color: #3D5A80 !important;
        }

        /* Navbar Styling (Clean White Glassmorphism) */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(61, 90, 128, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .navbar-brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--primary-color) !important;
        }

        .navbar-brand-sub {
            font-size: 0.75rem;
            font-weight: 500;
            color: #8A9Aad !important;
            display: block;
            margin-top: -3px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            transition: color 0.25s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.25s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .btn-login-nav {
            background-color: var(--primary-color);
            color: #FFFFFF !important;
            font-weight: 700;
            border-radius: 30px;
            padding: 8px 24px;
            border: none;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(61, 90, 128, 0.15);
        }

        .btn-login-nav:hover {
            background-color: var(--primary-hover);
            color: #FFFFFF !important;
            transform: translateY(-1px);
        }

        /* Hero Section Styling (Light & Bright Theme with sub-blend school photo) */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, rgba(242, 239, 231, 0.4) 30%, rgba(216, 230, 242, 0.3) 100%), url("{{ asset('images/gedung_sd.jpg') }}");
            background-size: cover;
            background-position: center;
            color: var(--dark-color);
            padding: 160px 0 220px 0;
            overflow: hidden;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.15;
            color: var(--dark-color) !important;
            margin-bottom: 15px;
        }

        .hero-accent {
            font-family: var(--font-title);
            font-style: italic;
            font-weight: 400;
            color: var(--primary-color) !important;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #4A5568 !important;
            max-width: 600px;
            line-height: 1.6;
        }

        .btn-blue-primary {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 30px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(61, 90, 128, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-blue-primary:hover {
            background-color: var(--primary-hover);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(61, 90, 128, 0.35);
        }

        /* Stats in Hero Column (Bright card) */
        .hero-stats-container {
            display: flex;
            gap: 30px;
            margin-top: 40px;
        }

        .hero-stat-item {
            text-align: left;
            border-left: 2px solid rgba(61, 90, 128, 0.2);
            padding-left: 15px;
        }

        .hero-stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: #64748B;
            font-weight: 550;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Overlapping Search/Action Card */
        .search-card-wrapper {
            position: relative;
            margin-top: -100px;
            z-index: 100;
            margin-bottom: 80px;
        }

        .overlapping-card {
            background: #FFFFFF;
            border: 1px solid rgba(61, 90, 128, 0.15);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
            padding: 35px;
        }

        .card-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 1px solid #E2E8F0;
            padding-bottom: 15px;
        }

        .card-tab-btn {
            background: transparent;
            border: none;
            font-weight: 700;
            font-size: 0.9rem;
            color: #64748B;
            padding: 8px 16px;
            border-radius: 30px;
            transition: all 0.25s ease;
        }

        .card-tab-btn.active {
            background: var(--primary-color);
            color: #FFFFFF;
        }

        .card-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group-custom label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748B;
            letter-spacing: 0.5px;
        }

        .form-input-custom {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.85rem;
            color: var(--dark-color);
            outline: none;
            transition: border-color 0.2s ease;
            background: #FAFAFA;
        }

        .form-input-custom:focus {
            border-color: var(--primary-color);
            background: #FFFFFF;
        }

        .btn-search-submit {
            background: var(--primary-color);
            color: #FFFFFF;
            font-weight: 750;
            font-size: 0.85rem;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            cursor: pointer;
            height: 43px;
        }

        .btn-search-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Section Global Layouts */
        .section-padding {
            padding: 80px 0;
        }

        .section-tag {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary-color) !important;
            margin-bottom: 12px;
            display: block;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: #64748B;
            max-width: 600px;
            margin-bottom: 45px;
        }

        /* Feature Cards (Why Choose Us) */
        .feature-card {
            background: #FFFFFF;
            border: 1px solid rgba(61, 90, 128, 0.08);
            border-radius: 20px;
            padding: 35px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px rgba(61, 90, 128, 0.05);
            border-color: rgba(61, 90, 128, 0.25);
        }

        .feature-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(61, 90, 128, 0.08);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 20px;
        }

        .feature-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--dark-color);
        }

        .feature-card-desc {
            font-size: 0.85rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Visi Misi Section (Beige background) */
        .visi-misi-section {
            background-color: var(--beige-bg);
        }

        .visi-misi-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .visi-misi-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .visi-misi-icon {
            color: var(--primary-color);
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .visi-misi-text {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.5;
        }

        .profile-img-container {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(61, 90, 128, 0.15);
        }

        /* News & Activities Section */
        .news-card {
            background: #FFFFFF;
            border: 1px solid rgba(61, 90, 128, 0.08);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.01);
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.04);
            border-color: rgba(61, 90, 128, 0.2);
        }

        .news-img-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
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

        .news-card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .news-card-date {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            color: var(--primary-color);
            padding: 3px 10px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .news-card-body {
            padding: 25px;
        }

        .news-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .news-card-desc {
            font-size: 0.85rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .news-card-btn {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.2s ease;
            background: transparent;
            border: none;
            padding: 0;
        }

        .news-card-btn:hover {
            color: var(--primary-hover);
            gap: 8px;
        }

        /* Testimonials Section (Beige background) */
        .testimonial-section {
            background-color: var(--beige-bg);
        }

        .testimonial-card {
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.01);
            height: 100%;
        }

        .rating-stars {
            color: #F59E0B; /* Star Gold */
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .testimonial-text {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.65;
            font-style: italic;
            margin-bottom: 25px;
        }

        .testimonial-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            background: #E2E8F0;
        }

        .testimonial-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 2px;
        }

        .testimonial-relation {
            font-size: 0.75rem;
            color: #64748B;
            font-weight: 500;
        }

        /* Staf & Guru Section */
        .guru-card {
            background: #FFFFFF;
            border: 1px solid rgba(61, 90, 128, 0.08);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.25s ease;
            height: 100%;
        }

        .guru-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.02);
            border-color: rgba(61, 90, 128, 0.15);
        }

        .guru-avatar-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px auto;
            border: 2px solid rgba(61, 90, 128, 0.2);
        }

        .guru-name {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-color);
        }

        .guru-role {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        /* Pre-footer CTA (Bright Beige & Sky Blue Gradient instead of dark navy) */
        .cta-section {
            background: linear-gradient(135deg, var(--beige-bg) 0%, var(--pale-blue) 100%);
            color: var(--dark-color);
            padding: 70px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(61, 90, 128, 0.05);
        }

        .cta-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .cta-desc {
            font-size: 1rem;
            color: #4A5568;
            max-width: 600px;
            margin: 0 auto 30px auto;
        }

        /* Footer */
        .footer-section {
            background-color: var(--footer-bg);
            color: #475569;
            padding: 80px 0 30px 0;
            font-size: 0.85rem;
            border-top: 1px solid rgba(61, 90, 128, 0.08);
        }

        .footer-title {
            color: var(--dark-color);
            font-size: 0.95rem;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            color: #475569;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-social-links {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }

        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(61, 90, 128, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            background: var(--primary-color);
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 1px solid rgba(61, 90, 128, 0.08);
        }

        /* Scroll Top Button */
        .btn-scroll-top {
            position: fixed;
            bottom: 30px;
            right: -60px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #FFFFFF;
            border: none;
            outline: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-scroll-top.show {
            right: 30px;
        }

        .btn-scroll-top:hover {
            background: var(--primary-hover);
            color: #FFFFFF;
            transform: translateY(-3px);
        }
    </style>
</head>
<body id="beranda">

    <!-- 1. NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#beranda">
                <div class="me-2 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.jpg') }}" style="height: 40px; width: auto; border-radius: 4px;" alt="Logo">
                </div>
                <div>
                    <span class="navbar-brand-title">SIPRESMA 28</span>
                    <span class="navbar-brand-sub">SDN 28 Kinali</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: brightness(0.2);"></span>
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
                        <a class="nav-link" href="#guru">Staf & Guru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#informasi">Kabar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex justify-content-center mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-login-nav">Ke Dashboard</a>
                        @else
                            <a href="{{ url('/login') }}" class="btn-login-nav">Login</a>
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
                <div class="col-lg-7 text-start">
                    <span class="section-tag">Portal Resmi</span>
                    <h1 class="hero-title">Raih Impian & Ukir <span class="hero-accent">Prestasi Terbaik</span></h1>
                    <p class="hero-subtitle mb-4">Sistem Informasi Manajemen Nilai & Monitoring Prestasi (SIPRESMA 28) SD Negeri 28 Kinali. Mengintegrasikan pencatatan akademis secara digital, transparan, dan akuntabel.</p>
                    <div class="d-flex gap-3">
                        <a href="#profil" class="btn-blue-primary">Mulai Jelajah <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Stat items stacked on hero left/right card -->
                    <div class="p-4 rounded-3xl" style="background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(8px); border: 1px solid rgba(61,90,128,0.15); box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <h4 class="mb-4 fw-bold text-blue-accent" style="font-size: 1.1rem; border-bottom: 1px solid rgba(61,90,128,0.1); padding-bottom: 10px;">Statistik Sekolah</h4>
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="hero-stat-item">
                                    <div class="hero-stat-number">{{ $siswaCount ?? 350 }}</div>
                                    <div class="hero-stat-label">Siswa Aktif</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-item">
                                    <div class="hero-stat-number">{{ $guruCount ?? 25 }}</div>
                                    <div class="hero-stat-label">Guru & Staf</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-item">
                                    <div class="hero-stat-number">{{ $prestasiCount ?? 50 }}</div>
                                    <div class="hero-stat-label">Prestasi</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-item">
                                    <div class="hero-stat-number">{{ $mapelCount ?? 12 }}</div>
                                    <div class="hero-stat-label">Mapel</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. OVERLAPPING ACTION/SEARCH CARD -->
    <div class="container search-card-wrapper">
        <div class="overlapping-card">
            <div class="card-tabs">
                <button class="card-tab-btn active">Cari Informasi Cepat</button>
            </div>
            <form action="{{ url('/login') }}" method="GET" class="card-form-grid">
                <div class="form-group-custom">
                    <label>Kategori Data</label>
                    <select class="form-input-custom">
                        <option value="siswa">Siswa & Rapor</option>
                        <option value="prestasi">Daftar Prestasi</option>
                        <option value="kegiatan">Kegiatan Sekolah</option>
                    </select>
                </div>
                <div class="form-group-custom">
                    <label>Pencarian</label>
                    <input type="text" placeholder="Nama, NISN, atau Judul..." class="form-input-custom">
                </div>
                <div class="form-group-custom">
                    <label>Tahun Ajaran</label>
                    <select class="form-input-custom">
                        <option>2026/2027</option>
                        <option>2025/2026</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-search-submit w-100">
                        <i class="bi bi-search"></i> Cek Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. WHY CHOOSE US SECTION -->
    <section class="section-padding pt-0">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-tag">Mengapa Memilih Kami</span>
                <h2 class="section-title">Pendidikan Unggul & Berkarakter</h2>
                <p class="section-subtitle mx-auto">Kami bertekad menciptakan lingkungan belajar yang kondusif guna melahirkan generasi muda yang cerdas, kompetitif, dan berakhlak mulia.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h4 class="feature-card-title">Kurikulum Terpadu</h4>
                        <p class="feature-card-desc">Pembelajaran terintegrasi dengan pengembangan karakter kepribadian dan wawasan keilmuan yang luas.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 class="feature-card-title">Pendidik Profesional</h4>
                        <p class="feature-card-desc">Didampingi oleh dewan guru yang berpengalaman dan ahli di bidang pengembangan potensi siswa.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <h4 class="feature-card-title">Fasilitas Lengkap</h4>
                        <p class="feature-card-desc">Didukung oleh sarana kelas yang representatif, perpustakaan, dan penunjang kreativitas siswa.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <h4 class="feature-card-title">SIPRESMA 28</h4>
                        <p class="feature-card-desc">Penerapan teknologi rekap nilai rapor berbasis digital guna menjamin transparansi data belajar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. PROFIL SEKOLAH SECTION (Warm Beige backdrop) -->
    <section class="section-padding visi-misi-section" id="profil">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="section-tag">Profil Sekolah</span>
                    <h2 class="section-title">Visi & Misi Utama</h2>
                    <p class="lead text-muted mb-4">Melahirkan generasi penerus yang cerdas, berkarakter mulia, dan siap berkontribusi positif bagi bangsa.</p>
                    <p class="text-secondary mb-4">SD Negeri 28 Kinali terus berkomitmen untuk memberikan layanan pendidikan berkualitas terbaik. Melalui platform digital <strong>SIPRESMA 28</strong>, kami mendukung transparansi dan integrasi data penilaian rapor serta rekapitulasi prestasi yang dapat diakses dengan cepat dan aman oleh wali kelas, guru, dan admin.</p>
                    
                    <div class="visi-misi-list">
                        <div class="visi-misi-item">
                            <i class="bi bi-check-circle-fill visi-misi-icon"></i>
                            <div class="visi-misi-text">
                                <strong>Visi:</strong> Terwujudnya insan yang religius, unggul dalam prestasi, berkarakter mulia, dan peduli lingkungan.
                            </div>
                        </div>
                        <div class="visi-misi-item">
                            <i class="bi bi-check-circle-fill visi-misi-icon"></i>
                            <div class="visi-misi-text">
                                <strong>Misi 1:</strong> Melaksanakan proses belajar mengajar secara efektif, kreatif, inovatif, dan menyenangkan.
                            </div>
                        </div>
                        <div class="visi-misi-item">
                            <i class="bi bi-check-circle-fill visi-misi-icon"></i>
                            <div class="visi-misi-text">
                                <strong>Misi 2:</strong> Memfasilitasi pengembangan minat dan bakat secara berkesinambungan demi melahirkan siswa berprestasi.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-img-container">
                        <img src="{{ asset('images/suasana_sekolah.jpeg') }}" alt="Suasana Sekolah SDN 28 Kinali" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. GURU & STAF SECTION -->
    <section class="section-padding" id="guru">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-tag">Tenaga Pendidik</span>
                <h2 class="section-title">Guru & Staf Sekolah</h2>
                <p class="section-subtitle mx-auto">Pendidik profesional yang berdedikasi membimbing dan mengarahkan siswa mencapai puncak prestasi.</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse ($gurusSorted as $guru)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="guru-card">
                        <div class="guru-avatar-wrapper">
                            @if($guru->foto && file_exists(public_path('uploads/guru/' . $guru->foto)))
                                <img src="{{ asset('uploads/guru/' . $guru->foto) }}" alt="{{ $guru->nama }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($guru->nama) }}&background=CBD9E6&color=3D5A80" alt="{{ $guru->nama }}" class="w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <h4 class="guru-name">{{ $guru->nama }}</h4>
                        <p class="guru-role">{{ $guru->jabatan }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-5">
                    <p class="italic">Belum ada data guru atau staf terdaftar.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 7. NEWS & ACTIVITIES SECTION -->
    <section class="section-padding bg-white" id="informasi">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
                <div>
                    <span class="section-tag">Kabar Sekolah</span>
                    <h2 class="section-title mb-0">Berita & Aktivitas Terbaru</h2>
                </div>
                <a href="#beranda" class="btn btn-outline-dark rounded-pill px-4 py-2 text-xs fw-bold">Lihat Semua Berita</a>
            </div>
            
            <div class="row g-4">
                @forelse($kegiatan_terbaru->take(3) as $keg)
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            @if($keg->gambar)
                            <img src="{{ asset('storage/' . $keg->gambar) }}" alt="{{ $keg->nama_kegiatan }}" class="news-img">
                            @else
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=500&q=80" alt="{{ $keg->nama_kegiatan }}" class="news-img">
                            @endif
                            <span class="news-card-badge">{{ $keg->kategori }}</span>
                            <span class="news-card-date">{{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="news-card-body">
                            <h4 class="news-card-title">{{ $keg->nama_kegiatan }}</h4>
                            <p class="news-card-desc">{{ \Illuminate\Support\Str::limit($keg->deskripsi, 100) }}</p>
                            <button type="button" class="news-card-btn" data-bs-toggle="modal" data-bs-target="#modalKegiatan{{ $keg->id }}">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Static Event Card 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=500&q=80" alt="Lomba Sains" class="news-img">
                            <span class="news-card-badge">Perlombaan</span>
                            <span class="news-card-date">28 Jun 2026</span>
                        </div>
                        <div class="news-card-body">
                            <h4 class="news-card-title">Juara 1 Lomba Sains Tingkat Kabupaten</h4>
                            <p class="news-card-desc">Siswa SDN 28 Kinali kembali menorehkan prestasi membanggakan dengan meraih juara pertama pada ajang Kompetisi Sains tingkat Kabupaten.</p>
                            <button type="button" class="news-card-btn" data-bs-toggle="modal" data-bs-target="#modalStatic1">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Static Event Card 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=500&q=80" alt="Evaluasi Digital" class="news-img">
                            <span class="news-card-badge">Resmi</span>
                            <span class="news-card-date">15 Jun 2026</span>
                        </div>
                        <div class="news-card-body">
                            <h4 class="news-card-title">Penilaian Digital Menggunakan SIPRESMA 28</h4>
                            <p class="news-card-desc">Penerapan portal SIPRESMA 28 secara berkala mempermudah guru melakukan rekap nilai harian secara paperless dan terintegrasi.</p>
                            <button type="button" class="news-card-btn" data-bs-toggle="modal" data-bs-target="#modalStatic2">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Static Event Card 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=500&q=80" alt="Literasi Sabtu Pagi" class="news-img">
                            <span class="news-card-badge">Ekstrakurikuler</span>
                            <span class="news-card-date">08 Jun 2026</span>
                        </div>
                        <div class="news-card-body">
                            <h4 class="news-card-title">Gerakan Literasi Sekolah Tiap Sabtu Pagi</h4>
                            <p class="news-card-desc">Program membaca bersama buku bacaan non-akademik di lapangan sekolah guna menumbuhkan minat membaca sejak dini.</p>
                            <button type="button" class="news-card-btn" data-bs-toggle="modal" data-bs-target="#modalStatic3">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>



    <!-- 8.5 LOKASI & KONTAK -->
    <section class="section-padding bg-white" id="kontak">
        <div class="container">
            <div class="text-center mb-4">
                <span class="section-tag">Lokasi Sekolah</span>
                <h2 class="section-title">Temukan Lokasi Kami</h2>
                <p class="section-subtitle mx-auto">Kunjungi alamat resmi SD Negeri 28 Kinali secara langsung melalui penunjuk peta digital di bawah ini.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="profile-img-container" style="height: 350px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid rgba(61, 90, 128, 0.15);">
                        <!-- Sematan Google Maps SDN 28 Kinali, Sumatera Barat -->
                        <iframe 
                            src="https://maps.google.com/maps?q=-0.15333495126860688,99.76505095952322&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="100%" 
                            style="border:0; display:block;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. PRE-FOOTER CTA SECTION (Warm Beige & Sky Blue Gradient instead of dark navy) -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Siap Mengukir Prestasi Bersama Kami?</h2>
            <p class="cta-desc">Hubungi kami hari ini untuk informasi pendaftaran siswa baru, kurikulum sekolah, atau akses platform monitoring rapor SIPRESMA 28.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ url('/login') }}" class="btn-blue-primary">Mulai Akses Rapor <i class="bi bi-box-arrow-in-right"></i></a>
            </div>
        </div>
    </section>

    <!-- 10. FOOTER (Light Beige background) -->
    <footer class="footer-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-4 fw-bold text-blue-accent" style="font-size: 1.2rem;">SDN 28 KINALI</h5>
                    <p class="small mb-4" style="line-height: 1.6;">Portal integrasi monitoring nilai rapor dan rekapitulasi prestasi siswa. Mendukung tata kelola sekolah dasar yang transparan, modern, dan akuntabel.</p>
                    <div class="footer-social-links">
                        <a href="#beranda" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#beranda" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#beranda" class="social-btn"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#profil">Profil Sekolah</a></li>
                        <li><a href="#guru">Guru & Staf</a></li>
                        <li><a href="#informasi">Kabar Sekolah</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Sistem & Akses</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/login') }}">Login Guru & Admin</a></li>
                        <li><a href="{{ url('/login') }}">Monitoring Nilai Siswa</a></li>
                        <li><a href="{{ url('/login') }}">Akses Rapor Digital</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Kontak Sekolah</h5>
                    <p class="small mb-2"><i class="bi bi-geo-alt-fill text-blue-accent me-2"></i> Katiagan, Kecamatan Kinali, Kabupaten Pasaman Barat, Sumatera Barat, Indonesia</p>
                    <p class="small mb-0"><i class="bi bi-envelope-fill text-blue-accent me-2"></i> sdn28katiagan@gmail.com</p>
                </div>
            </div>

            <div class="row footer-bottom text-center">
                <div class="col-md-6 text-md-start mb-3 mb-md-0">
                    <span>&copy; 2026 SIPRESMA 28 - SD Negeri 28 Kinali. All Rights Reserved.</span>
                </div>
                <div class="col-md-6 text-md-end text-blue-accent fw-semibold">
                    <span>Didukung oleh Tim TI SD Negeri 28 Kinali</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <button class="btn-scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        <i class="bi bi-arrow-up-short fs-4"></i>
    </button>

    <!-- Modals Detail Kegiatan Dinamis -->
    @foreach($kegiatan_terbaru as $keg)
    <div class="modal fade text-dark" id="modalKegiatan{{ $keg->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $keg->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge text-uppercase tracking-wider text-[10px] me-2" style="background: var(--primary-color);">{{ $keg->kategori }}</span>
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
                        <div><i class="bi bi-tag-fill text-blue-accent"></i> <span class="text-capitalize">{{ $keg->jenis_kegiatan }}</span></div>
                        <div>|</div>
                        <div><i class="bi bi-calendar3 text-blue-accent"></i> Semester: {{ $keg->semester_aktif }}</div>
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

    <!-- Static Modals -->
    <div class="modal fade text-dark" id="modalStatic1" tabindex="-1" aria-labelledby="modalLabelStatic1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge text-uppercase tracking-wider text-[10px] me-2" style="background: var(--primary-color);">Perlombaan</span>
                        <span class="text-secondary small">28 Juni 2026</span>
                    </div>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3 text-start">
                    <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic1">Juara 1 Lomba Sains Tingkat Kabupaten</h3>
                    <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px;">
                        <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80" alt="Lomba Sains" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                    </div>
                    <p class="text-secondary lh-lg">Siswa SDN 28 Kinali kembali menorehkan prestasi membanggakan dengan meraih juara pertama pada ajang Kompetisi Sains tingkat Kabupaten. Kompetisi ini diikuti oleh puluhan sekolah dasar dari berbagai kecamatan. Sekolah sangat bangga dan berkomitmen untuk terus membimbing bakat anak secara maksimal demi prestasi masa depan.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-dark" id="modalStatic2" tabindex="-1" aria-labelledby="modalLabelStatic2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge text-uppercase tracking-wider text-[10px] me-2" style="background: var(--primary-color);">Resmi</span>
                        <span class="text-secondary small">15 Juni 2026</span>
                    </div>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3 text-start">
                    <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic2">Penilaian Digital Menggunakan SIPRESMA 28</h3>
                    <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px;">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80" alt="Evaluasi Digital" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                    </div>
                    <p class="text-secondary lh-lg">Penerapan portal SIPRESMA 28 secara berkala mempermudah guru melakukan rekap nilai harian secara paperless dan terintegrasi. Wali murid juga dapat memantau capaian belajar anak secara berkala dan langsung. Inovasi digital ini mendukung target efisiensi tata kelola sekolah dasar yang unggul dan mandiri.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-dark" id="modalStatic3" tabindex="-1" aria-labelledby="modalLabelStatic3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge text-uppercase tracking-wider text-[10px] me-2" style="background: var(--primary-color);">Ekstrakurikuler</span>
                        <span class="text-secondary small">08 Juni 2026</span>
                    </div>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3 text-start">
                    <h3 class="modal-title fw-bold mb-3" id="modalLabelStatic3">Gerakan Literasi Sekolah Tiap Sabtu Pagi</h3>
                    <div class="mb-4 text-center rounded-3 overflow-hidden" style="max-height: 400px;">
                        <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=800&q=80" alt="Kegiatan Literasi" class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;">
                    </div>
                    <p class="text-secondary lh-lg">Program membaca bersama buku bacaan non-akademik di lapangan sekolah guna menumbuhkan minat membaca sejak dini. Gerakan literasi ini diikuti oleh seluruh siswa dari Kelas I hingga Kelas VI, staf pengajar, dan didampingi langsung oleh Kepala Sekolah. Kami meyakini minat baca yang tinggi melahirkan generasi yang cerdas.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
    </script>
</body>
</html>
