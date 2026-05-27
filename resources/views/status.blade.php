<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Filter Status Laporan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan laporan dengan status: <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ str_replace('_', ' ', request('status')) }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ============================================================== --}}
            {{-- TABEL DATA BERGAYA ENTERPRISE (SAMA SEPERTI DASHBOARD)          --}}
            {{-- ============================================================== --}}
            <div class="bg-white dark:bg-[#1e293b] overflow-hidden shadow-sm dark:shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <div class="bg-white dark:bg-[#1e293b] rounded-xl overflow-hidden">
                    
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left table-auto">
                            <thead class="bg-gradient-to-r from-[#0f2942] to-[#1e405e] text-white text-xs uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-lg w-[10%] whitespace-nowrap">No. Tiket</th>
                                    <th class="px-4 py-3 w-[12%] whitespace-nowrap">Tanggal</th>
                                    <th class="px-4 py-3 w-[12%]">Kategori</th>
                                    <th class="px-4 py-3 w-[12%]">Layanan</th>
                                    <th class="px-4 py-3 w-[24%]">Masalah</th>
                                    <th class="px-4 py-3 w-[12%]">Lokasi</th>
                                    <th class="px-4 py-3 text-center w-[10%] whitespace-nowrap">Status</th>
                                    <th class="px-4 py-3 text-center rounded-tr-lg w-[8%] whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="font-bold text-[#0f2942] dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800">
                                                {{ $ticket->ticket_number }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-block px-2 py-1 rounded-md text-[11px] font-semibold bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 break-words">
                                                {{ $ticket->category ? $ticket->category->name : '-' }}
                                            </span>
                                        </td> 
                                        <td class="px-4 py-3">
                                            @if($ticket->service_type == 'perbaikan')
                                                <span class="text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1 text-xs"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Perbaikan</span>
                                            @elseif($ticket->service_type == 'rutin')
                                                <span class="text-blue-600 dark:text-blue-400 font-bold flex items-center gap-1 text-xs"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Rutin</span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-semibold text-gray-800 dark:text-gray-200 line-clamp-2" title="{{ $ticket->title }}">{{ $ticket->title }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-800 dark:text-gray-200 break-words">{{ $ticket->location }}</div>
                                            <div class="text-[11px] text-gray-400 font-medium mt-0.5 flex items-center gap-1 whitespace-nowrap">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $ticket->floor ?? 'Lantai -' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'open' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                                    'assigned' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800',
                                                    'on_progress' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                                    'resolved' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                                    'closed' => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-600'
                                                ];
                                                $dotColors = [
                                                    'open' => 'bg-blue-500', 'assigned' => 'bg-purple-500',
                                                    'on_progress' => 'bg-amber-500', 'resolved' => 'bg-emerald-500', 'closed' => 'bg-gray-400'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] sm:text-xs font-bold border {{ $statusColors[$ticket->status] ?? 'bg-gray-100 dark:bg-gray-800' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$ticket->status] ?? 'bg-gray-400' }}"></span>
                                                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center space-x-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="p-1.5 rounded-md text-blue-600 dark:text-blue-400 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white dark:hover:text-white transition-colors" title="Lihat Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                @if(Auth::user()->role == 'admin')
                                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="p-1.5 rounded-md text-amber-500 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus tiket ini permanen?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 rounded-md text-rose-500 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-colors" title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada laporan dengan status ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($tickets->hasPages())
                        <div class="px-6 py-4 bg-gray-50/50 dark:bg-[#0b1b2b] border-t border-gray-100 dark:border-gray-700">
                            {{ $tickets->links() }}
                        </div>
                    @endif

                </div>
            </div>

            {{-- ============================================================== --}}
            {{-- TOMBOL KEMBALI BESAR DI BAGIAN PALING BAWAH LAYAR               --}}
            {{-- ============================================================== --}}
            <div class="flex justify-center mt-8 mb-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3.5 bg-[#0f2942] dark:bg-gray-700 hover:bg-blue-600 dark:hover:bg-gray-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-200 uppercase text-xs tracking-widest">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard Utama
                </a>
            </div>

        </div>
    </div>
</x-app-layout>