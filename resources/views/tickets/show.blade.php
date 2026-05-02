<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tiket: ') }} {{ $ticket->ticket_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-2">{{ $ticket->title }}</h3>
                <p class="text-gray-600 mb-4">Lokasi: <b>{{ $ticket->location }}</b> | Status: <span class="bg-gray-200 px-2 py-1 rounded text-sm font-bold uppercase">{{ $ticket->status }}</span></p>

                <div class="bg-gray-50 p-4 rounded border mb-6">
                    <p class="font-semibold mb-2">Deskripsi Kerusakan:</p>
                    <p>{{ $ticket->description }}</p>
                </div>

                <!-- FOTONYA DITAMPILKAN JIKA ADA -->
                @if($ticket->image_before)
                    <div class="mb-6">
                        <p class="font-semibold mb-2">Foto Bukti Kerusakan:</p>
                        <img src="{{ asset('storage/' . $ticket->image_before) }}" alt="Foto Kerusakan" class="w-64 rounded shadow">
                    </div>
                @endif

                <hr class="my-6">

                <!-- PANEL KHUSUS ADMIN UNTUK MENUGASKAN TEKNISI -->
                @if(Auth::user()->role == 'admin' && $ticket->status == 'open')
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded">
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

                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 underline">&laquo; Kembali ke Dashboard</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
