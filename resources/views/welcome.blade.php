<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPRESMA 28 | SD Negeri 28 Kinali</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #0f172a;
            --navy-2: #172554;
            --blue-soft: #eff6ff;
            --blue-light: #dbeafe;

            --white: #ffffff;
            --bg: #f8fafc;
            --text: #0f172a;
            --text-soft: #64748b;
            --border: #e2e8f0;

            --gold: #f59e0b;

            --font-title: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'DM Sans', sans-serif;

            --shadow-sm: 0 4px 15px rgba(15, 23, 42, .05);
            --shadow-md: 0 15px 40px rgba(15, 23, 42, .08);
            --shadow-lg: 0 25px 70px rgba(15, 23, 42, .12);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: var(--font-body);
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-title);
        }

        a {
            text-decoration: none;
        }

        ::selection {
            background: var(--primary);
            color: white;
        }

        /* =========================================================
           TOP BAR
        ========================================================= */

        .topbar {
            background: var(--navy);
            color: rgba(255,255,255,.8);
            font-size: .78rem;
            padding: 8px 0;
        }

        .topbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-left,
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .topbar-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar-item i {
            color: #93c5fd;
        }

        .topbar-social {
            color: rgba(255,255,255,.75);
            transition: .25s ease;
        }

        .topbar-social:hover {
            color: white;
            transform: translateY(-2px);
        }

        /* =========================================================
           NAVBAR
        ========================================================= */

        .main-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(226,232,240,.75);
            transition: .3s ease;
        }

        .main-navbar.scrolled {
            box-shadow: var(--shadow-sm);
        }

        .navbar {
            padding: 16px 0;
            transition: .3s ease;
        }

        .main-navbar.scrolled .navbar {
            padding: 10px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .brand-logo {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 13px;
            box-shadow: 0 5px 15px rgba(37,99,235,.15);
        }

        .brand-text {
            line-height: 1.1;
        }

        .brand-name {
            display: block;
            font-family: var(--font-title);
            font-weight: 800;
            color: var(--navy);
            font-size: 1.05rem;
            letter-spacing: -.3px;
        }

        .brand-sub {
            display: block;
            color: var(--text-soft);
            font-size: .68rem;
            margin-top: 4px;
        }

        .navbar-nav {
            gap: 4px;
        }

        .nav-link {
            color: #475569 !important;
            font-weight: 600;
            font-size: .87rem;
            padding: 9px 14px !important;
            border-radius: 10px;
            transition: .25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
            background: var(--blue-soft);
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            background: var(--navy);
            color: white !important;

            border-radius: 12px;
            padding: 11px 19px;

            font-size: .84rem;
            font-weight: 700;

            transition: .25s ease;
            box-shadow: 0 8px 20px rgba(15,23,42,.12);
        }

        .btn-login:hover {
            background: var(--primary);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(37,99,235,.2);
        }

        /* =========================================================
           HERO
        ========================================================= */

        .hero {
            position: relative;
            min-height: 690px;
            display: flex;
            align-items: center;

            background-image:
                linear-gradient(
                    90deg,
                    rgba(2,6,23,.94) 0%,
                    rgba(15,23,42,.80) 38%,
                    rgba(15,23,42,.30) 75%,
                    rgba(15,23,42,.15) 100%
                ),
                url("{{ asset('images/gedung_sd.jpg') }}");

            background-size: cover;
            background-position: center;

            overflow: visible;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(37,99,235,.18);
            filter: blur(90px);
            top: -200px;
            right: -100px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(59,130,246,.15);
            filter: blur(80px);
            bottom: -150px;
            left: 35%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 90px 0 150px;
        }

        .hero-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 22px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 8px 13px;

            border-radius: 50px;

            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.18);

            color: white;
            font-size: .72rem;
            font-weight: 700;

            backdrop-filter: blur(10px);
        }

        .hero-badge.gold {
            background: rgba(245,158,11,.18);
            border-color: rgba(245,158,11,.35);
            color: #fde68a;
        }

        .hero-title {
            color: white;
            font-size: clamp(2.7rem, 5vw, 4.8rem);
            line-height: 1.05;
            font-weight: 800;
            letter-spacing: -2.5px;
            max-width: 780px;
            margin-bottom: 25px;
        }

        .hero-title span {
            background: linear-gradient(90deg,#60a5fa,#bfdbfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            max-width: 650px;
            color: rgba(255,255,255,.76);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            padding: 14px 22px;

            border-radius: 13px;

            background: var(--primary);
            color: white;

            font-size: .88rem;
            font-weight: 700;

            box-shadow: 0 12px 30px rgba(37,99,235,.3);

            transition: .3s ease;
        }

        .btn-hero-primary:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            padding: 14px 22px;

            border-radius: 13px;

            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.25);

            color: white;

            font-size: .88rem;
            font-weight: 700;

            backdrop-filter: blur(10px);

            transition: .3s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,.15);
            color: white;
            transform: translateY(-3px);
        }

        .hero-trust {
            margin-top: 35px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,.65);
            font-size: .78rem;
        }

        .trust-line {
            width: 35px;
            height: 1px;
            background: rgba(255,255,255,.4);
        }

        /* =========================================================
           FLOATING STATISTICS
        ========================================================= */

        .stats-wrapper {
            position: absolute;
            z-index: 5;
            bottom: -65px;
            left: 0;
            right: 0;
        }

        .stats-panel {
            background: rgba(255,255,255,.97);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 24px;
            padding: 12px;
            box-shadow: var(--shadow-lg);
        }

        .stat-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;

            padding: 18px 20px;

            border-radius: 17px;

            transition: .3s ease;
        }

        .stat-card:hover {
            background: var(--blue-soft);
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: var(--blue-soft);
            color: var(--primary);

            font-size: 1.15rem;
        }

        .stat-number {
            font-family: var(--font-title);
            font-size: 1.55rem;
            line-height: 1;
            font-weight: 800;
            color: var(--navy);
        }

        .stat-label {
            margin-top: 5px;
            color: var(--text-soft);
            font-size: .72rem;
            font-weight: 600;
        }

        /* =========================================================
           SECTION
        ========================================================= */

        .section {
            padding: 110px 0;
        }

        .section-soft {
            background: var(--bg);
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            color: var(--primary);

            font-size: .7rem;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 1.5px;

            margin-bottom: 12px;
        }

        .section-tag::before {
            content: "";
            width: 25px;
            height: 2px;
            background: var(--primary);
            border-radius: 10px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.9rem);
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.15;
            color: var(--navy);
            margin-bottom: 15px;
        }

        .section-subtitle {
            max-width: 650px;
            color: var(--text-soft);
            line-height: 1.8;
            font-size: .92rem;
        }

        /* =========================================================
           QUICK ACCESS
        ========================================================= */

        .quick-section {
            padding-top: 145px;
            padding-bottom: 80px;
        }

        .quick-card {
            position: relative;
            display: block;

            height: 100%;

            padding: 27px;

            background: white;

            border: 1px solid var(--border);
            border-radius: 20px;

            box-shadow: var(--shadow-sm);

            overflow: hidden;

            transition: .3s ease;
        }

        .quick-card::after {
            content: "";
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: var(--blue-soft);
            right: -30px;
            bottom: -35px;
            transition: .3s ease;
        }

        .quick-card:hover {
            transform: translateY(-7px);
            border-color: #bfdbfe;
            box-shadow: var(--shadow-md);
        }

        .quick-card:hover::after {
            transform: scale(1.4);
        }

        .quick-icon {
            width: 50px;
            height: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background: var(--blue-soft);
            color: var(--primary);

            font-size: 1.2rem;

            margin-bottom: 20px;
        }

        .quick-title {
            font-family: var(--font-title);
            font-weight: 750;
            font-size: 1rem;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .quick-text {
            color: var(--text-soft);
            font-size: .8rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .quick-arrow {
            position: absolute;
            z-index: 2;
            right: 22px;
            top: 24px;

            color: #94a3b8;

            transition: .25s ease;
        }

        .quick-card:hover .quick-arrow {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* =========================================================
           FEATURE
        ========================================================= */

        .feature-card {
            height: 100%;
            padding: 32px;

            background: white;

            border: 1px solid var(--border);
            border-radius: 22px;

            transition: .3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: #bfdbfe;
        }

        .feature-number {
            color: #cbd5e1;
            font-family: var(--font-title);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .feature-icon {
            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--navy);
            color: white;

            border-radius: 16px;

            font-size: 1.35rem;

            margin-bottom: 22px;

            transition: .3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            transform: rotate(-4deg);
        }

        .feature-title {
            font-size: 1.05rem;
            font-weight: 750;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .feature-text {
            color: var(--text-soft);
            font-size: .82rem;
            line-height: 1.7;
            margin: 0;
        }

        /* =========================================================
           PROFILE
        ========================================================= */

        .profile-section {
            background: #f8fafc;
        }

        .profile-image-box {
            position: relative;
            border-radius: 28px;
            overflow: visible;
        }

        .profile-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 28px;
            box-shadow: var(--shadow-lg);
        }

        .profile-floating {
            position: absolute;
            right: 20px;
            bottom: 20px;

            width: 220px;

            background: rgba(255,255,255,.95);
            backdrop-filter: blur(15px);

            border: 1px solid rgba(255,255,255,.9);

            padding: 20px;

            border-radius: 18px;

            box-shadow: var(--shadow-md);
        }

        .profile-floating-icon {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--blue-soft);
            color: var(--primary);

            border-radius: 11px;

            margin-bottom: 12px;
        }

        .profile-floating strong {
            display: block;
            font-family: var(--font-title);
            font-size: .85rem;
            margin-bottom: 4px;
        }

        .profile-floating span {
            display: block;
            color: var(--text-soft);
            font-size: .7rem;
            line-height: 1.5;
        }

        .profile-copy {
            color: var(--text-soft);
            line-height: 1.85;
            font-size: .9rem;
        }

        .vision-box {
            margin-top: 28px;
            padding: 22px;

            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
        }

        .vision-title {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: .85rem;
            font-weight: 800;

            color: var(--navy);

            margin-bottom: 10px;
        }

        .vision-title i {
            color: var(--primary);
        }

        .vision-text {
            color: var(--text-soft);
            font-size: .82rem;
            line-height: 1.7;
            margin: 0;
        }

        .mission-list {
            margin-top: 15px;
            padding: 0;
            list-style: none;
        }

        .mission-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;

            color: var(--text-soft);
            font-size: .82rem;
            line-height: 1.6;

            margin-bottom: 10px;
        }

        .mission-list i {
            color: var(--primary);
            margin-top: 3px;
        }

        /* =========================================================
           GURU
        ========================================================= */

        .guru-card {
            height: 100%;
            background: #F2EFE7; /* Cream background */
            border: 1px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            transition: .3s ease;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .guru-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: #bfdbfe;
        }

        .guru-image-wrapper {
            position: relative;
            height: 200px; /* ~h-52 */
            padding: 8px; /* p-2 */
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .guru-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 15px;
        }

        .guru-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .guru-name {
            font-size: .95rem;
            font-weight: 750;
            color: var(--navy);
            margin-bottom: 5px;
        }

        .guru-role {
            color: var(--primary);
            font-size: .72rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        /* =========================================================
           NEWS
        ========================================================= */

        .news-card {
            height: 100%;
            overflow: hidden;

            background: white;

            border: 1px solid var(--border);
            border-radius: 22px;

            transition: .3s ease;
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .news-image-wrapper {
            position: relative;
            height: 235px;
            overflow: hidden;
        }

        .news-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .5s ease;
        }

        .news-card:hover .news-image {
            transform: scale(1.06);
        }

        .news-category {
            position: absolute;
            left: 15px;
            top: 15px;

            background: rgba(15,23,42,.82);
            backdrop-filter: blur(8px);

            color: white;

            padding: 6px 11px;

            border-radius: 50px;

            font-size: .63rem;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .news-date {
            position: absolute;
            right: 15px;
            bottom: 15px;

            background: rgba(255,255,255,.92);

            color: var(--navy);

            padding: 6px 11px;

            border-radius: 50px;

            font-size: .63rem;
            font-weight: 700;
        }

        .news-content {
            padding: 24px;
        }

        .news-title {
            font-size: 1rem;
            font-weight: 750;
            line-height: 1.45;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .news-description {
            color: var(--text-soft);
            font-size: .78rem;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .news-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            color: var(--primary);

            border: 0;
            background: transparent;

            padding: 0;

            font-size: .75rem;
            font-weight: 750;

            transition: .25s ease;
        }

        .news-link:hover {
            gap: 11px;
            color: var(--primary-dark);
        }

        /* =========================================================
           CONTACT
        ========================================================= */

        .contact-card {
            overflow: hidden;

            background: white;

            border: 1px solid var(--border);
            border-radius: 28px;

            box-shadow: var(--shadow-md);
        }

        .contact-info {
            height: 100%;
            padding: 45px;
        }

        .contact-title {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 15px;
        }

        .contact-description {
            color: var(--text-soft);
            font-size: .84rem;
            line-height: 1.75;
            margin-bottom: 30px;
        }

        .contact-item {
            display: flex;
            gap: 14px;
            margin-bottom: 22px;
        }

        .contact-icon {
            flex: 0 0 42px;

            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--blue-soft);
            color: var(--primary);

            border-radius: 12px;
        }

        .contact-label {
            display: block;
            font-size: .67rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #94a3b8;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .contact-value {
            display: block;
            color: var(--navy);
            font-size: .8rem;
            line-height: 1.6;
        }

        .map-wrapper {
            min-height: 470px;
            height: 100%;
        }

        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            min-height: 470px;
            border: 0;
            display: block;
        }

        /* =========================================================
           CTA
        ========================================================= */

        .cta {
            position: relative;
            overflow: hidden;

            padding: 95px 0;

            background:
                radial-gradient(circle at 85% 20%, rgba(59,130,246,.35), transparent 30%),
                radial-gradient(circle at 10% 100%, rgba(37,99,235,.25), transparent 30%),
                linear-gradient(135deg,#0f172a,#172554);

            color: white;
        }

        .cta::before {
            content: "";
            position: absolute;
            width: 450px;
            height: 450px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 50%;
            right: -150px;
            top: -200px;
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .cta-title {
            color: white;
            font-size: clamp(2rem,4vw,3rem);
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 15px;
        }

        .cta-description {
            max-width: 600px;
            margin: 0 auto 28px;

            color: rgba(255,255,255,.7);

            font-size: .9rem;
            line-height: 1.7;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            padding: 14px 23px;

            background: white;
            color: var(--navy);

            border-radius: 13px;

            font-size: .82rem;
            font-weight: 800;

            transition: .3s ease;
        }

        .btn-cta:hover {
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,.2);
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            background: #020617;
            color: rgba(255,255,255,.65);
            padding: 70px 0 25px;
        }

        .footer-brand {
            color: white;
            font-family: var(--font-title);
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .footer-description {
            max-width: 360px;
            font-size: .78rem;
            line-height: 1.8;
            color: rgba(255,255,255,.5);
        }

        .footer-title {
            color: white;
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .footer-links {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 11px;
        }

        .footer-links a {
            color: rgba(255,255,255,.55);
            font-size: .78rem;
            transition: .25s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 3px;
        }

        .footer-contact {
            font-size: .76rem;
            line-height: 1.7;
            color: rgba(255,255,255,.55);
            margin-bottom: 12px;
        }

        .footer-contact i {
            color: #60a5fa;
            margin-right: 7px;
        }

        .social-links {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .social-link {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            color: rgba(255,255,255,.65);
            background: rgba(255,255,255,.06);

            transition: .25s ease;
        }

        .social-link:hover {
            color: white;
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 23px;

            border-top: 1px solid rgba(255,255,255,.08);

            font-size: .7rem;
            color: rgba(255,255,255,.4);
        }

        /* =========================================================
           SCROLL TOP
        ========================================================= */

        .scroll-top {
            position: fixed;
            right: 25px;
            bottom: 25px;

            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 13px;

            background: var(--primary);
            color: white;

            box-shadow: 0 10px 25px rgba(37,99,235,.25);

            opacity: 0;
            visibility: hidden;
            transform: translateY(15px);

            transition: .3s ease;

            z-index: 999;
        }

        .scroll-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* =========================================================
           REVEAL ANIMATION
        ========================================================= */

        .reveal {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* =========================================================
           MODAL
        ========================================================= */

        .modal-content {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
        }

        .modal-header {
            padding: 24px 25px 10px;
        }

        .modal-body {
            padding: 15px 25px 25px;
        }

        .modal-footer {
            padding: 0 25px 25px;
        }

        .modal-news-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 16px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .topbar {
                display: none;
            }

            .navbar-collapse {
                padding-top: 15px;
            }

            .navbar-nav {
                gap: 3px;
            }

            .nav-link {
                text-align: center;
            }

            .btn-login-wrapper {
                margin-top: 10px;
            }

            .hero {
                min-height: 650px;
            }

            .hero-content {
                padding: 75px 0 145px;
            }

            .hero-title {
                font-size: 3rem;
            }

            .stats-wrapper {
                bottom: -120px;
            }

            .quick-section {
                padding-top: 180px;
            }

            .profile-image {
                height: 430px;
            }

            .profile-floating {
                right: 20px;
            }
        }

        @media (max-width: 767px) {

            .brand-logo {
                width: 40px;
                height: 40px;
            }

            .brand-name {
                font-size: .95rem;
            }

            .hero {
                min-height: 690px;
                background-position: 60% center;
            }

            .hero-content {
                padding: 70px 0 185px;
            }

            .hero-title {
                font-size: 2.45rem;
                letter-spacing: -1.5px;
            }

            .hero-description {
                font-size: .88rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                justify-content: center;
            }

            .stats-wrapper {
                bottom: -215px;
            }

            .stats-panel {
                padding: 7px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: .95rem;
            }

            .stat-number {
                font-size: 1.2rem;
            }

            .stat-label {
                font-size: .62rem;
            }

            .quick-section {
                padding-top: 270px;
            }

            .section {
                padding: 75px 0;
            }

            .section-title {
                font-size: 2rem;
            }

            .profile-image {
                height: 350px;
            }

            .profile-floating {
                position: relative;
                right: auto;
                bottom: auto;
                width: 100%;
                margin-top: -35px;
                margin-left: 15px;
                width: calc(100% - 30px);
            }

            .contact-info {
                padding: 30px;
            }

            .map-wrapper,
            .map-wrapper iframe {
                min-height: 350px;
            }

            .cta {
                padding: 75px 0;
            }

            .footer {
                padding-top: 55px;
            }
        }

        @media (max-width: 575px) {

            .hero-title {
                font-size: 2.15rem;
            }

            .hero-badge {
                font-size: .62rem;
            }

            .hero-description {
                font-size: .82rem;
            }

            .stats-wrapper {
                bottom: -255px;
            }

            .quick-section {
                padding-top: 310px;
            }

            .stat-card {
                gap: 8px;
            }

            .stat-number {
                font-size: 1.05rem;
            }

            .stat-label {
                font-size: .56rem;
            }
        }
    </style>
</head>

<body id="beranda">

<!-- =========================================================
     TOPBAR
========================================================= -->

<div class="topbar">
    <div class="container">
        <div class="topbar-content">

            <div class="topbar-left">

                <div class="topbar-item">
                    <i class="bi bi-envelope"></i>
                    <span>sdn28katiagan@gmail.com</span>
                </div>

                <div class="topbar-item">
                    <i class="bi bi-building"></i>
                    <span>NPSN: 10305963</span>
                </div>

                <div class="topbar-item">
                    <i class="bi bi-geo-alt"></i>
                    <span>Kinali, Pasaman Barat</span>
                </div>

            </div>

            <div class="topbar-right">

                <a href="#beranda" class="topbar-social">
                    <i class="bi bi-facebook"></i>
                </a>

                <a href="#beranda" class="topbar-social">
                    <i class="bi bi-instagram"></i>
                </a>

                <a href="#beranda" class="topbar-social">
                    <i class="bi bi-youtube"></i>
                </a>

            </div>

        </div>
    </div>
</div>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="main-navbar" id="mainNavbar">

    <div class="container">

        <div class="navbar navbar-expand-lg">

            <a href="#beranda" class="brand">

                <img
                    src="{{ asset('images/logo.jpg') }}"
                    alt="Logo SD Negeri 28 Kinali"
                    class="brand-logo"
                >

                <div class="brand-text">
                    <span class="brand-name">SIPRESMA 28</span>
                    <span class="brand-sub">SD Negeri 28 Kinali</span>
                </div>

            </a>


            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent"
                aria-label="Toggle navigation"
            >
                <i class="bi bi-list fs-3"></i>
            </button>


            <div class="collapse navbar-collapse" id="navbarContent">

                <ul class="navbar-nav mx-auto mt-3 mt-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#profil">
                            Profil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#guru">
                            Guru & Staf
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#informasi">
                            Kabar
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">
                            Kontak
                        </a>
                    </li>

                </ul>


                <div class="btn-login-wrapper mt-3 mt-lg-0">

                    @if (Route::has('login'))

                        @auth

                            <a
                                href="{{ url('/dashboard') }}"
                                class="btn-login"
                            >
                                <i class="bi bi-grid-1x2-fill"></i>
                                Ke Dashboard
                            </a>

                        @else

                            <a
                                href="{{ url('/login') }}"
                                class="btn-login"
                            >
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </a>

                        @endauth

                    @endif

                </div>

            </div>

        </div>

    </div>

</nav>


<!-- =========================================================
     HERO
========================================================= -->

<header class="hero" id="hero">

    <div class="container">

        <div class="hero-content">

            <div class="row">

                <div class="col-lg-8">

                    <div class="hero-badges">

                        <span class="hero-badge">
                            <i class="bi bi-stars"></i>
                            PORTAL RESMI SEKOLAH
                        </span>

                        <span class="hero-badge gold">
                            <i class="bi bi-award-fill"></i>
                            AKREDITASI B
                        </span>

                    </div>


                    <h1 class="hero-title">
                        Raih Impian,
                        <br>
                        <span>Ukir Prestasi Terbaik.</span>
                    </h1>


                    <p class="hero-description">
                        SIPRESMA 28 merupakan Sistem Informasi Manajemen
                        Nilai dan Monitoring Prestasi SD Negeri 28 Kinali
                        yang membantu pengelolaan data akademik dan prestasi
                        siswa secara digital, transparan, dan terintegrasi.
                    </p>


                    <div class="hero-buttons">

                        <a
                            href="{{ url('/login') }}"
                            class="btn-hero-primary"
                        >
                            Masuk ke SIPRESMA
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <a
                            href="#profil"
                            class="btn-hero-secondary"
                        >
                            Kenali Sekolah
                            <i class="bi bi-arrow-down"></i>
                        </a>

                    </div>


                    <div class="hero-trust">

                        <span class="trust-line"></span>

                        <span>
                            Pendidikan • Prestasi • Teknologi
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- FLOATING STATISTICS -->

    <div class="stats-wrapper">

        <div class="container">

            <div class="stats-panel">

                <div class="row g-1">

                    <!-- SISWA -->

                    <div class="col-6 col-lg-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>

                            <div>
                                <div class="stat-number">
                                    {{ $siswaCount ?? 69 }}
                                </div>

                                <div class="stat-label">
                                    Siswa Aktif
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- GURU -->

                    <div class="col-6 col-lg-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-person-workspace"></i>
                            </div>

                            <div>
                                <div class="stat-number">
                                    {{ $guruCount ?? 13 }}
                                </div>

                                <div class="stat-label">
                                    Guru & Staf
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- PRESTASI -->

                    <div class="col-6 col-lg-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-trophy-fill"></i>
                            </div>

                            <div>
                                <div class="stat-number">
                                    {{ $prestasiCount ?? 4 }}
                                </div>

                                <div class="stat-label">
                                    Prestasi
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- MAPEL -->

                    <div class="col-6 col-lg-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-book-half"></i>
                            </div>

                            <div>
                                <div class="stat-number">
                                    {{ $mapelCount ?? 10 }}
                                </div>

                                <div class="stat-label">
                                    Mata Pelajaran
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>


<!-- =========================================================
     QUICK ACCESS
========================================================= -->

<section class="quick-section">

    <div class="container">

        <div class="text-center mb-5 reveal">

            <div class="section-tag justify-content-center">
                Akses Cepat
            </div>

            <h2 class="section-title">
                Semua Informasi dalam Satu Portal
            </h2>

            <p class="section-subtitle mx-auto">
                SIPRESMA 28 membantu sekolah mengelola informasi akademik,
                prestasi, guru, dan kegiatan secara lebih terstruktur.
            </p>

        </div>


        <div class="row g-4">

            <!-- NILAI -->

            <div class="col-lg-3 col-md-6 reveal">

                <a href="{{ url('/login') }}" class="quick-card">

                    <i class="bi bi-arrow-up-right quick-arrow"></i>

                    <div class="quick-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>

                    <h3 class="quick-title">
                        Monitoring Nilai
                    </h3>

                    <p class="quick-text">
                        Kelola dan pantau perkembangan nilai akademik siswa
                        secara digital.
                    </p>

                </a>

            </div>


            <!-- PRESTASI -->

            <div class="col-lg-3 col-md-6 reveal">

                <a href="{{ url('/login') }}" class="quick-card">

                    <i class="bi bi-arrow-up-right quick-arrow"></i>

                    <div class="quick-icon">
                        <i class="bi bi-trophy"></i>
                    </div>

                    <h3 class="quick-title">
                        Prestasi Siswa
                    </h3>

                    <p class="quick-text">
                        Dokumentasikan dan pantau berbagai pencapaian
                        akademik maupun non-akademik.
                    </p>

                </a>

            </div>


            <!-- GURU -->

            <div class="col-lg-3 col-md-6 reveal">

                <a href="#guru" class="quick-card">

                    <i class="bi bi-arrow-up-right quick-arrow"></i>

                    <div class="quick-icon">
                        <i class="bi bi-person-video3"></i>
                    </div>

                    <h3 class="quick-title">
                        Guru & Staf
                    </h3>

                    <p class="quick-text">
                        Kenali tenaga pendidik yang mendukung proses
                        pembelajaran siswa.
                    </p>

                </a>

            </div>


            <!-- KEGIATAN -->

            <div class="col-lg-3 col-md-6 reveal">

                <a href="#informasi" class="quick-card">

                    <i class="bi bi-arrow-up-right quick-arrow"></i>

                    <div class="quick-icon">
                        <i class="bi bi-newspaper"></i>
                    </div>

                    <h3 class="quick-title">
                        Kabar Sekolah
                    </h3>

                    <p class="quick-text">
                        Temukan berita dan kegiatan terbaru SD Negeri
                        28 Kinali.
                    </p>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FEATURE
========================================================= -->

<section class="section section-soft">

    <div class="container">

        <div class="text-center mb-5 reveal">

            <div class="section-tag justify-content-center">
                Keunggulan
            </div>

            <h2 class="section-title">
                Pendidikan Unggul & Berkarakter
            </h2>

            <p class="section-subtitle mx-auto">
                Membangun lingkungan pendidikan yang mendukung perkembangan
                akademik, karakter, kreativitas, dan prestasi siswa.
            </p>

        </div>


        <div class="row g-4">

            <!-- 01 -->

            <div class="col-lg-3 col-md-6 reveal">

                <div class="feature-card">

                    <div class="feature-number">
                        01 / PENDIDIKAN
                    </div>

                    <div class="feature-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>

                    <h3 class="feature-title">
                        Kurikulum Terpadu
                    </h3>

                    <p class="feature-text">
                        Pembelajaran terintegrasi dengan pengembangan
                        karakter dan wawasan keilmuan siswa.
                    </p>

                </div>

            </div>


            <!-- 02 -->

            <div class="col-lg-3 col-md-6 reveal">

                <div class="feature-card">

                    <div class="feature-number">
                        02 / PENDIDIK
                    </div>

                    <div class="feature-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h3 class="feature-title">
                        Pendidik Profesional
                    </h3>

                    <p class="feature-text">
                        Didukung tenaga pendidik yang berdedikasi
                        membimbing dan mengembangkan potensi siswa.
                    </p>

                </div>

            </div>


            <!-- 03 -->

            <div class="col-lg-3 col-md-6 reveal">

                <div class="feature-card">

                    <div class="feature-number">
                        03 / FASILITAS
                    </div>

                    <div class="feature-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <h3 class="feature-title">
                        Lingkungan Belajar
                    </h3>

                    <p class="feature-text">
                        Lingkungan sekolah yang mendukung proses belajar,
                        kreativitas, dan pengembangan karakter.
                    </p>

                </div>

            </div>


            <!-- 04 -->

            <div class="col-lg-3 col-md-6 reveal">

                <div class="feature-card">

                    <div class="feature-number">
                        04 / TEKNOLOGI
                    </div>

                    <div class="feature-icon">
                        <i class="bi bi-cpu-fill"></i>
                    </div>

                    <h3 class="feature-title">
                        SIPRESMA 28
                    </h3>

                    <p class="feature-text">
                        Pengelolaan nilai dan prestasi siswa secara digital,
                        terintegrasi, dan lebih efisien.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     PROFILE
========================================================= -->

<section class="section profile-section" id="profil">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- IMAGE -->

            <div class="col-lg-6 reveal">

                <div class="profile-image-box">

                    <img
                        src="{{ asset('images/suasana_sekolah.jpeg') }}"
                        alt="Suasana SD Negeri 28 Kinali"
                        class="profile-image"
                    >

                </div>

            </div>


            <!-- CONTENT -->

            <div class="col-lg-6 reveal">

                <div class="section-tag">
                    Profil Sekolah
                </div>

                <h2 class="section-title">
                    Tumbuh Bersama,
                    <br>
                    Meraih Prestasi
                </h2>

                <p class="profile-copy">
                    SD Negeri 28 Kinali terus berkomitmen memberikan
                    layanan pendidikan berkualitas bagi peserta didik.
                    Melalui pemanfaatan teknologi, SIPRESMA 28 hadir
                    untuk mendukung pengelolaan data nilai dan prestasi
                    secara lebih transparan, cepat, dan terintegrasi.
                </p>


                <!-- VISION -->

                <div class="vision-box">

                    <div class="vision-title">

                        <i class="bi bi-eye-fill"></i>

                        Visi Sekolah

                    </div>

                    <p class="vision-text">
                        Terwujudnya insan yang religius, unggul dalam
                        prestasi, berkarakter mulia, dan peduli lingkungan.
                    </p>


                    <ul class="mission-list">

                        <li>
                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Melaksanakan proses belajar mengajar secara
                                efektif, kreatif, inovatif, dan menyenangkan.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Memfasilitasi pengembangan minat dan bakat
                                siswa secara berkesinambungan.
                            </span>
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Mendorong siswa untuk meraih prestasi
                                akademik maupun non-akademik.
                            </span>
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     GURU
========================================================= -->

<section class="section" id="guru">

    <div class="container">

        <div class="text-center mb-5 reveal">

            <div class="section-tag justify-content-center">
                Tenaga Pendidik
            </div>

            <h2 class="section-title">
                Guru & Staf Sekolah
            </h2>

            <p class="section-subtitle mx-auto">
                Tenaga pendidik yang berdedikasi untuk membimbing,
                mendampingi, dan mengembangkan potensi setiap siswa.
            </p>

        </div>


        <div class="row g-4 justify-content-center">

            @forelse ($gurusSorted as $guru)

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 reveal">

                    <div class="guru-card">

                        <div class="guru-image-wrapper">

                            @if($guru->foto && file_exists(public_path('uploads/guru/' . $guru->foto)))

                                <img
                                    src="{{ asset('uploads/guru/' . $guru->foto) }}"
                                    alt="{{ $guru->nama }}"
                                    class="guru-image"
                                >

                            @else

                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode($guru->nama) }}&background=eff6ff&color=2563eb&size=512"
                                    alt="{{ $guru->nama }}"
                                    class="guru-image"
                                >

                            @endif

                        </div>


                        <div class="guru-content">

                            <h3 class="guru-name">
                                {{ $guru->nama }}
                            </h3>

                            <p class="guru-role">
                                {{ $guru->jabatan }}
                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="text-center py-5">

                        <i class="bi bi-person-x fs-1 text-secondary"></i>

                        <p class="text-secondary mt-3">
                            Belum ada data guru atau staf terdaftar.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


<!-- =========================================================
     NEWS
========================================================= -->

<section class="section section-soft" id="informasi">

    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5 reveal">

            <div>

                <div class="section-tag">
                    Kabar Sekolah
                </div>

                <h2 class="section-title mb-0">
                    Berita & Aktivitas Terbaru
                </h2>

            </div>

            <a href="#informasi" class="btn btn-outline-primary rounded-pill px-4">
                Lihat Semua
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>


        <div class="row g-4">

            @forelse($kegiatan_terbaru->take(3) as $keg)

                <div class="col-lg-4 col-md-6 reveal">

                    <div class="news-card">

                        <div class="news-image-wrapper">

                            @if($keg->gambar)

                                <img
                                    src="{{ asset('storage/' . $keg->gambar) }}"
                                    alt="{{ $keg->nama_kegiatan }}"
                                    class="news-image"
                                >

                            @else

                                <img
                                    src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=900&q=80"
                                    alt="{{ $keg->nama_kegiatan }}"
                                    class="news-image"
                                >

                            @endif


                            <span class="news-category">
                                {{ $keg->kategori }}
                            </span>

                            <span class="news-date">
                                {{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d M Y') }}
                            </span>

                        </div>


                        <div class="news-content">

                            <h3 class="news-title">
                                {{ $keg->nama_kegiatan }}
                            </h3>

                            <p class="news-description">
                                {{ \Illuminate\Support\Str::limit($keg->deskripsi, 100) }}
                            </p>


                            <button
                                type="button"
                                class="news-link"
                                data-bs-toggle="modal"
                                data-bs-target="#modalKegiatan{{ $keg->id }}"
                            >
                                Baca Selengkapnya
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>

                </div>

            @empty


                <!-- STATIC NEWS 1 -->

                <div class="col-lg-4 col-md-6 reveal">

                    <div class="news-card">

                        <div class="news-image-wrapper">

                            <img
                                src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=900&q=80"
                                alt="Lomba Sains"
                                class="news-image"
                            >

                            <span class="news-category">
                                Perlombaan
                            </span>

                            <span class="news-date">
                                28 Jun 2026
                            </span>

                        </div>


                        <div class="news-content">

                            <h3 class="news-title">
                                Juara 1 Lomba Sains Tingkat Kabupaten
                            </h3>

                            <p class="news-description">
                                Siswa SDN 28 Kinali kembali menorehkan
                                prestasi membanggakan pada ajang
                                Kompetisi Sains tingkat Kabupaten.
                            </p>

                            <button
                                type="button"
                                class="news-link"
                                data-bs-toggle="modal"
                                data-bs-target="#modalStatic1"
                            >
                                Baca Selengkapnya
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>

                </div>


                <!-- STATIC NEWS 2 -->

                <div class="col-lg-4 col-md-6 reveal">

                    <div class="news-card">

                        <div class="news-image-wrapper">

                            <img
                                src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80"
                                alt="Penilaian Digital"
                                class="news-image"
                            >

                            <span class="news-category">
                                Resmi
                            </span>

                            <span class="news-date">
                                15 Jun 2026
                            </span>

                        </div>


                        <div class="news-content">

                            <h3 class="news-title">
                                Penilaian Digital Menggunakan SIPRESMA 28
                            </h3>

                            <p class="news-description">
                                Penerapan portal SIPRESMA 28 membantu
                                guru melakukan rekap nilai secara
                                paperless dan terintegrasi.
                            </p>

                            <button
                                type="button"
                                class="news-link"
                                data-bs-toggle="modal"
                                data-bs-target="#modalStatic2"
                            >
                                Baca Selengkapnya
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>

                </div>


                <!-- STATIC NEWS 3 -->

                <div class="col-lg-4 col-md-6 reveal">

                    <div class="news-card">

                        <div class="news-image-wrapper">

                            <img
                                src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=900&q=80"
                                alt="Literasi Sekolah"
                                class="news-image"
                            >

                            <span class="news-category">
                                Ekstrakurikuler
                            </span>

                            <span class="news-date">
                                08 Jun 2026
                            </span>

                        </div>


                        <div class="news-content">

                            <h3 class="news-title">
                                Gerakan Literasi Sekolah Tiap Sabtu Pagi
                            </h3>

                            <p class="news-description">
                                Program membaca bersama untuk menumbuhkan
                                minat membaca sejak dini.
                            </p>

                            <button
                                type="button"
                                class="news-link"
                                data-bs-toggle="modal"
                                data-bs-target="#modalStatic3"
                            >
                                Baca Selengkapnya
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>


<!-- =========================================================
     CONTACT & MAP
========================================================= -->

<section class="section" id="kontak">

    <div class="container">

        <div class="contact-card reveal">

            <div class="row g-0">

                <!-- CONTACT INFO -->

                <div class="col-lg-5">

                    <div class="contact-info">

                        <div class="section-tag">
                            Hubungi Kami
                        </div>

                        <h2 class="contact-title">
                            Temukan Kami
                        </h2>

                        <p class="contact-description">
                            Kunjungi SD Negeri 28 Kinali secara langsung
                            atau gunakan peta digital untuk menemukan
                            lokasi sekolah dengan mudah.
                        </p>


                        <!-- ADDRESS -->

                        <div class="contact-item">

                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>

                                <span class="contact-label">
                                    Alamat
                                </span>

                                <span class="contact-value">
                                    Katiagan, Kecamatan Kinali,
                                    Kabupaten Pasaman Barat,
                                    Sumatera Barat.
                                </span>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="contact-item">

                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>

                            <div>

                                <span class="contact-label">
                                    Email
                                </span>

                                <span class="contact-value">
                                    sdn28katiagan@gmail.com
                                </span>

                            </div>

                        </div>


                        <!-- NPSN -->

                        <div class="contact-item">

                            <div class="contact-icon">
                                <i class="bi bi-building-fill"></i>
                            </div>

                            <div>

                                <span class="contact-label">
                                    NPSN
                                </span>

                                <span class="contact-value">
                                    10305963
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- MAP -->

                <div class="col-lg-7">

                    <div class="map-wrapper">

                        <iframe
                            src="https://maps.google.com/maps?q=-0.15333495126860688,99.76505095952322&t=&z=16&ie=UTF8&iwloc=&output=embed"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        >
                        </iframe>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CTA
========================================================= -->

<section class="cta">

    <div class="container">

        <div class="cta-content reveal">

            <div class="section-tag justify-content-center" style="color:#93c5fd;">
                SIPRESMA 28
            </div>

            <h2 class="cta-title">
                Siap Mengukir Prestasi
                Bersama Kami?
            </h2>

            <p class="cta-description">
                Akses SIPRESMA 28 untuk mendukung pengelolaan nilai,
                monitoring prestasi, dan tata kelola informasi sekolah
                yang lebih modern.
            </p>

            <a
                href="{{ url('/login') }}"
                class="btn-cta"
            >
                Akses SIPRESMA
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="container">

        <div class="row g-5">

            <!-- BRAND -->

            <div class="col-lg-4 col-md-6">

                <div class="footer-brand">
                    SIPRESMA 28
                </div>

                <p class="footer-description">
                    Portal Sistem Informasi Manajemen Nilai dan Monitoring
                    Prestasi SD Negeri 28 Kinali untuk mendukung pengelolaan
                    pendidikan yang modern, transparan, dan akuntabel.
                </p>


                <div class="social-links">

                    <a href="#beranda" class="social-link">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#beranda" class="social-link">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#beranda" class="social-link">
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>

            </div>


            <!-- NAVIGATION -->

            <div class="col-lg-2 col-md-6">

                <h3 class="footer-title">
                    Navigasi
                </h3>

                <ul class="footer-links">

                    <li>
                        <a href="#beranda">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a href="#profil">
                            Profil Sekolah
                        </a>
                    </li>

                    <li>
                        <a href="#guru">
                            Guru & Staf
                        </a>
                    </li>

                    <li>
                        <a href="#informasi">
                            Kabar Sekolah
                        </a>
                    </li>

                    <li>
                        <a href="#kontak">
                            Kontak
                        </a>
                    </li>

                </ul>

            </div>


            <!-- SYSTEM -->

            <div class="col-lg-3 col-md-6">

                <h3 class="footer-title">
                    Sistem & Akses
                </h3>

                <ul class="footer-links">

                    <li>
                        <a href="{{ url('/login') }}">
                            Login Guru & Admin
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/login') }}">
                            Monitoring Nilai
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/login') }}">
                            Rapor Digital
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/login') }}">
                            Monitoring Prestasi
                        </a>
                    </li>

                </ul>

            </div>


            <!-- CONTACT -->

            <div class="col-lg-3 col-md-6">

                <h3 class="footer-title">
                    Kontak Sekolah
                </h3>

                <p class="footer-contact">
                    <i class="bi bi-geo-alt-fill"></i>
                    Katiagan, Kecamatan Kinali,
                    Kabupaten Pasaman Barat,
                    Sumatera Barat.
                </p>

                <p class="footer-contact">
                    <i class="bi bi-envelope-fill"></i>
                    sdn28katiagan@gmail.com
                </p>

                <p class="footer-contact">
                    <i class="bi bi-building-fill"></i>
                    NPSN: 10305963
                </p>

            </div>

        </div>


        <div class="footer-bottom">

            <div class="row align-items-center">

                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">

                    &copy; {{ date('Y') }}
                    SIPRESMA 28 - SD Negeri 28 Kinali.

                </div>

                <div class="col-md-6 text-center text-md-end">

                    Sistem Informasi Manajemen Nilai & Prestasi

                </div>

            </div>

        </div>

    </div>

</footer>


<!-- =========================================================
     SCROLL TOP
========================================================= -->

<button
    class="scroll-top"
    id="scrollTopBtn"
    aria-label="Kembali ke atas"
>
    <i class="bi bi-arrow-up"></i>
</button>


<!-- =========================================================
     MODAL KEGIATAN DINAMIS
========================================================= -->

@foreach($kegiatan_terbaru as $keg)

<div
    class="modal fade"
    id="modalKegiatan{{ $keg->id }}"
    tabindex="-1"
    aria-labelledby="modalLabel{{ $keg->id }}"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header border-0">

                <div>

                    <span
                        class="badge rounded-pill px-3 py-2"
                        style="background:#eff6ff;color:#2563eb;"
                    >
                        {{ $keg->kategori }}
                    </span>

                    <span class="text-secondary small ms-2">
                        {{ \Carbon\Carbon::parse($keg->tanggal_kegiatan)->translatedFormat('d F Y') }}
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                </button>

            </div>


            <div class="modal-body">

                <h3
                    class="fw-bold mb-4"
                    id="modalLabel{{ $keg->id }}"
                >
                    {{ $keg->nama_kegiatan }}
                </h3>


                <div class="mb-4">

                    @if($keg->gambar)

                        <img
                            src="{{ asset('storage/' . $keg->gambar) }}"
                            alt="{{ $keg->nama_kegiatan }}"
                            class="modal-news-image"
                        >

                    @else

                        <img
                            src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1000&q=80"
                            alt="{{ $keg->nama_kegiatan }}"
                            class="modal-news-image"
                        >

                    @endif

                </div>


                <div class="d-flex flex-wrap gap-3 mb-4 pb-3 border-bottom text-secondary small">

                    <span>
                        <i class="bi bi-tag-fill text-primary me-1"></i>
                        {{ $keg->jenis_kegiatan }}
                    </span>

                    <span>
                        <i class="bi bi-calendar3 text-primary me-1"></i>
                        Semester {{ $keg->semester_aktif }}
                    </span>

                </div>


                <div
                    class="text-secondary"
                    style="line-height:1.85; white-space:pre-line;"
                >
                    {{ $keg->deskripsi }}
                </div>

            </div>


            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-dark rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>

@endforeach


<!-- =========================================================
     STATIC MODAL 1
========================================================= -->

<div
    class="modal fade"
    id="modalStatic1"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header border-0">

                <div>

                    <span
                        class="badge rounded-pill px-3 py-2"
                        style="background:#eff6ff;color:#2563eb;"
                    >
                        Perlombaan
                    </span>

                    <span class="text-secondary small ms-2">
                        28 Juni 2026
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                >
                </button>

            </div>


            <div class="modal-body">

                <h3 class="fw-bold mb-4">
                    Juara 1 Lomba Sains Tingkat Kabupaten
                </h3>

                <img
                    src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1000&q=80"
                    alt="Lomba Sains"
                    class="modal-news-image mb-4"
                >

                <p class="text-secondary" style="line-height:1.85;">
                    Siswa SDN 28 Kinali kembali menorehkan prestasi
                    membanggakan dengan meraih juara pertama pada ajang
                    Kompetisi Sains tingkat Kabupaten. Kompetisi ini
                    diikuti oleh berbagai sekolah dasar dari sejumlah
                    kecamatan.
                </p>

            </div>


            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-dark rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     STATIC MODAL 2
========================================================= -->

<div
    class="modal fade"
    id="modalStatic2"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header border-0">

                <div>

                    <span
                        class="badge rounded-pill px-3 py-2"
                        style="background:#eff6ff;color:#2563eb;"
                    >
                        Resmi
                    </span>

                    <span class="text-secondary small ms-2">
                        15 Juni 2026
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                >
                </button>

            </div>


            <div class="modal-body">

                <h3 class="fw-bold mb-4">
                    Penilaian Digital Menggunakan SIPRESMA 28
                </h3>

                <img
                    src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1000&q=80"
                    alt="Evaluasi Digital"
                    class="modal-news-image mb-4"
                >

                <p class="text-secondary" style="line-height:1.85;">
                    Penerapan portal SIPRESMA 28 membantu guru melakukan
                    rekap nilai harian secara paperless dan terintegrasi.
                    Sistem ini mendukung efisiensi pengelolaan data
                    akademik serta membantu monitoring perkembangan
                    belajar siswa.
                </p>

            </div>


            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-dark rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     STATIC MODAL 3
========================================================= -->

<div
    class="modal fade"
    id="modalStatic3"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header border-0">

                <div>

                    <span
                        class="badge rounded-pill px-3 py-2"
                        style="background:#eff6ff;color:#2563eb;"
                    >
                        Ekstrakurikuler
                    </span>

                    <span class="text-secondary small ms-2">
                        08 Juni 2026
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                >
                </button>

            </div>


            <div class="modal-body">

                <h3 class="fw-bold mb-4">
                    Gerakan Literasi Sekolah Tiap Sabtu Pagi
                </h3>

                <img
                    src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=1000&q=80"
                    alt="Kegiatan Literasi"
                    class="modal-news-image mb-4"
                >

                <p class="text-secondary" style="line-height:1.85;">
                    Program membaca bersama buku bacaan non-akademik
                    di lingkungan sekolah guna menumbuhkan minat membaca
                    sejak dini. Kegiatan ini diikuti oleh siswa dari
                    berbagai tingkatan kelas bersama tenaga pendidik.
                </p>

            </div>


            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-dark rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

    /* =========================================================
       NAVBAR SCROLL
    ========================================================= */

    const mainNavbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', function () {

        if (window.scrollY > 30) {
            mainNavbar.classList.add('scrolled');
        } else {
            mainNavbar.classList.remove('scrolled');
        }

    });


    /* =========================================================
       SCROLL TOP
    ========================================================= */

    const scrollTopBtn = document.getElementById('scrollTopBtn');

    window.addEventListener('scroll', function () {

        if (window.scrollY > 450) {

            scrollTopBtn.classList.add('show');

        } else {

            scrollTopBtn.classList.remove('show');

        }

    });


    scrollTopBtn.addEventListener('click', function () {

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    });


    /* =========================================================
       REVEAL ANIMATION
    ========================================================= */

    const revealElements = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver(
        function(entries, observer) {

            entries.forEach(function(entry) {

                if (entry.isIntersecting) {

                    entry.target.classList.add('show');

                    observer.unobserve(entry.target);

                }

            });

        },
        {
            threshold: 0.12
        }
    );


    revealElements.forEach(function(element) {

        revealObserver.observe(element);

    });


    /* =========================================================
       ACTIVE NAVIGATION
    ========================================================= */

    const sections = document.querySelectorAll(
        'header[id], section[id]'
    );

    const navLinks = document.querySelectorAll(
        '.nav-link'
    );


    window.addEventListener('scroll', function () {

        let currentSection = '';

        sections.forEach(function(section) {

            const sectionTop =
                section.offsetTop - 180;

            const sectionHeight =
                section.offsetHeight;

            if (
                window.scrollY >= sectionTop &&
                window.scrollY < sectionTop + sectionHeight
            ) {

                currentSection =
                    section.getAttribute('id');

            }

        });


        navLinks.forEach(function(link) {

            link.classList.remove('active');

            const href =
                link.getAttribute('href');

            if (
                href &&
                href === '#' + currentSection
            ) {

                link.classList.add('active');

            }

        });

    });


    /* =========================================================
       CLOSE MOBILE NAVBAR AFTER CLICK
    ========================================================= */

    document
        .querySelectorAll('.navbar-nav .nav-link')
        .forEach(function(link) {

            link.addEventListener('click', function() {

                const navbar =
                    document.getElementById('navbarContent');

                if (
                    navbar.classList.contains('show')
                ) {

                    const collapse =
                        bootstrap.Collapse.getInstance(navbar);

                    if (collapse) {
                        collapse.hide();
                    }

                }

            });

        });

</script>

</body>
</html>
