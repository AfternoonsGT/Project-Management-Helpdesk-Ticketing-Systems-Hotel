<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan: ') }} <span class="text-blue-600">{{ $ticket->ticket_number }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

                <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm transition duration-150 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                        <div class="flex justify-between items-start border-b pb-4 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $ticket->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Dilaporkan pada: {{ $ticket->created_at->format('d M Y - H:i') }}</p>
                            </div>
                            <div>
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'assigned' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'on_progress' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'resolved' => 'bg-green-100 text-green-800 border-green-200',
                                        'closed' => 'bg-gray-200 text-gray-800 border-gray-300',
                                    ];
                                    $statusLabels = [
                                        'open' => 'OPEN',
                                        'assigned' => 'DITUGASKAN',
                                        'on_progress' => 'DIPROSES',
                                        'resolved' => 'SELESAI (RESOLVED)',
                                        'closed' => 'DITUTUP',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$ticket->status] ?? strtoupper($ticket->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                           <!-- INFO PELAPOR + TOMBOL WA -->
    <div class="bg-gray-50 p-3 rounded-lg border">
        <span class="block text-gray-500 text-xs font-bold uppercase mb-1">Pelapor</span>
        <div class="flex flex-col gap-2">
            <span class="font-semibold text-gray-800">{{ $ticket->reporter?->name ?? 'Karyawan Dihapus' }}</span>
            
            @if($ticket->reporter && $ticket->reporter->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ticket->reporter->phone) }}?text=Halo%20{{ $ticket->reporter->name }},%20saya%20Admin%20Engineering%20ingin%20konfirmasi%20tiket%20{{ $ticket->ticket_number }}" 
                   target="_blank" 
                   class="inline-flex items-center justify-center gap-1 bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-[10px] font-bold transition w-fit">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.626 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            @endif
        </div>
    </div>

    <!-- INFO TEKNISI + TOMBOL WA -->
    <div class="bg-gray-50 p-3 rounded-lg border">
        <span class="block text-gray-500 text-xs font-bold uppercase mb-1">Teknisi</span>
        <div class="flex flex-col gap-2">
            <span class="font-semibold text-gray-800">{{ $ticket->technician?->name ?? 'Belum Ditugaskan' }}</span>
            
            @if($ticket->technician && $ticket->technician->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ticket->technician->phone) }}?text=Halo%20{{ $ticket->technician->name }},%20terkait%20pekerjaan%20di%20tiket%20{{ $ticket->ticket_number }}" 
                   target="_blank" 
                   class="inline-flex items-center justify-center gap-1 bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-[10px] font-bold transition w-fit">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.626 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            @endif
        </div>
    </div>
                            <div class="bg-gray-50 p-3 rounded-lg border">
                                <span class="block text-gray-500 text-xs font-bold uppercase mb-1">Kategori</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->category ? $ticket->category->name : '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border">
                                <span class="block text-gray-500 text-xs font-bold uppercase mb-1">Lokasi</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->floor }} - {{ $ticket->location }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border">
                                <span class="block text-gray-500 text-xs font-bold uppercase mb-1">Prioritas</span>
                                <span class="font-semibold {{ $ticket->priority == 'tinggi' ? 'text-red-600' : ($ticket->priority == 'sedang' ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ strtoupper($ticket->priority) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Deskripsi Lengkap
                        </h4>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed bg-gray-50 p-4 rounded-lg border">{{ $ticket->description }}</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Lampiran Foto
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="block text-center text-xs font-bold text-red-500 bg-red-50 py-1 rounded-t-lg border-t border-l border-r">SEBELUM PERBAIKAN</span>
                                <div class="border rounded-b-lg p-2 bg-gray-50 flex justify-center items-center h-48">
                                    @if($ticket->image_before)
                                        <img src="{{ asset('storage/' . $ticket->image_before) }}" class="max-h-full rounded shadow-sm object-contain">
                                    @else
                                        <span class="text-gray-400 italic text-sm">Tidak ada foto terlampir</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="block text-center text-xs font-bold text-green-500 bg-green-50 py-1 rounded-t-lg border-t border-l border-r">SESUDAH PERBAIKAN</span>
                                <div class="border rounded-b-lg p-2 bg-gray-50 flex justify-center items-center h-48">
                                    @if($ticket->image_after)
                                        <img src="{{ asset('storage/' . $ticket->image_after) }}" class="max-h-full rounded shadow-sm object-contain">
                                    @else
                                        <span class="text-gray-400 italic text-sm">Belum ada foto perbaikan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1 space-y-6">

                    @if($ticket->status != 'closed')
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-blue-200">
                            <div class="bg-blue-50 p-4 border-b border-blue-100">
                                <h4 class="font-bold text-blue-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Panel Tindakan
                                </h4>
                            </div>
                            <div class="p-4 space-y-4">

                                @if(Auth::user()->role == 'admin' && $ticket->status == 'open')
                                    <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                        @csrf
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tugaskan Kepada:</label>
                                        <select name="technician_id" class="w-full text-sm border-gray-300 rounded-lg shadow-sm mb-3" required>
                                            <option value="">-- Pilih Teknisi --</option>
                                            @foreach($technicians as $tech)
                                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                                            Kirim Penugasan
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::user()->role == 'technician' && Auth::id() == $ticket->technician_id && in_array($ticket->status, ['assigned', 'on_progress']))
                                    <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Update Status Pengerjaan:</label>
                                        <select name="status" class="w-full text-sm border-gray-300 rounded-lg shadow-sm mb-3" required>
                                            <option value="on_progress" {{ $ticket->status == 'on_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                            <option value="resolved">Selesai (Resolved)</option>
                                        </select>
                                        <textarea name="note" rows="2" class="w-full text-sm border-gray-300 rounded-lg shadow-sm mb-3" placeholder="Catatan perbaikan..."></textarea>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Bukti Foto Selesai (Jika Selesai):</label>
                                        <input type="file" name="image_after" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 mb-3 border rounded-lg">
                                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                                            Update Laporan
                                        </button>
                                    </form>
                                @endif

                               @if(Auth::user()->role == 'admin' && $ticket->status == 'resolved')
                                    <p class="text-xs text-gray-600 mb-3">Teknisi telah menyelesaikan pekerjaan. Silakan lakukan verifikasi.</p>

                                    <label class="block text-xs font-bold text-gray-700 mb-1">Catatan (Wajib diisi jika menolak):</label>
                                    <textarea name="note" form="form-reject" rows="2" class="w-full text-xs border-gray-300 rounded-lg shadow-sm mb-3 focus:ring-red-500 focus:border-red-500" placeholder="Ketik alasan penolakan di sini..."></textarea>
                                    <div class="flex gap-2">
                                        <form action="{{ route('tickets.close', $ticket->id) }}" method="POST" class="w-1/2">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tutup tiket ini secara permanen?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-2 rounded-lg shadow transition text-xs text-center">
                                                 ACC & Tutup
                                            </button>
                                        </form>

                                        <form id="form-reject" action="{{ route('tickets.reject', $ticket->id) }}" method="POST" class="w-1/2">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tolak pekerjaan ini dan kembalikan ke teknisi?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-2 rounded-lg shadow transition text-xs text-center">
                                                 Tolak (Revisi)
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jejak Riwayat
                        </h4>

                        <div class="relative border-l border-gray-200 ml-3 space-y-6">
                            @foreach($histories as $history)
                                <div class="mb-4 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <h5 class="flex items-center mb-1 text-sm font-semibold text-gray-900">
                                        {{ $history->user?->name ?? 'Sistem' }}
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-2">{{ strtoupper($history->status) }}</span>
                                    </h5>
                                    <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $history->created_at->format('d M Y, H:i') }}</time>
                                    <p class="mb-4 text-sm font-normal text-gray-500 bg-gray-50 p-2 rounded border border-gray-100">{{ $history->note ?? 'Status diperbarui.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
