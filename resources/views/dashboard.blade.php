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
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Total Laporan</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTickets }}</p>
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



            <!-- KOTAK ADDITIONAL FILTERS (Tanpa Emotikon) -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-bold text-[#0f2942] uppercase tracking-wider">
                        Additional Filters
                    </h3>
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-bold transition">
                        Clear
                    </a>
                </div>

                <div class="p-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                        <!-- Search Box -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tiket..." class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                        </div>

                        <!-- Dropdown Kategori -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Category</label>
                            <select name="category_id" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Lantai (Basement - Lantai 8) -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Floor</label>
                            <select name="floor" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]" onchange="this.form.submit()">
                                <option value="">Semua Lantai</option>
                                <option value="Basement" {{ request('floor') == 'Basement' ? 'selected' : '' }}>Basement</option>
                                @for($i=1; $i<=8; $i++)
                                    <option value="Lantai {{ $i }}" {{ request('floor') == 'Lantai '.$i ? 'selected' : '' }}>Lantai {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Dropdown Bulan -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Month</label>
                            <select name="month" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]" onchange="this.form.submit()">
                                <option value="">Semua Bulan</option>
                                @for($m=1; $m<=12; ++$m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Dropdown Status -->
                        <div>
                            <label class="block text-xs font-bold text-[#0f2942] mb-1 uppercase tracking-wide">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <button type="submit" class="hidden">Cari</button>
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

                        <!-- Tombol Excel (Hijau) -->
                        <button type="submit" name="type" value="excel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-xs font-bold transition shadow-sm">
                            Excel
                        </button>
                        <!-- Tombol PDF (Merah) -->
                        <button type="submit" name="type" value="pdf" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-xs font-bold transition shadow-sm">
                            PDF
                        </button>
                    </form>

                    <!-- Tombol Buat Laporan -->
                    @if(Auth::user()->role == 'staff' || Auth::user()->role == 'admin')
                        <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-md transition">
                            + Buat Laporan
                        </a>
                    @endif
                </div>

                <!-- Tabel Data -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- HEADER WARNA NAVY BLUE -->
                        <thead class="bg-[#0f2942] text-white text-xs uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="p-4 border-r border-[#1a3a5a]">No. Tiket</th>
                                <th class="p-4 border-r border-[#1a3a5a]">Tanggal</th>
                                <th class="p-4 border-r border-[#1a3a5a]">Kategori</th>
                                <th class="p-4 border-r border-[#1a3a5a]">Jenis Layanan</th>
                                <th class="p-4 border-r border-[#1a3a5a]">Masalah</th>
                                <th class="p-4 border-r border-[#1a3a5a]">Lokasi</th>
                                <th class="p-4 border-r border-[#1a3a5a] text-center">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @forelse($tickets as $ticket)
                                <tr class="border-b border-gray-100 hover:bg-blue-50 transition duration-150">
                                    <td class="p-4 text-[#0f2942] font-semibold">{{ $ticket->ticket_number }}</td>
                                    <td class="p-4 text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>

                                    <td class="p-4 font-medium text-blue-700">{{ $ticket->category ? $ticket->category->name : '-' }}</td>

                                    <td class="p-4">
                                        @if($ticket->service_type == 'perbaikan')
                                            <span class="text-red-600 font-semibold">Perbaikan</span>
                                        @elseif($ticket->service_type == 'rutin')
                                            <span class="text-blue-600 font-semibold">Rutin</span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="p-4 font-medium text-gray-800">{{ $ticket->title }}</td>

                                    <!-- 👇 Kolom Lokasi dengan Kamar & Lantai 👇 -->
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $ticket->location }}</div>
                                        <div class="text-xs text-gray-500 mt-1 font-medium">{{ $ticket->floor ?? 'Lantai -' }}</div>
                                    </td>

                                    <!-- 👇 BADGE STATUS DENGAN WARNA BARU 👇 -->
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 rounded text-xs font-bold text-white shadow-sm inline-block min-w-[90px]
                                            {{ $ticket->status == 'open' ? 'bg-blue-500' : '' }}
                                            {{ $ticket->status == 'assigned' ? 'bg-purple-500' : '' }}
                                            {{ $ticket->status == 'on_progress' ? 'bg-yellow-500' : '' }}
                                            {{ $ticket->status == 'resolved' ? 'bg-green-500' : '' }}
                                            {{ $ticket->status == 'closed' ? 'bg-gray-600' : '' }}
                                        ">
                                            {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>

                                    <!-- TOMBOL AKSI -->
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <!-- Detail -->
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-1.5 rounded transition" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            <!-- Admin Actions -->
                                            @if(Auth::user()->role == 'admin')
                                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100 p-1.5 rounded transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>

                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-1.5 rounded transition" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-500 font-medium">Tidak ada data tiket ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-white border-t border-gray-100">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
