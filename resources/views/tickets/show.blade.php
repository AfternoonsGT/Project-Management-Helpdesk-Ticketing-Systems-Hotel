<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tiket: ') }} {{ $ticket->ticket_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- 1. INFO DETAIL KERUSAKAN -->
                <h3 class="text-2xl font-bold mb-2">{{ $ticket->title }}</h3>
                <p class="text-gray-600 mb-4">Lokasi: <b>{{ $ticket->location }}</b> | Status: <span class="bg-gray-200 px-2 py-1 rounded text-sm font-bold uppercase">{{ $ticket->status }}</span></p>

                <div class="bg-gray-50 p-4 rounded border mb-6">
                    <p class="font-semibold mb-2">Deskripsi Kerusakan:</p>
                    <p>{{ $ticket->description }}</p>
                </div>

                <!-- PANEL KONTAK (WHATSAPP) -->
                <div class="bg-blue-50 p-4 rounded border border-blue-200 mb-6 flex flex-col md:flex-row gap-8">

                    <!-- Info Pelapor (Staff) -->
                    <div>
                        <p class="text-sm text-gray-500">Dilaporkan oleh Staff:</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $ticket->reporter->name }}</p>
                        @if($ticket->reporter->phone)
                            <a href="https://wa.me/{{ $ticket->reporter->phone }}" target="_blank" class="inline-flex items-center mt-2 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded hover:bg-green-600">
                                <!-- Icon WA -->
                                <svg class="w-4 h-4 mr-1 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Chat WA Staff
                            </a>
                        @else
                            <p class="text-xs text-red-500 italic mt-1">Nomor HP belum diatur</p>
                        @endif
                    </div>

                    <!-- Info Teknisi -->
                    <div>
                        <p class="text-sm text-gray-500">Dikerjakan oleh Teknisi:</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $ticket->technician ? $ticket->technician->name : 'Belum Ditugaskan' }}</p>
                        @if($ticket->technician && $ticket->technician->phone)
                            <a href="https://wa.me/{{ $ticket->technician->phone }}" target="_blank" class="inline-flex items-center mt-2 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded hover:bg-green-600">
                                <!-- Icon WA -->
                                <svg class="w-4 h-4 mr-1 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Chat WA Teknisi
                            </a>
                        @elseif($ticket->technician)
                            <p class="text-xs text-red-500 italic mt-1">Nomor HP belum diatur</p>
                        @endif
                    </div>
                </div>

                <!-- 2. FOTO SEBELUM & SESUDAH DIPERBAIKI -->
                <div class="flex flex-wrap gap-8 mb-6">
                    @if($ticket->image_before)
                        <div>
                            <p class="font-semibold mb-2 text-red-600">Sebelum Diperbaiki:</p>
                            <img src="{{ asset('storage/' . $ticket->image_before) }}" alt="Foto Kerusakan" class="w-64 h-48 object-cover rounded shadow border border-red-200">
                        </div>
                    @endif

                    @if($ticket->image_after)
                        <div>
                            <p class="font-semibold mb-2 text-green-600">Sesudah Diperbaiki:</p>
                            <img src="{{ asset('storage/' . $ticket->image_after) }}" alt="Foto Selesai" class="w-64 h-48 object-cover rounded shadow border border-green-200">
                        </div>
                    @endif
                </div>

                <hr class="my-6">

                <!-- 3. BAGIAN RIWAYAT & CATATAN -->
                <div class="mb-8">
                    <h4 class="font-bold text-lg mb-4 text-gray-800">Riwayat & Catatan Pengerjaan</h4>
                    <div class="space-y-3">
                        @foreach($histories as $history)
                            <div class="bg-gray-50 border border-gray-200 rounded p-4 text-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-gray-700 uppercase bg-gray-200 px-2 py-1 rounded text-xs">
                                        Status: {{ $history->status }}
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        {{ $history->created_at->format('d M Y - H:i') }}
                                    </span>
                                </div>

                                @if($history->note)
                                    <p class="text-gray-800 mt-2">
                                        <span class="font-semibold text-gray-600">Catatan:</span> <br>
                                        {{ $history->note }}
                                    </p>
                                @else
                                    <p class="text-gray-400 mt-2 italic">Tidak ada catatan.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 4. PANEL KHUSUS ADMIN UNTUK MENUGASKAN TEKNISI -->
                @if(Auth::user()->role == 'admin' && $ticket->status == 'open')
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-6">
                        <h4 class="font-bold text-blue-800 mb-2">Tugaskan Teknisi</h4>
                        <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST" class="flex gap-4 items-center">
                            @csrf
                            <select name="technician_id" class="border-gray-300 rounded shadow-sm w-1/3" required>
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                                Berikan Tugas
                            </button>
                        </form>
                    </div>
                @endif

                <!-- 5. PANEL KHUSUS TEKNISI UNTUK UPDATE STATUS -->
                @if(Auth::user()->role == 'technician' && Auth::id() == $ticket->technician_id)
                    @if($ticket->status == 'assigned' || $ticket->status == 'on_progress')
                        <div class="bg-green-50 border border-green-200 p-4 rounded mb-6">
                            <h4 class="font-bold text-green-800 mb-4">Panel Teknisi: Update Pengerjaan</h4>
                            <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label class="block text-sm font-bold mb-1 text-green-900">Ubah Status Menjadi:</label>
                                    <select name="status" class="border-gray-300 rounded shadow-sm w-full md:w-1/2" required>
                                        @if($ticket->status == 'assigned')
                                            <option value="on_progress">Mulai Dikerjakan (On Progress)</option>
                                        @endif
                                        <option value="resolved">Selesai Diperbaiki (Resolved)</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-bold mb-1 text-green-900">Catatan Perbaikan (Opsional):</label>
                                    <textarea name="note" rows="2" class="border-gray-300 rounded shadow-sm w-full" placeholder="Misal: Kabel sudah disambung ulang dan dilakban..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-bold mb-1 text-green-900">Upload Foto Hasil Perbaikan (Opsional):</label>
                                    <input type="file" name="image_after" class="border border-gray-300 bg-white rounded p-1 w-full md:w-1/2">
                                </div>

                                <button type="submit" class="bg-green-600 text-black font-bold py-2 px-4 rounded hover:bg-green-700">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    @endif
                @endif

                <!-- 6. PANEL KHUSUS ADMIN UNTUK MENUTUP / MENOLAK TIKET -->
                @if(Auth::user()->role == 'admin' && $ticket->status == 'resolved')
                    <div class="bg-purple-50 border border-purple-200 p-4 rounded mb-6">
                        <h4 class="font-bold text-purple-800 mb-2">Verifikasi Admin: Evaluasi Pekerjaan</h4>
                        <p class="text-sm text-purple-700 mb-4">Teknisi telah melaporkan perbaikan selesai. Silakan periksa foto dan catatan sebelum menutup tiket, atau kembalikan jika pekerjaan belum maksimal.</p>

                        <!-- INI BAGIAN YANG DIUBAH: Menggunakan 'formaction' untuk 2 tombol berbeda -->
                        <form method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-1 text-purple-900">Catatan Verifikasi (Wajib diisi jika ditolak):</label>
                                <textarea name="note" rows="2" class="border-gray-300 rounded shadow-sm w-full" placeholder="Misal: Pekerjaan rapi, ATAU Tolong perbaiki lagi, kabel belum dilakban."></textarea>
                            </div>

                            <div class="flex gap-4">
                                <!-- Tombol Setuju (Menuju fungsi close) -->
                                <button type="submit" formaction="{{ route('tickets.close', $ticket->id) }}" class="bg-purple-600 text-black font-bold py-2 px-4 rounded hover:bg-purple-700">
                                    Verifikasi & Tutup Tiket
                                </button>

                                <!-- Tombol Tolak (Menuju fungsi reject) -->
                                <button type="submit" formaction="{{ route('tickets.reject', $ticket->id) }}" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
                                    Tolak & Kembalikan (Revisi)
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- TOMBOL KEMBALI -->
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 underline">&laquo; Kembali ke Dashboard</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
