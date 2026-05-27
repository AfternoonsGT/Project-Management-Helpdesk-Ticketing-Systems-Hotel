<aside class="w-64 bg-[#0f2942] dark:bg-[#0b1b2b] text-white flex flex-col transition-colors duration-200">
    <div class="h-16 flex items-center justify-center border-b border-gray-800 bg-gray-950">
        <h1 class="text-xl font-extrabold text-white tracking-widest uppercase">
            <span class="flex items-center justify-center text-red-500">Hotel Pangeran</span> Helpdesk
        </h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('dashboard') && !request()->has('status') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Dashboard
        </a>

        @if (Auth::user()->role == 'staff' || Auth::user()->role == 'technician')
            <a href="{{ route('tickets.create') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('tickets.create') ? 'bg-blue-600 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Laporan Baru
            </a>
        @endif

        @if (Auth::user()->role == 'admin')
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mt-6 mb-2">Manajemen</p>

            <a href="{{ route('users.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Kelola Pengguna
            </a>

            <a href="{{ route('settings.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('settings.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan Sistem
            </a>

            <a href="{{ route('categories.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                Kelola Kategori
            </a>
            
            <div x-data="{ open: {{ request()->has('status') ? 'true' : 'false' }} }" class="mb-2 mt-2">
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-[#1a365d] rounded-lg transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Status Laporan
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

               <div x-show="open" style="display: none;" class="pl-11 pr-4 mt-1 space-y-1">
                    <a href="{{ route('dashboard', ['status' => 'open', 'view' => 'clean']) }}" class="flex items-center py-2 text-sm transition-colors {{ request('status') == 'open' ? 'text-blue-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Baru (Open)
                    </a>

                    <a href="{{ route('dashboard', ['status' => 'on_progress', 'view' => 'clean']) }}" class="flex items-center py-2 text-sm transition-colors {{ request('status') == 'on_progress' ? 'text-yellow-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span> Diproses
                    </a>

                    <a href="{{ route('dashboard', ['status' => 'resolved', 'view' => 'clean']) }}" class="flex items-center py-2 text-sm transition-colors {{ request('status') == 'resolved' ? 'text-green-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Selesai
                    </a>

                    <a href="{{ route('dashboard', ['status' => 'closed', 'view' => 'clean']) }}" class="flex items-center py-2 text-sm transition-colors {{ request('status') == 'closed' ? 'text-gray-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span> Ditutup Permanen
                    </a>
                </div>
            </div>
        @endif

        <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mt-6 mb-2">Lainnya</p>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Saya
        </a>
    </nav>

    <a href="{{ route('profile.edit') }}" class="mx-4 mb-4 p-3 rounded-xl bg-gray-950/60 border border-gray-800/80 flex items-center gap-3 hover:bg-gray-800/50 hover:border-gray-700/50 transition-all duration-200 group">
        <div class="relative flex-shrink-0">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-700 shadow-inner transition-transform duration-200 group-hover:scale-105">
            @else
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold border border-gray-700 shadow-inner uppercase tracking-wider text-sm transition-transform duration-200 group-hover:scale-105">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif
            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-gray-900"></span>
        </div>

        <div class="overflow-hidden flex-1">
            <h4 class="text-sm font-semibold text-gray-200 truncate group-hover:text-white transition-colors" title="{{ Auth::user()->name }}">
                {{ Auth::user()->name }}
            </h4>
            <p class="text-[11px] text-gray-400 font-bold capitalize mt-0.5 flex items-center gap-1.5 tracking-wide">
                <span class="inline-block w-1.5 h-1.5 rounded-full {{ Auth::user()->role == 'admin' ? 'bg-red-500' : (Auth::user()->role == 'technician' ? 'bg-amber-500' : 'bg-blue-400') }}"></span>
                {{ Auth::user()->role == 'technician' ? 'Teknisi' : Auth::user()->role }}
            </p>
        </div>

        <div class="text-gray-600 group-hover:text-gray-400 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </div>
    </a>

    <div class="p-4 border-t border-gray-800 text-xs text-center text-gray-500 bg-gray-950/30">
        &copy; 2026 IT Dept
    </div>
</aside>