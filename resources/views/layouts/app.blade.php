<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hotel Helpdesk') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <!-- KITA UBAH BODY MENJADI FLEXBOX -->
    <body class="font-sans antialiased text-gray-900 flex h-screen bg-gray-100 overflow-hidden">

        <!-- 1. PANGGIL SIDEBAR DI SINI -->
        @include('layouts.sidebar')

        <!-- 2. BUNGKUSAN KONTEN SEBELAH KANAN -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Navbar Atas (Bawaan Laravel Breeze) -->
            @include('layouts.navigation')

            <!-- Konten Utama (Tabel, Form, dll akan masuk ke sini) -->
            <main class="flex-1 overflow-y-auto bg-gray-50">

                <!-- FITUR NOTIFIKASI (FLASH MESSAGE) YANG KEMARIN KITA BUAT -->
                @if(session('success'))
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4"
                         x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 3000)"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-90">

                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center shadow-sm" role="alert">
                            <strong class="font-bold mr-2">Berhasil! </strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- INI TEMPAT KONTEN HALAMAN BERADA -->
                {{ $slot }}
            </main>
        </div>

    </body>
</html>
