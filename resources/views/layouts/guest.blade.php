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
                --primary-burgundy: #9F5261;
                --primary-hover: #86414E;
                --bg-dark-burgundy: #1F1215;
                --card-dark-burgundy: #2D1B1F;
                --border-dark-burgundy: #3D262A;
                --bg-light-cream: #FAF5F5;
                --card-light-cream: #FFFDFD;
                --text-dark-mauve: #4A2830;
            }
            body {
                background-color: var(--bg-light-cream) !important;
                color: var(--text-dark-mauve) !important;
            }
            .auth-card {
                background-color: var(--card-light-cream) !important;
                border: 1px solid rgba(159, 82, 97, 0.15) !important;
                box-shadow: 0 10px 30px rgba(74, 40, 48, 0.06) !important;
            }
            /* Override standard input and button components used in Breeze */
            input, select, textarea {
                color: var(--text-dark-mauve) !important;
                border-color: rgba(159, 82, 97, 0.2) !important;
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
                    <img src="{{ asset('images/logo.jpg') }}" class="w-20 h-20 object-contain rounded-2xl shadow-md border border-[#9F5261]/25" alt="Logo">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-6 auth-card overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
