<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cetak Label QR Code Ruangan') }}
            </h2>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-colors flex items-center gap-2 print:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Halaman (Print)
            </button>
        </div>
    </x-slot>

    <style>
        @media print {
            body * { visibility: hidden; }
            #area-cetak, #area-cetak * { visibility: visible; }
            #area-cetak { position: absolute; left: 0; top: 0; width: 100%; }
            .min-h-screen { background-color: white !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 print:hidden">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Gunakan kertas ukuran <strong>A4</strong> (kertas stiker). Potong sesuai garis putus-putus dan tempelkan di area kamar atau aset terkait.
                </p>
            </div>

            <div id="area-cetak" class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-white dark:bg-[#1e293b] p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 print:grid-cols-4 print:gap-4 print:shadow-none print:border-none print:p-0">
                
                @php
                    // Ini contoh data ruangan. Nanti bisa kamu hubungkan dengan database Tabel Room/Asset jika ada.
                    $daftar_lokasi = [
                        'Kamar VIP 101', 'Kamar VIP 102', 'Kamar Reguler 201', 'Kamar Reguler 202', 
                        'Lobby Utama', 'Ruang Meeting A', 'Dapur Restoran', 'Lift Penumpang 1',
                        'Mesin Pompa Air', 'Panel Listrik Lt 1', 'Kamar 301', 'Kamar 302'
                    ];
                @endphp

                @foreach($daftar_lokasi as $lokasi)
                <div class="flex flex-col items-center justify-center p-5 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-[#0f172a] text-center print:border-solid print:border-black print:bg-white print:p-4 break-inside-avoid">
                    
                    <h4 class="font-extrabold text-base text-gray-900 dark:text-gray-100 mb-3 print:text-black">
                        {{ $lokasi }}
                    </h4>
                    
                    <div class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 mb-3 print:border-black print:shadow-none">
                        {!! QrCode::size(120)->margin(1)->generate(url('/tickets/create?lokasi=' . urlencode($lokasi))) !!}
                    </div>
                    
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider print:text-gray-700">Scan untuk Lapor Kerusakan</p>
                    <p class="text-[11px] text-blue-600 dark:text-blue-400 font-black mt-1 tracking-widest uppercase print:text-black">Hotel Pangeran</p>
                </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>