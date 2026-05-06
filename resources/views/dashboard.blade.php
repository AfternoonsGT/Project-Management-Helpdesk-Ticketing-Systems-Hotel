<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Helpdesk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- PESAN SUKSES -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

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

            <!-- AREA FILTER & TOMBOL EXPORT -->
            <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">

                <!-- Form Pencarian & Filter -->
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-2 w-full md:w-auto">

                    <!-- Search -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tiket, lokasi..." class="border-gray-300 rounded-md text-sm">

                    <!-- FILTER BARU: KATEGORI -->
                    <select name="category_id" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter Bulan -->
                    <select name="month" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @for($m=1; $m<=12; ++$m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>

                    <!-- Filter Status -->
                    <select name="status" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <!-- Tombol Reset -->
                    <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm hover:bg-gray-300">Reset</a>
                </form>

                <!-- Tombol Export & Buat Tiket -->
                <div class="flex gap-2">
                    <form method="GET" action="{{ route('tickets.export') }}" class="flex gap-2">
                        <!-- Bawa data filter saat export -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <button type="submit" name="type" value="pdf" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-bold">📄 PDF</button>
                        <button type="submit" name="type" value="excel" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-bold">📊 Excel</button>
                    </form>

                    @if(Auth::user()->role == 'staff' || Auth::user()->role == 'admin')
                        <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold">+ Buat Laporan</a>
                    @endif
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                                <th class="p-3 border-b">No. Tiket</th>
                                <th class="p-3 border-b">Tanggal</th>
                                <th class="p-3 border-b">Kategori</th>
                                <th class="p-3 border-b">Jenis Layanan</th>
                                <th class="p-3 border-b">Masalah</th>
                                <th class="p-3 border-b">Status</th>
                                <th class="p-3 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-b hover:bg-gray-50 text-sm">
                                    <td class="p-3 font-semibold text-blue-600">{{ $ticket->ticket_number }}</td>
                                    <td class="p-3">{{ $ticket->created_at->format('d M Y') }}</td>

                                    <!-- Terjemahan Kategori -->
                                    <td class="p-3 font-medium">{{ $ticket->category ? $ticket->category->name : '-' }}</td>

                                    <!-- Terjemahan Jenis Layanan -->
                                    <td class="p-3">
                                        @if($ticket->service_type == 'perbaikan')
                                            <span class="text-red-600">🚨 Perbaikan</span>
                                        @elseif($ticket->service_type == 'rutin')
                                            <span class="text-blue-600">🔧 Servis Rutin</span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="p-3">{{ $ticket->title }} ({{ $ticket->location }})</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold text-white
                                            {{ $ticket->status == 'open' ? 'bg-red-500' : '' }}
                                            {{ $ticket->status == 'assigned' ? 'bg-yellow-500' : '' }}
                                            {{ $ticket->status == 'on_progress' ? 'bg-blue-500' : '' }}
                                            {{ $ticket->status == 'resolved' ? 'bg-green-500' : '' }}
                                            {{ $ticket->status == 'closed' ? 'bg-gray-600' : '' }}
                                        ">
                                            {{ strtoupper($ticket->status) }}
                                        </span>
                                    </td>

                                    <!-- 👇 AKSI DENGAN TOMBOL EDIT & DELETE ADMIN 👇 -->
                                    <td class="p-3 flex space-x-2">
                                        <!-- Tombol Detail (Bisa dilihat Semua Role) -->
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-xs hover:bg-indigo-200 font-semibold">Detail</a>

                                        <!-- KEMBALINYA TOMBOL SAKTI KHUSUS ADMIN -->
                                        @if(Auth::user()->role == 'admin')
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('tickets.edit', $ticket->id) }}" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs hover:bg-yellow-200 font-semibold">Edit</a>

                                            <!-- Tombol Delete -->
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini beserta fotonya secara permanen?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 font-semibold">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                    <!-- 👆 BATAS TOMBOL ADMIN 👆 -->

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-gray-500">Tidak ada data tiket yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
