<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // 1. Menampilkan Halaman Kelola Kategori
    public function index()
    {
        if (Auth::user()->role != 'admin') abort(403);

        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    // 2. Menyimpan Kategori Baru
    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name' // Mencegah nama kategori kembar
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // 3. Menghapus Kategori
    public function destroy(Category $category)
    {
        if (Auth::user()->role != 'admin') abort(403);

        // Fitur Keamanan Ekstra: Cek apakah kategori ini sudah dipakai di tiket?
        // (Asumsi relasi di model Category -> tickets() sudah ada, jika belum ini diabaikan)
        if (\App\Models\Ticket::where('category_id', $category->id)->exists()) {
            return back()->with('error', 'Gagal! Kategori ini sedang digunakan oleh laporan tiket. Hapus tiketnya terlebih dahulu.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
