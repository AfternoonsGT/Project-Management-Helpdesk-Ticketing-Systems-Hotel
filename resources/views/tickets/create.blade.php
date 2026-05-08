<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Kerusakan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6 md:p-8">

                    <div class="mb-8 border-b pb-4">
                        <h3 class="text-2xl font-extrabold text-gray-800">Form Helpdesk Ticketing</h3>
                        <p class="text-gray-500 text-sm mt-1">Harap isi detail laporan selengkap mungkin agar tim Teknisi dapat membawa peralatan yang tepat.</p>
                    </div>

                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100">
                                <h4 class="text-sm font-bold text-blue-700 uppercase tracking-wider mb-5 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    1. Lokasi & Jenis Bantuan
                                </h4>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Masalah <span class="text-red-500">*</span></label>
                                    <select name="category_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                                    <select name="service_type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="">-- Pilih Jenis Layanan --</option>
                                        <option value="perbaikan">🔧 Perbaikan (Rusak/Error)</option>
                                        <option value="perawatan">🧹 Perawatan Rutin (Maintenance)</option>
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Lantai <span class="text-red-500">*</span></label>
                                    <select name="floor" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="">-- Pilih Lantai --</option>
                                        <option value="Basement">Basement</option>
                                        <option value="Lantai 1">Lantai 1 (Lobby)</option>
                                        <option value="Lantai 2">Lantai 2</option>
                                        <option value="Lantai 3">Lantai 3</option>
                                        <option value="Lantai 4">Lantai 4</option>
                                        <option value="Lantai 5">Lantai 5</option>
                                        <option value="Lantai 6">Lantai 6</option>
                                        <option value="Lantai 7">Lantai 7</option>
                                        <option value="Lantai 8">Lantai 8</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Area / Nomor Kamar <span class="text-red-500">*</span></label>
                                    <input type="text" name="location" required placeholder="Contoh: Kamar 302 / Dapur Utama" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                </div>
                            </div>

                            <div class="bg-red-50/50 p-6 rounded-xl border border-red-100">
                                <h4 class="text-sm font-bold text-red-600 uppercase tracking-wider mb-5 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    2. Detail Kerusakan
                                </h4>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Judul Singkat <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" required placeholder="Contoh: AC Bocor Menetes" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 bg-white">
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tingkat Prioritas <span class="text-red-500">*</span></label>
                                    <select name="priority" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 bg-white">
                                        <option value="">-- Pilih Tingkat Keparahan --</option>
                                        <option value="low">🟢 Rendah </option>
                                        <option value="medium">🟡 Sedang </option>
                                        <option value="high">🔴 Tinggi </option>
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="description" rows="3" required placeholder="Jelaskan detail masalahnya di sini..." class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 bg-white"></textarea>
                                </div>

                                <div class="mb-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Upload Foto (Opsional)</label>
                                    <input type="file" name="image_before" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 border border-gray-300 rounded-lg bg-white cursor-pointer transition-colors">
                                    <p class="text-xs text-gray-400 mt-2 font-medium">Format: JPG, PNG, JPEG (Max 2MB)</p>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 font-bold text-sm transition duration-150 py-2 px-4 rounded-lg hover:bg-gray-100">
                                Batal & Kembali
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-blue-500/30 transition duration-200 flex items-center transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Kirim Laporan Sekarang
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
