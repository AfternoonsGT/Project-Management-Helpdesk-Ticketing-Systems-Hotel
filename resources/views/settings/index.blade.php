<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-200">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">System Configuration</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Kelola parameter operasional, batas waktu pengerjaan (SLA), dan sistem otomatisasi helpdesk.</p>
                </div>
                <span class="bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-bold px-4 py-2 rounded-full border border-blue-200 dark:border-blue-500/20 tracking-wider">
                    v1.0.2 Stable
                </span>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FORM PENGATURAN UTAMA --}}
            <form method="POST" action="{{ route('settings.update') }}" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- 1. KARTU GENERAL PROPERTI --}}
                <div class="bg-white dark:bg-[#0A0A0A] p-8 rounded-3xl border border-gray-200 dark:border-gray-800/60 shadow-lg dark:shadow-2xl transition-colors duration-200">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h3 class="text-gray-800 dark:text-white font-extrabold text-base uppercase tracking-wider">General Properti</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-[#161616] rounded-xl border border-gray-200 dark:border-gray-800/40 overflow-hidden px-5 py-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Nama Aplikasi</label>
                            <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'Pangeran Helpdesk System' }}" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-[13px] font-medium focus:ring-0 px-0 mt-0.5">
                        </div>

                        <div class="bg-gray-50 dark:bg-[#161616] rounded-xl border border-gray-200 dark:border-gray-800/40 overflow-hidden px-5 py-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Identitas Properti</label>
                            <input type="text" name="hotel_name" value="{{ $settings['hotel_name'] ?? 'Hotel Pangeran Pekanbaru' }}" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-[13px] font-medium focus:ring-0 px-0 mt-0.5">
                        </div>
                    </div>
                </div>

                {{-- 2. KARTU SLA --}}
                <div class="bg-white dark:bg-[#0A0A0A] p-8 rounded-3xl border border-gray-200 dark:border-gray-800/60 shadow-lg dark:shadow-2xl transition-colors duration-200">
                    <div class="flex items-center gap-3 mb-2 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-gray-800 dark:text-white font-extrabold text-base uppercase tracking-wider">Target Respons & Penanganan (SLA)</h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-6">Tentukan batas maksimal waktu penyelesaian masalah berdasarkan tingkat urgensi kerusakan kamar atau fasilitas hotel.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-[#161616] rounded-xl border border-red-200 dark:border-gray-800/40 overflow-hidden px-5 py-2">
                            <label class="block text-[10px] font-bold text-red-600 dark:text-red-500 uppercase tracking-wider mt-1">Prioritas Tinggi / Darurat</label>
                            <div class="flex items-center justify-between mt-0.5">
                                <input type="number" name="sla_high" value="{{ $settings['sla_high'] ?? '30' }}" class="w-2/3 bg-transparent border-none text-gray-900 dark:text-white text-base font-bold focus:ring-0 px-0">
                                <span class="text-xs text-gray-500 font-semibold">Menit</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-[#161616] rounded-xl border border-amber-200 dark:border-gray-800/40 overflow-hidden px-5 py-2">
                            <label class="block text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider mt-1">Prioritas Sedang / Terisi</label>
                            <div class="flex items-center justify-between mt-0.5">
                                <input type="number" name="sla_medium" value="{{ $settings['sla_medium'] ?? '2' }}" class="w-2/3 bg-transparent border-none text-gray-900 dark:text-white text-base font-bold focus:ring-0 px-0">
                                <span class="text-xs text-gray-500 font-semibold">Jam</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-[#161616] rounded-xl border border-blue-200 dark:border-gray-800/40 overflow-hidden px-5 py-2">
                            <label class="block text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mt-1">Prioritas Normal / Publik</label>
                            <div class="flex items-center justify-between mt-0.5">
                                <input type="number" name="sla_low" value="{{ $settings['sla_low'] ?? '12' }}" class="w-2/3 bg-transparent border-none text-gray-900 dark:text-white text-base font-bold focus:ring-0 px-0">
                                <span class="text-xs text-gray-500 font-semibold">Jam</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. KARTU OTOMATISASI & WA --}}
                <div class="bg-white dark:bg-[#0A0A0A] p-8 rounded-3xl border border-gray-200 dark:border-gray-800/60 shadow-lg dark:shadow-2xl transition-colors duration-200">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <h3 class="text-gray-800 dark:text-white font-extrabold text-base uppercase tracking-wider">Otomatisasi & Integrasi</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-[#161616]/40 p-4 rounded-xl border border-gray-200 dark:border-gray-800/30">
                            <div class="max-w-xl">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Auto-Assign Tiket</h4>
                                <p class="text-xs text-gray-500 mt-1">Secara otomatis menugaskan laporan kerusakan baru ke teknisi berdasarkan beban kerja terkecil.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="auto_assign" value="0">
                                <input type="checkbox" name="auto_assign" value="1" class="sr-only peer" {{ ($settings['auto_assign'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-300 dark:bg-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white dark:after:bg-gray-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 peer-checked:after:bg-white"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 dark:bg-[#161616]/40 p-4 rounded-xl border border-gray-200 dark:border-gray-800/30">
                            <div class="max-w-xl">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Integrasi Gateway WhatsApp</h4>
                                <p class="text-xs text-gray-500 mt-1">Kirim alert pesan WhatsApp otomatis ke nomor Hp teknisi sesaat setelah staf hotel membuat laporan darurat.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="wa_gateway" value="0">
                                <input type="checkbox" name="wa_gateway" value="1" class="sr-only peer" {{ ($settings['wa_gateway'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-300 dark:bg-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white dark:after:bg-gray-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 peer-checked:after:bg-white"></div>
                            </label>
                        </div>

                        <div class="bg-gray-50 dark:bg-[#161616]/40 p-4 rounded-xl border border-gray-200 dark:border-gray-800/30">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Token API Fonnte</label>
                            <input type="text" name="fonnte_token" value="{{ $settings['fonnte_token'] ?? '' }}" placeholder="Masukkan token Fonnte di sini..." class="w-full bg-white dark:bg-[#0A0A0A] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg text-[13px] font-mono focus:ring-blue-500 px-4 py-2">
                        </div>
                    </div>
                </div>

                {{-- 4. KARTU SISTEM & KEAMANAN --}}
                <div class="bg-white dark:bg-[#0A0A0A] p-8 rounded-3xl border border-gray-200 dark:border-gray-800/60 shadow-lg dark:shadow-2xl transition-colors duration-200">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <h3 class="text-gray-800 dark:text-white font-extrabold text-base uppercase tracking-wider">Sistem & Keamanan</h3>
                    </div>

                    <div class="flex items-center justify-between bg-gray-50 dark:bg-[#161616]/40 p-4 rounded-xl border border-gray-200 dark:border-gray-800/30">
                        <div class="max-w-xl">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Mode Pemeliharaan (Maintenance)</h4>
                            <p class="text-xs text-gray-500 mt-1">Kunci akses aplikasi untuk staf & teknisi saat ada perbaikan server. Hanya Admin yang bisa login.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_maintenance" value="0">
                            <input type="checkbox" name="is_maintenance" value="1" class="sr-only peer" {{ ($settings['is_maintenance'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white dark:after:bg-gray-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600 peer-checked:after:bg-white"></div>
                        </label>
                    </div>
                </div>

                {{-- TOMBOL SAVE --}}
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-xs font-extrabold tracking-wider uppercase transition duration-200 shadow-xl border border-transparent">
                        Save Configuration
                    </button>
                </div>
            </form>

            {{-- 5. TOMBOL OPTIMIZER & BACKUP (DI LUAR FORM UTAMA) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8 pb-12">
                <form action="{{ route('settings.optimize') }}" method="POST" onsubmit="return confirm('Yakin ingin menyapu bersih semua foto lama? File fisik akan dihapus permanen.');">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-amber-50 dark:bg-amber-500/10 hover:bg-amber-100 dark:hover:bg-amber-500/20 text-amber-600 dark:text-amber-500 border border-amber-200 dark:border-amber-500/50 font-bold text-sm rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Sapu Bersih Foto Laporan Lama
                    </button>
                </form>

                <form action="{{ route('settings.backup') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-500 border border-emerald-200 dark:border-emerald-500/50 font-bold text-sm rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Database (.sql)
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>