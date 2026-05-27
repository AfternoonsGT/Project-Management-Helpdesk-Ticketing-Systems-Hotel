<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hotel Helpdesk') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-[#0f172a] dark:text-gray-200 flex h-screen overflow-hidden">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            @include('layouts.navigation')

            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-[#0f172a]">

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

                {{ $slot }}
                
                <footer class="bg-white dark:bg-[#1e293b] border-t border-gray-200 dark:border-gray-700 pt-16 pb-8 mt-auto transition-colors duration-200">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="grid gap-12 md:grid-cols-4 lg:grid-cols-5">

                            <div class="lg:col-span-2 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#0f2942] rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-bold text-[#0f2942] dark:text-gray-100 tracking-wide">IT Helpdesk</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm leading-relaxed">
                                    Sistem manajemen pelaporan teknis dan pemeliharaan fasilitas terpadu untuk mendukung kenyamanan dan operasional Hotel Pangeran.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 tracking-wider uppercase">Menu Utama</h3>
                                <ul class="space-y-3 text-sm">
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">Dashboard</a></li>
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">Buat Laporan Baru</a></li>
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">Riwayat Tiket</a></li>
                                </ul>
                            </div>

                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 tracking-wider uppercase">Pusat Bantuan</h3>
                                <ul class="space-y-3 text-sm">
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">Buku Panduan (PDF)</a></li>
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">SOP Pemeliharaan</a></li>
                                    <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-[#0f2942] dark:hover:text-blue-400 font-medium transition-colors duration-200">Katalog Kerusakan</a></li>
                                </ul>
                            </div>

                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 tracking-wider uppercase">Kontak Darurat</h3>
                                <ul class="space-y-3 text-sm">
                                    <li class="flex items-center gap-2 text-gray-500 dark:text-gray-400 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        Ext. 101 (Admin IT)
                                    </li>
                                    <li class="flex items-center gap-2 text-gray-500 dark:text-gray-400 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Ext. 102 (Teknisi)
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-16 flex flex-col md:flex-row items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-8 gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium order-last md:order-first text-center md:text-left">
                                &copy; {{ date('Y') }} IT Department. Dirancang oleh <span class="text-[#0f2942] dark:text-gray-200 font-bold">Arif & Dika</span>. All rights reserved.
                            </span>

                            <div class="flex justify-center space-x-6 md:order-2">
                                <a href="https://wa.me/6295340422898" target="_blank" class="text-gray-400 hover:text-green-500 transition-colors duration-300">
                                    <span class="sr-only">WhatsApp</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </a>
                                <a href="https://instagram.com/sandkrmd" target="_blank" class="text-gray-400 hover:text-pink-500 transition-colors duration-300">
                                    <span class="sr-only">Instagram</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- SCRIPT 1: NOTIFIKASI LONCENG ---
                const btnNotification = document.getElementById('btn-notification');
                const badgeNotification = document.getElementById('badge-notification');

                if (btnNotification) {
                    btnNotification.addEventListener('click', function () {
                        if (badgeNotification) {
                            badgeNotification.style.display = 'none';
                        }
                        fetch("{{ route('notifications.markAllRead') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        })
                        .then(response => response.json())
                        .then(data => console.log('Semua notifikasi sudah dibaca.'))
                        .catch(error => console.error('Error:', error));
                    });
                }

                // --- SCRIPT 2: DARK MODE TOGGLE ---
                var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
                var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
                var themeToggleBtn = document.getElementById('theme-toggle');

                if (themeToggleBtn) { // Pastikan tombol dark mode ada di halaman ini
                    // Tampilkan ikon yang sesuai saat load
                    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        themeToggleLightIcon.classList.remove('hidden');
                    } else {
                        themeToggleDarkIcon.classList.remove('hidden');
                    }

                    // Aksi saat tombol diklik
                    themeToggleBtn.addEventListener('click', function() {
                        themeToggleDarkIcon.classList.toggle('hidden');
                        themeToggleLightIcon.classList.toggle('hidden');

                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        }
                    });
                }
            });
        </script>
        </body>
</html>