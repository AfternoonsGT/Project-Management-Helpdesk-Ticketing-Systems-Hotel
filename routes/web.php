<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes (Peta Jalur Aplikasi Helpdesk)
|--------------------------------------------------------------------------
| Di sinilah semua rute web untuk aplikasi didaftarkan.
| Rute dikelompokkan berdasarkan fitur dan hak akses untuk
| memudahkan proses pemeliharaan (maintenance) di masa depan.
|--------------------------------------------------------------------------
*/

// ================================================================
// 1. AREA PUBLIK (Bisa diakses tanpa login)
// ================================================================
Route::get('/', function () {
    return view('welcome');
});

// ================================================================
// 2. AREA PENGGUNA TERDAFTAR (Wajib Login)
// ================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman Utama setelah login (Dashboard)
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');

    // Manajemen Profil Akun (Bawaan Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// ================================================================
// 3. AREA SISTEM INTI: HELPDESK TICKETING
// ================================================================
Route::middleware('auth')->group(function () {

    // --- A. Rute Statis ---
    // (PENTING: Rute statis seperti /export dan /create wajib diletakkan
    // di ATAS rute dinamis /{ticket} agar tidak terjadi bentrok URL)
    Route::get('/tickets/export', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    
    // Rute untuk mengklik/membaca notifikasi
    Route::get('/notifications/{id}/read', [TicketController::class, 'readNotification'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [TicketController::class, 'markAllRead'])->name('notifications.markAllRead');

    // --- Rute Cetak QR Code ---
    Route::get('/cetak-qr', function () {
        return view('qrcode.print');
    })->name('qrcode.print');

    // --- B. Rute Dinamis (CRUD Dasar) ---
    // (Menggunakan parameter {ticket} untuk memanggil ID spesifik)
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // --- C. Rute Aksi Spesifik (Sesuai Role Pekerjaan) ---
    // Penugasan (Oleh Admin)
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    // Update Status Pengerjaan (Oleh Teknisi)
    Route::post('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    // Verifikasi Akhir (Oleh Admin)
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('/tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('tickets.reject');

});

// ================================================================
// 4. AREA MASTER DATA (Panel Admin & Pengaturan)
// Catatan: Pengecekan role 'admin' dilakukan di dalam Controller
// ================================================================
Route::middleware('auth')->group(function () {

    // --- Kelola Karyawan / Pengguna ---
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // --- Kelola Kategori Kerusakan ---
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // --- Pengaturan Sistem ---
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
    // --- Pengaturan Sistem ---
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Fitur Baru: Backup & Optimizer
    Route::post('/settings/optimize', [SettingController::class, 'optimizeStorage'])->name('settings.optimize');
    Route::post('/settings/backup', [SettingController::class, 'backupDatabase'])->name('settings.backup');

});

require __DIR__.'/auth.php';