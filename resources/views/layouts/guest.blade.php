<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary-burgundy: #3D5A80;
                --primary-hover: #293E59;
                --bg-dark-burgundy: #1B263B;
                --card-dark-burgundy: #25354F;
                --border-dark-burgundy: #2C3E5B;
                --bg-light-cream: #F2EFE7;
                --card-light-cream: #FFFFFF;
                --text-dark-mauve: #2D3748;
            }
            body {
                background-color: var(--bg-light-cream) !important;
                color: var(--text-dark-mauve) !important;
            }
            .auth-card {
                background-color: var(--card-light-cream) !important;
                border: 1px solid rgba(61, 90, 128, 0.15) !important;
                box-shadow: 0 10px 30px rgba(61, 90, 128, 0.06) !important;
            }
            /* Override standard input and button components used in Breeze */
            input, select, textarea {
                color: var(--text-dark-mauve) !important;
                border-color: rgba(61, 90, 128, 0.2) !important;
            }
            input:focus {
                border-color: var(--primary-burgundy) !important;
                --tw-ring-color: var(--primary-burgundy) !important;
            }
            label {
                color: var(--text-dark-mauve) !important;
            }
            button, .btn-primary {
                background-color: var(--primary-burgundy) !important;
                transition: all 0.2s ease-in-out !important;
            }
            button:hover, .btn-primary:hover {
                background-color: var(--primary-hover) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-20 h-20 object-contain rounded-2xl shadow-md border border-[#3D5A80]/25" alt="Logo">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-6 auth-card overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
