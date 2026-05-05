<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>

                    {{-- BAGIAN WIDGET STATISTIK --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <!-- Kotak Total -->
                        <div class="bg-blue-50 p-4 rounded-lg shadow-sm border border-blue-200">
                            <h5 class="text-blue-800 font-bold text-sm uppercase">Total Tiket</h5>
                            <p class="text-3xl font-extrabold text-blue-900 mt-2">{{ $totalTickets }}</p>
                        </div>

                        <!-- Kotak Sedang Dikerjakan -->
                        <div class="bg-yellow-50 p-4 rounded-lg shadow-sm border border-yellow-200">
                            <h5 class="text-yellow-800 font-bold text-sm uppercase">Dikerjakan</h5>
                            <p class="text-3xl font-extrabold text-yellow-900 mt-2">{{ $progressTickets }}</p>
                        </div>

                        <!-- Kotak Selesai (Resolved) -->
                        <div class="bg-green-50 p-4 rounded-lg shadow-sm border border-green-200">
                            <h5 class="text-green-800 font-bold text-sm uppercase">Selesai Menunggu Cek</h5>
                            <p class="text-3xl font-extrabold text-green-900 mt-2">{{ $resolvedTickets }}</p>
                        </div>

                        <!-- Kotak Ditutup (Closed) -->
                        <div class="bg-purple-50 p-4 rounded-lg shadow-sm border border-purple-200">
                            <h5 class="text-purple-800 font-bold text-sm uppercase">Tiket Ditutup</h5>
                            <p class="text-3xl font-extrabold text-purple-900 mt-2">{{ $closedTickets }}</p>
                        </div>
                    </div>

                    {{-- BAGIAN TABEL DAFTAR TIKET --}}
                    <div class="mt-8">
                        <!-- HEADER TABEL & FORM FILTER -->
                        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                            <h4 class="text-lg font-bold w-full md:w-auto">Daftar Tiket</h4>

                            <!-- Form Pencarian (GET Method) -->
                            <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-auto flex flex-col md:flex-row gap-3">

                                <!-- Kolom Search -->
                                <div class="relative w-full md:w-64">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Cari tiket, judul, lokasi...">
                                </div>

                                <!-- Dropdown Filter Bulan -->
                                <!-- onchange="this.form.submit()" membuat tabel otomatis terfilter saat diklik tanpa perlu tombol cari -->
                                <select name="month" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                    <option value="">Semua Bulan</option>
                                    <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>Januari</option>
                                    <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>Februari</option>
                                    <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>Maret</option>
                                    <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>April</option>
                                    <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>Mei</option>
                                    <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>Juni</option>
                                    <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>Juli</option>
                                    <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>Agustus</option>
                                    <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Desember</option>
                                </select>

                                <!-- Dropdown Filter Status / Kategori -->
                                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                    <option value="">Semua Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>

                                <!-- Tombol Submit (Opsional jika ingin menekan enter di kolom search) -->
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-bold shadow">
                                    Cari
                                </button>

                                <!-- Tombol Reset Filter -->
                                @if(request('search') || request('month') || request('status'))
                                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-red-500 px-3 py-2 text-sm flex items-center font-bold">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <!-- TABEL DAFTAR TIKET DIMULAI DI SINI -->
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <table class="min-w-full leading-normal">
...

                        <!-- TABEL DAFTAR TIKET DIMULAI DI SINI -->
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            No. Tiket
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Judul / Masalah
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Lokasi
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap font-bold">
                                                    {{ $ticket->ticket_number }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $ticket->title }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $ticket->location }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <span
                                                    class="bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>

                                            <!-- INI ADALAH LANGKAH 4 YANG DITAMBAHKAN -->
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 text-sm flex items-center gap-2">
                                                <!-- Tombol Default (Semua Bisa Lihat) -->
                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded border border-blue-200">
                                                    Detail
                                                </a>

                                                <!-- Tombol Khusus Admin -->
                                                @if (Auth::user()->role == 'admin')
                                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 px-3 py-1 rounded border border-yellow-200">
                                                        Edit
                                                    </a>

                                                    <!-- Tombol Delete Harus Pakai Form -->
                                                    <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini permanen? Data foto juga akan terhapus.');"
                                                        class="inline-block m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded border border-red-200">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                    <!-- TOMBOL EXPORT PDF & EXCEL -->
                            <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
                                <!-- Tombol Excel (Warna Hijau) -->
                                <!-- Kita kirimkan semua parameter pencarian yang sedang aktif ke URL Export -->
                                <a href="{{ route('tickets.export', ['type' => 'excel', 'search' => request('search'), 'month' => request('month'), 'status' => request('status')]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-bold shadow flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Excel
                                </a>

                                <!-- Tombol PDF (Warna Merah) -->
                                <a href="{{ route('tickets.export', ['type' => 'pdf', 'search' => request('search'), 'month' => request('month'), 'status' => request('status')]) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-bold shadow flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    PDF
                                </a>
                            </div>
                                                @endif
                                            </td>
                                            <!-- AKHIR LANGKAH 4 -->

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-5 py-5 border-b border-gray-200 text-sm text-center text-gray-500">
                                                Belum ada data tiket.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- FITUR PAGINASI (TOMBOL NEXT / PREV) -->
                            <div class="mt-4 p-4 border-t border-gray-200">
                                {{ $tickets->links() }}
                            </div>
                        </div>
                    </div>

                    <p class="mb-4 mt-8">Kamu login sebagai: <span
                            class="uppercase font-bold text-blue-600">{{ Auth::user()->role }}</span></p>

                    <hr class="mb-4">

                    {{-- Tampilan khusus ADMIN --}}
                    @if (Auth::user()->role == 'admin')
                        <div class="bg-blue-100 p-4 rounded">
                            <p>Ini adalah panel kendali <b>Admin</b>. Di sini kamu bisa melihat semua laporan tiket
                                masuk, menugaskan teknisi, dan menutup tiket yang sudah selesai.</p>
                        </div>

                        {{-- Tampilan khusus TEKNISI --}}
                    @elseif(Auth::user()->role == 'technician')
                        <div class="bg-green-100 p-4 rounded">
                            <p>Ini adalah panel <b>Teknisi</b>. Di sini kamu akan melihat daftar tugas perbaikan yang
                                ditugaskan kepadamu.</p>
                        </div>

                        {{-- Tampilan khusus STAFF (Pelapor) --}}
                    @else
                        <div class="bg-yellow-100 p-4 rounded">
                            <p>Ini adalah panel <b>Staff</b>. Temukan fasilitas hotel yang rusak? Silakan buat laporan
                                tiket baru di sini.</p>
                            <br>
                            <a href="{{ route('tickets.create') }}"
                                class="inline-block bg-blue-500 text-white font-bold px-4 py-2 rounded hover:bg-blue-600">
                                + Buat Laporan Kerusakan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
