<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ============================================================== --}}
            {{-- KOTAK PENCARIAN & FILTER JABATAN                               --}}
            {{-- ============================================================== --}}
            <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm mb-6 p-4">
                <form method="GET" action="{{ route('users.index') }}" class="flex flex-col md:flex-row items-end gap-4">
                    
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-[#0f2942] dark:text-gray-300 mb-1 uppercase tracking-wide">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau nomor HP..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:text-gray-200 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-[#0f2942] dark:text-gray-300 mb-1 uppercase tracking-wide">Filter Jabatan</label>
                        <select name="role_filter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:text-gray-200 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="technician" {{ request('role_filter') == 'technician' ? 'selected' : '' }}>Teknisi</option>
                            <option value="staff" {{ request('role_filter') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto h-[42px] px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                        
                        @if(request()->filled('search') || request()->filled('role_filter'))
                            <a href="{{ route('users.index') }}" class="h-[42px] px-4 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-bold transition shadow-md flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ============================================================== --}}
            {{-- TABEL KELOLA PENGGUNA                                         --}}
            {{-- ============================================================== --}}
            <div class="bg-white dark:bg-[#1e293b] overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left table-auto">
                        <thead class="bg-gradient-to-r from-[#0f2942] to-[#1e405e] text-white text-xs uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">No. WhatsApp</th> {{-- KOLOM BARU --}}
                                <th class="px-6 py-4 whitespace-nowrap">Tanggal Daftar</th>
                                <th class="px-6 py-4 text-center">Jabatan (Role)</th>
                                <th class="px-6 py-4 text-center">Aksi Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-blue-50/20 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    
                                    <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </td>
                                    
                                    <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ $user->phone ?? '-' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                            @if ($user->role == 'admin')
                                                <span
                                                    class="bg-blue-100 text-blue-700 dark:bg-blue-600 dark:text-white border border-blue-200 dark:border-blue-700 text-xs font-bold px-3 py-1.5 rounded uppercase tracking-wider shadow-sm">
                                                    Admin Utama
                                                </span>
                                            @else
                                                <form action="{{ route('users.updateRole', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="role" onchange="this.form.submit()" 
    class="border border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-600 dark:bg-[#0f172a] dark:text-gray-200 rounded-md text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none py-1.5 px-3 transition-colors cursor-pointer w-28">
    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
    <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Teknisi</option>
</select>
                                                </form>
                                            @endif
                                        </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role == 'admin' && $user->email == 'admin@hotel.com')
                                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 italic">Sistem Terkunci</span>
                                        @else
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} permanen?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
                                                    Hapus Akun
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                        Tidak ada data pengguna yang ditemukan.
                                    </td>
                                endtr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($users->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-[#0b1b2b] border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>