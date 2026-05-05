<!-- SIDEBAR COMPONENT -->
<aside class="w-64 bg-gray-900 text-gray-300 flex flex-col min-h-screen shadow-2xl transition-all duration-300">
    <!-- Logo / Judul Aplikasi -->
    <div class="h-16 flex items-center justify-center border-b border-gray-800 bg-gray-950">
        <h1 class="text-xl font-extrabold text-white tracking-widest uppercase">
            <span class="text-blue-500">Hotel</span> Helpdesk
        </h1>
    </div>

    <!-- Daftar Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Menu Utama</p>

        <!-- Menu Default (Semua Role Punya) -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        <!-- Menu Khusus Staff -->
        @if(Auth::user()->role == 'staff')
            <a href="{{ route('tickets.create') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('tickets.create') ? 'bg-blue-600 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Laporan Baru
            </a>
        @endif

        <!-- Menu Tambahan Khusus Admin (Bisa untuk masa depan) -->
        @if(Auth::user()->role == 'admin')
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mt-6 mb-2">Manajemen</p>
            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Kelola Pengguna
            </a>
        @endif
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-gray-800 text-xs text-center text-gray-500">
        &copy; 2026 IT Dept
    </div>
</aside>
