<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

               <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Kategori Kerusakan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Kategori Kerusakan</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 👇 INI ADALAH TAMBAHAN UNTUK JENIS LAYANAN 👇 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Jenis Layanan</label>
                        <select name="service_type" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="" disabled selected>-- Pilih Jenis Pekerjaan --</option>
                            <option value="perbaikan">🚨 Perbaikan Kerusakan (Corrective)</option>
                            <option value="rutin">🔧 Servis Rutin / Perawatan (Preventive)</option>
                        </select>
                    </div>
                    <!-- 👆 AKHIR TAMBAHAN JENIS LAYANAN 👆 -->

                    <!-- Lokasi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Lokasi (Misal: Kamar 302)</label>
                        <input type="text" name="location" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Judul Laporan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Judul Laporan</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: AC Bocor" required>
                    </div>

                    <!-- Deskripsi Detail -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Deskripsi Detail</label>
                        <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Jelaskan kondisi kerusakannya..." required></textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Foto Bukti Kerusakan (Opsional)</label>
                        <input type="file" name="image_before" class="w-full text-gray-700 border border-gray-300 rounded-md p-2">
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>

                    <!-- Tingkat Prioritas -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Tingkat Prioritas</label>
                        <select name="priority" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="low">Rendah (Low)</option>
                            <option value="medium">Sedang (Medium)</option>
                            <option value="high">Tinggi / Urgent (High)</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Kirim Laporan
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
