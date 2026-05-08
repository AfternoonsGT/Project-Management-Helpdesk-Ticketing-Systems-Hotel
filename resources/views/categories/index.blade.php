<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Master Data: Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Kategori</h3>

                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                                <input type="text" name="name" required placeholder="Contoh: Mesin Air" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow transition duration-200">
                                + Simpan Kategori
                            </button>
                        </form>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Kategori Saat Ini</h3>

                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <th class="p-3 border-b">No</th>
                                    <th class="p-3 border-b">Nama Kategori</th>
                                    <th class="p-3 border-b text-center">Aksi Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                    <tr class="border-b hover:bg-gray-50 text-sm">
                                        <td class="p-3 text-gray-600">{{ $index + 1 }}</td>
                                        <td class="p-3 font-bold text-gray-800">{{ $category->name }}</td>
                                        <td class="p-3 text-center">
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 font-semibold transition duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($categories->isEmpty())
                            <p class="text-center text-gray-500 mt-4 italic text-sm">Belum ada kategori yang ditambahkan.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
