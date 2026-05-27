<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Kategori Kerusakan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-[#1e293b] overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
                        <h3 class="text-sm font-bold text-[#0f2942] dark:text-blue-300 uppercase tracking-wider mb-4 border-b dark:border-gray-700 pb-2">
                            Tambah Kategori Baru
                        </h3>

                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">Nama Kategori</label>
                                <input type="text" name="name" required placeholder="Contoh: AC & Pendingin" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:text-gray-200 dark:placeholder-gray-500 rounded text-sm focus:ring-[#0f2942] focus:border-[#0f2942]">
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded text-sm shadow transition duration-150">
                                Simpan Kategori
                            </button>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-[#1e293b] overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-200">
                        <div class="p-6">
                            <h3 class="text-sm font-bold text-[#0f2942] dark:text-blue-300 uppercase tracking-wider mb-4 border-b dark:border-gray-700 pb-2">
                                Daftar Kategori Aktif
                            </h3>

                            <div class="overflow-x-auto w-full">
                                <table class="w-full text-left table-auto">
                                    <thead class="bg-gray-50 dark:bg-[#1e3a8a]/40 text-gray-500 dark:text-blue-200 text-xs uppercase tracking-wider border-b dark:border-blue-900/50">
                                        <tr>
                                            <th class="px-6 py-4 font-bold w-[10%] text-center">No</th>
                                            <th class="px-6 py-4 font-bold w-[65%]">Nama Kategori</th>
                                            <th class="px-6 py-4 font-bold w-[25%] text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                                        @forelse($categories as $index => $category)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                
                                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 font-medium">
                                                    {{ $index + 1 }}
                                                </td>
                                                
                                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-200">
                                                    {{ $category->name }}
                                                </td>
                                                
                                                <td class="px-6 py-4 text-center">
                                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua tiket terkait kategori ini mungkin akan terpengaruh.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 dark:text-rose-400 border border-red-100 dark:border-rose-800 px-3 py-1.5 rounded text-xs font-bold transition-colors">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">
                                                    Belum ada data kategori kerusakan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>