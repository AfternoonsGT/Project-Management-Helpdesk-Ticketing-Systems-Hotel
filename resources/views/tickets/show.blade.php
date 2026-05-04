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
