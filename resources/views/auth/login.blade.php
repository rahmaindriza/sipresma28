<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPRESMA 28</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-burgundy: #9F5261;
            --primary-hover: #86414E;
            --bg-light-cream: #FAF5F5;
            --card-light-cream: #FFFDFD;
            --text-dark-mauve: #4A2830;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light-cream) !important;
            color: var(--text-dark-mauve) !important;
        }
        .login-card {
            background-color: var(--card-light-cream) !important;
            border: 1px solid rgba(159, 82, 97, 0.15) !important;
            box-shadow: 0 10px 30px rgba(74, 40, 48, 0.06) !important;
        }
        label, h1, h2, p {
            color: var(--text-dark-mauve) !important;
        }
        .text-slate-400, .text-slate-500, .text-blue-400 {
            color: var(--text-dark-mauve) !important;
            opacity: 0.8;
        }
        input {
            background-color: #FFFFFF !important;
            border-color: rgba(159, 82, 97, 0.25) !important;
            color: var(--text-dark-mauve) !important;
        }
        input::placeholder {
            color: #A08088 !important;
        }
        input:focus {
            border-color: var(--primary-burgundy) !important;
            --tw-ring-color: var(--primary-burgundy) !important;
        }
        button[type="submit"] {
            background-color: var(--primary-burgundy) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px rgba(159, 82, 97, 0.3) !important;
        }
        button[type="submit"]:hover {
            background-color: var(--primary-hover) !important;
            box-shadow: 0 6px 20px rgba(159, 82, 97, 0.4) !important;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Brand Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex mb-4">
                <img src="{{ asset('images/logo.jpg') }}" class="w-16 h-16 object-contain rounded-2xl shadow-md border border-[#9F5261]/25" alt="Logo">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">SIPRESMA 28</h1>
            <p class="text-sm text-slate-400 mt-1">Sistem Informasi Nilai & Prestasi Siswa</p>
            <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider mt-0.5">SD Negeri 28 Kinali</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-3xl p-8 shadow-2xl">
            <h2 class="text-lg font-semibold text-white mb-6">Silakan masuk ke akun Anda</h2>

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-950/60 border border-red-900 text-red-300 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full pl-11 pr-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm"
                               placeholder="Masukkan email Anda">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="w-full pl-11 pr-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm"
                               placeholder="Masukkan password Anda">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-slate-400">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-blue-600 focus:ring-blue-600/20 focus:ring-offset-slate-900 transition">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-semibold rounded-xl transition shadow-lg text-sm">
                        Masuk
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; 2026 SDN 28 Kinali. Hak Cipta Dilindungi.
        </div>
    </div>

</body>
</html>
