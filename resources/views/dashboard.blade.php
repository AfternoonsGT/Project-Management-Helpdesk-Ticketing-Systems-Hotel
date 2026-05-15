<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Helpdesk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- WIDGET STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Laporan Baru</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $openTickets }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Sedang Dikerjakan</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $progressTickets }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Selesai (Resolved)</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $resolvedTickets }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-gray-500">
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Ditutup Permanen</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $closedTickets }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2"> Kategori Kerusakan</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2"> Persentase Status Laporan</h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    // 1. RENDER GRAFIK KATEGORI (BAR)
                    const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
                    new Chart(ctxKategori, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartLabels) !!},
                            datasets: [{
                                label: 'Laporan',
                                data: {!! json_encode($chartData) !!},
                                // Bikin warna-warni otomatis
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)', // Blue
                                    'rgba(16, 185, 129, 0.7)', // Green
                                    'rgba(245, 158, 11, 0.7)', // Yellow
                                    'rgba(239, 68, 68, 0.7)',  // Red
                                    'rgba(139, 92, 246, 0.7)', // Purple
                                    'rgba(107, 114, 128, 0.7)' // Gray
                                ],
                                borderWidth: 1,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                            plugins: { legend: { display: false } }
                        }
                    });

                    // 2. RENDER GRAFIK STATUS (DOUGHNUT)
                    const ctxStatus = document.getElementById('statusChart').getContext('2d');
                    new Chart(ctxStatus, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($statusLabels) !!},
                            datasets: [{
                                data: {!! json_encode($statusData) !!},
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',  // Red (Open)
                                    'rgba(245, 158, 11, 0.8)', // Yellow (Progress)
                                    'rgba(16, 185, 129, 0.8)', // Green (Resolved)
                                    'rgba(75, 85, 99, 0.8)'    // Dark Gray (Closed)
                                ],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right' } // Pindah keterangan ke sebelah kanan donat
                            }
                        }
                    });

                });
            </script>



           <!-- KOTAK ADDITIONAL FILTERS -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-bold text-[#0f2942] uppercase tracking-wider">
                        Additional Filters
                    </h3>
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm">
                        Clear
                    </a>
                </div>

                <div class="p-4">
                    <!-- Grid diubah menjadi 4 kolom agar pas dan presisi untuk 8 item (7 input + 1 tombol) -->
                    <!-- Ditambahkan 'items-end' agar tombol sejajar rata bawah dengan input -->
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                        <!-- 1. Search -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tiket..." class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                        </div>

                        <!-- 2. Category -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Kategori</label>
                            <select name="category_id" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Jenis Layanan (BARU) -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Jenis Layanan</label>
                            <select name="service_type" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Layanan</option>
                                <option value="perbaikan" {{ request('service_type') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="rutin" {{ request('service_type') == 'rutin' ? 'selected' : '' }}>Rutin</option>
                            </select>
                        </div>

                        <!-- 4. Floor -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Lantai</label>
                            <select name="floor" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Lantai</option>
                                <option value="Basement" {{ request('floor') == 'Basement' ? 'selected' : '' }}>Basement</option>
                                @for($i=1; $i<=8; $i++)
                                    <option value="Lantai {{ $i }}" {{ request('floor') == 'Lantai '.$i ? 'selected' : '' }}>Lantai {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- 5. Year -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Tahun</label>
                            <select name="year" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Tahun</option>
                                @php
                                    $startYear = 2024; // Tahun sistem ini pertama kali dibuat
                                    $currentYear = date('Y'); // Mengambil tahun saat ini secara otomatis
                                @endphp
                                @for($y = $startYear; $y <= $currentYear; $y++)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- 6. Month -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Bulan</label>
                            <select name="month" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Bulan</option>
                                @for($m=1; $m<=12; ++$m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- 7. Status -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                                <option value="">Semua Status</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <!-- 8. Tombol Submit (Dimunculkan dan Dipercantik) -->
                        <div>
                            <button type="submit" class="w-full h-[42px] bg-[#0f2942] hover:bg-[#1a4066] text-white rounded text-sm font-bold transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Terapkan Filter
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- TABEL DATA & TOMBOL EXPORT -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <!-- Baris Tombol Export -->
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <form method="GET" action="{{ route('tickets.export') }}" class="flex gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <input type="hidden" name="floor" value="{{ request('floor') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">


                    </form>


                </div>

               <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">

                    <form method="GET" action="{{ route('tickets.export') }}" class="flex gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <input type="hidden" name="floor" value="{{ request('floor') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <button type="submit" name="type" value="excel" class="flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </button>

                        <button type="submit" name="type" value="pdf" class="flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export PDF
                        </button>
                    </form>

                   <!-- Tombol Buat Laporan -->
                    @if(Auth::user()->role == 'staff' || Auth::user()->role == 'admin')
                        <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-md transition">
                            + Buat Laporan
                        </a>
                    @endif
                </div>

               <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">

                <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">

                    <form method="GET" action="{{ route('tickets.export') }}" class="flex gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <input type="hidden" name="floor" value="{{ request('floor') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">


                    </form>


                </div>

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
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="font-bold text-[#0f2942] bg-blue-50 px-2 py-1 rounded-md border border-blue-100">{{ $ticket->ticket_number }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-500 whitespace-nowrap">
                                        {{ $ticket->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 break-words">
                                            {{ $ticket->category ? $ticket->category->name : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($ticket->service_type == 'perbaikan')
                                            <span class="text-rose-600 font-bold flex items-center gap-1 text-xs"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Perbaikan</span>
                                        @elseif($ticket->service_type == 'rutin')
                                            <span class="text-blue-600 font-bold flex items-center gap-1 text-xs"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Rutin</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800 line-clamp-2" title="{{ $ticket->title }}">{{ $ticket->title }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-800 break-words">{{ $ticket->location }}</div>
                                        <div class="text-[11px] text-gray-400 font-medium mt-0.5 flex items-center gap-1 whitespace-nowrap">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $ticket->floor ?? 'Lantai -' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'open' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'assigned' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'on_progress' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'resolved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'closed' => 'bg-gray-100 text-gray-600 border-gray-200'
                                            ];
                                            $dotColors = [
                                                'open' => 'bg-blue-500', 'assigned' => 'bg-purple-500',
                                                'on_progress' => 'bg-amber-500', 'resolved' => 'bg-emerald-500', 'closed' => 'bg-gray-400'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] sm:text-xs font-bold border {{ $statusColors[$ticket->status] ?? 'bg-gray-100' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$ticket->status] ?? 'bg-gray-400' }}"></span>
                                            {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center space-x-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            @if(Auth::user()->role == 'admin')
                                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="p-1.5 rounded-md text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus tiket ini permanen?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-md text-rose-500 hover:bg-rose-500 hover:text-white transition-colors" title="Hapus">
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
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="text-gray-500 font-medium">Belum ada tiket yang dilaporkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $tickets->links() }}
                    </div>
                @endif




            </div>

        </div>
    </div>
</x-app-layout>
