<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Laporan: ') }} <span class="text-blue-600 dark:text-blue-400">{{ $ticket->ticket_number }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#1e293b] overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700 transition-colors duration-200">
                <div class="p-6 md:p-8">

                    <div class="mb-8 border-b dark:border-gray-700 pb-4">
                        <h3 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">Revisi Detail Laporan</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Gunakan form ini untuk memperbaiki kesalahan ketik atau mengubah kategori laporan.</p>
                    </div>

                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-6 rounded-xl border border-blue-100 dark:border-blue-900/30">
                                <h4 class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-5 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    1. Perbaikan Kategori & Lokasi
                                </h4>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Kategori Masalah <span class="text-red-500">*</span></label>
                                    <select name="category_id" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#0f172a] dark:text-gray-200">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Jenis Layanan <span class="text-red-500">*</span></label>
                                    <select name="service_type" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#0f172a] dark:text-gray-200">
                                        <option value="">-- Pilih Jenis Layanan --</option>
                                        <option value="perbaikan" {{ $ticket->service_type == 'perbaikan' ? 'selected' : '' }}> Perbaikan (Rusak/Error)</option>
                                        <option value="perawatan" {{ $ticket->service_type == 'perawatan' ? 'selected' : '' }}> Perawatan Rutin (Maintenance)</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Area / Nomor Kamar <span class="text-red-500">*</span></label>
                                    <input type="text" name="location" value="{{ old('location', $ticket->location) }}" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#0f172a] dark:text-gray-200">
                                </div>
                            </div>

                            <div class="bg-amber-50/50 dark:bg-amber-900/10 p-6 rounded-xl border border-amber-100 dark:border-amber-900/30">
                                <h4 class="text-sm font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-5 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    2. Perbaikan Teks Laporan
                                </h4>

                                <div class="mb-5">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Judul Singkat <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $ticket->title) }}" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 bg-white dark:bg-[#0f172a] dark:text-gray-200">
                                </div>

                                <div class="mb-2">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="description" rows="5" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 bg-white dark:bg-[#0f172a] dark:text-gray-200">{{ old('description', $ticket->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t dark:border-gray-700 pt-6">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white font-bold text-sm transition duration-150 py-2 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                Batal & Kembali
                            </a>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-amber-500/30 transition duration-200 flex items-center transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>