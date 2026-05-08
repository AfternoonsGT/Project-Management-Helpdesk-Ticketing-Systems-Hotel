<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna (Karyawan)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <th class="p-3 border-b">Nama</th>
                            <th class="p-3 border-b">Email</th>
                            <th class="p-3 border-b">Tanggal Daftar</th>
                            <th class="p-3 border-b">Jabatan (Role)</th>
                            <th class="p-3 border-b text-center">Aksi Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b hover:bg-gray-50 text-sm">
                                <td class="p-3 font-bold text-gray-800">{{ $user->name }}</td>
                                <td class="p-3 text-gray-600">{{ $user->email }}</td>
                                <td class="p-3 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>

                                <td class="p-3">
                                    @if($user->role == 'admin')
                                        <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider">
                                            👑 Admin Utama
                                        </span>
                                    @else
                                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="border-gray-300 rounded-md text-sm shadow-sm py-1" onchange="this.form.submit()">
                                                <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>🔧 Teknisi</option>
                                                <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>👤 Staff</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>

                                <td class="p-3 text-center">
                                    @if($user->role != 'admin')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin karyawan bernama {{ $user->name }} sudah tidak bekerja lagi? Akun ini akan dihapus permanen.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 font-semibold">
                                                Hapus Akun
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Sistem Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
