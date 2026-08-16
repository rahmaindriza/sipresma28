<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPRESMA 28</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-burgundy: #3D5A80;
            /* Steel Blue Primary */
            --primary-hover: #293E59;
            --bg-light-cream: #F2EFE7;
            /* Bright Beige Backdrop */
            --text-dark-mauve: #2D3748;
            /* Slate Text */
            --font-title: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-light-cream) !important;
            color: var(--text-dark-mauve) !important;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .login-sidebar {
            flex: 1.25;
            position: relative;
            background: linear-gradient(135deg, rgba(61, 90, 128, 0.92) 0%, rgba(27, 38, 59, 0.88) 100%), url("{{ asset('images/gedung_sd.jpg') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 50px;
            color: #FFFFFF;
            overflow: hidden;
        }

        .login-form-side {
            flex: 0.85;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }

        .login-card-custom {
            width: 100%;
            max-width: 380px;
        }

        label {
            color: var(--text-dark-mauve) !important;
        }

        .text-slate-400,
        .text-slate-500 {
            color: #64748B !important;
        }

        input[type="email"],
        input[type="password"] {
            background-color: #FFFFFF !important;
            border: 1px solid rgba(61, 90, 128, 0.2) !important;
            color: var(--text-dark-mauve) !important;
            outline: none !important;
            transition: all 0.2s ease;
        }

        input::placeholder {
            color: #A0ABBA !important;
        }

        input:focus {
            border-color: var(--primary-burgundy) !important;
            --tw-ring-color: var(--primary-burgundy) !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(61, 90, 128, 0.15) !important;
        }

        button[type="submit"] {
            background-color: var(--primary-burgundy) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px rgba(61, 90, 128, 0.2) !important;
            font-weight: 700;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover) !important;
            box-shadow: 0 6px 20px rgba(61, 90, 128, 0.3) !important;
        }

        @media (max-width: 991px) {
            .login-sidebar {
                display: none;
            }

            .login-form-side {
                flex: 1;
                background-color: var(--bg-light-cream);
            }

            .login-card-custom {
                background: #FFFFFF;
                border: 1px solid rgba(61, 90, 128, 0.12);
                padding: 35px;
                border-radius: 24px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- Left Side: Professional Image Sidebar (Hidden on mobile) -->
        <div class="login-sidebar hidden lg:flex">
            <!-- Sidebar Header -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 object-contain border border-white/20"
                    alt="Logo">
                <div>
                    <h2 class="text-sm font-bold tracking-wider uppercase text-white"
                        style="font-family: var(--font-title);">SDN 28 KINALI</h2>
                    <p class="text-[10px] text-white/70">Kab. Pasaman Barat</p>
                </div>
            </div>

            <!-- Sidebar Body (Welcome Text) -->
            <div class="my-auto max-w-lg">
                <span class="text-xs font-bold uppercase tracking-widest text-sky-300"
                    style="font-family: var(--font-title);">Sistem Manajemen Sekolah</span>
                <h1 class="text-4xl font-extrabold text-white mt-2 mb-4 leading-tight"
                    style="font-family: var(--font-title);">SIPRESMA 28</h1>
                <p class="text-white/80 leading-relaxed text-sm">Portal digital integrasi pencatatan nilai rapor siswa
                    dan monitoring rekapitulasi prestasi secara transparan, aman, dan akuntabel di lingkungan SD Negeri
                    28 Kinali.</p>
            </div>

            <!-- Sidebar Footer -->
            <div class="text-xs text-white/50 border-t border-white/10 pt-4 flex justify-between items-center">
                <span>&copy; 2026 SD Negeri 28 Kinali</span>
                <span>Sumatera Barat, Indonesia</span>
            </div>
        </div>

        <!-- Right Side: Centered Login Form -->
        <div class="login-form-side">
            <div class="login-card-custom">

                <!-- Brand Header (Visible on Mobile only) -->
                <div class="text-center mb-6 block lg:hidden">
                    <div class="inline-flex mb-3">
                        <img src="{{ asset('images/logo.jpg') }}"
                            class="w-14 h-14 object-contain rounded-2xl shadow-sm border border-[#3D5A80]/20"
                            alt="Logo">
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800" style="font-family: var(--font-title);">
                        SIPRESMA 28</h1>
                    <p class="text-xs text-slate-500 mt-0.5">SD Negeri 28 Kinali</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800" style="font-family: var(--font-title);">Selamat Datang
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Silakan masuk menggunakan kredensial akun Anda</p>
                </div>

                <!-- Errors Alert -->
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email"
                            class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="username"
                                class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] focus:ring-1 focus:ring-[var(--primary-burgundy)] transition text-sm"
                                placeholder="Masukkan email Anda">
                        </div>
                    </div>

                    <!-- Password -->
                    <!-- Password -->
                    <div>
                        <label for="password"
                            class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <!-- Icon Gembok (Kiri) -->
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>

                            <!-- Input Password (Ditambahkan pr-11 agar teks tidak tertimpa icon mata) -->
                            <input type="password" id="password" name="password" required
                                autocomplete="current-password"
                                class="w-full pl-11 pr-11 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[var(--primary-burgundy)] focus:ring-1 focus:ring-[var(--primary-burgundy)] transition text-sm"
                                placeholder="Masukkan password Anda">

                            <!-- Tombol Icon Mata (Kanan) -->
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition focus:outline-none">
                                <!-- Icon Mata Terbuka (Default) -->
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <!-- Icon Mata Tertutup (Hidden) -->
                                <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 0110.123 3.937C21.268 11.057 17.478 14 13 14c-.62 0-1.228-.057-1.815-.165M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Script Toggle Password -->
                    <script>
                        function togglePasswordVisibility() {
                            const passwordInput = document.getElementById('password');
                            const eyeIcon = document.getElementById('eyeIcon');
                            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                eyeIcon.classList.add('hidden');
                                eyeSlashIcon.classList.remove('hidden');
                            } else {
                                passwordInput.type = 'password';
                                eyeIcon.classList.remove('hidden');
                                eyeSlashIcon.classList.add('hidden');
                            }
                        }
                    </script>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-slate-600">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded bg-white border-slate-300 text-[var(--primary-burgundy)] focus:ring-[var(--primary-burgundy)]/20 transition">
                            <span class="ml-2">Ingat saya</span>
                        </label>
                        <a href="/"
                            class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">Kembali ke
                            Beranda</a>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" style="background-color: var(--primary-burgundy) !important;"
                            class="w-full py-3 hover:bg-[var(--primary-hover)] active:bg-[var(--primary-hover)] text-white font-semibold rounded-xl transition shadow-lg text-sm">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="text-center mt-8 text-xs text-slate-400 block lg:hidden">
                    &copy; 2026 SDN 28 Kinali. Hak Cipta Dilindungi.
                </div>
            </div>
        </div>

    </div>

</body>

</html>
