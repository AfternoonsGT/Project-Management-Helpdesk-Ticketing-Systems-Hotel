<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Menampilkan daftar semua pengguna
  public function index(Request $request)
{
    $query = User::query();

    // 1. Fitur Pencarian (Nama, Email, atau Nomor HP)
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%');
        });
    }

    // 2. Fitur Filter Berdasarkan Jabatan (Role)
    if ($request->filled('role_filter')) {
        $query->where('role', $request->role_filter);
    }

    // Ambil data pengguna dengan pagination (misal 10 data per halaman)
    $users = $query->latest()->paginate(10)->withQueryString();

    return view('users.index', compact('users'));
}

    // 2. Fungsi untuk mengubah jabatan (Role)
   public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        // 👇 PERLINDUNGAN 1: Mencegah pengubahan jabatan Admin Utama
        if ($user->role == 'admin') {
            return back()->with('error', 'Jabatan Admin Utama tidak dapat diubah!');
        }

        // 👇 PERLINDUNGAN 2: Opsi admin dihapus dari validasi
        $request->validate([
            'role' => 'required|in:technician,staff'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Jabatan pengguna berhasil diperbarui!');
    }

    // 3. Fungsi untuk menghapus pengguna
    public function destroy(User $user)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        // 👇 PERLINDUNGAN 3: Mencegah penghapusan akun Admin Utama
        if ($user->role == 'admin' || Auth::id() == $user->id) {
            return back()->with('error', 'Akun Admin Utama tidak boleh dihapus!');
        }

        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus dari sistem!');
    }
}
