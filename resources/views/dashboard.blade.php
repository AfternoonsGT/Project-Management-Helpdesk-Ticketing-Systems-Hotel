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

                    {{-- BAGIAN TABEL DAFTAR TIKET --}}
                    <div class="mt-8">
                        <h4 class="text-lg font-bold mb-4">Daftar Tiket Kerusakan</h4>

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
                                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold underline">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-5 py-5 border-b border-gray-200 text-sm text-center text-gray-500">
                                                Belum ada data tiket.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <p class="mb-4">Kamu login sebagai: <span
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
                            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <a href="{{ route('tickets.create') }}"
                                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    + Buat Laporan Kerusakan
                                </a>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
