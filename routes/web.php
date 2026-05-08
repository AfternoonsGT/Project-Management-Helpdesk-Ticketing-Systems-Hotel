<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard
Route::get('/dashboard', [TicketController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profile (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTE SISTEM (TIKET & USER)
// Semuanya dibungkus dalam 1 middleware auth
// ==========================================
Route::middleware('auth')->group(function () {

    // --- RUTE MANAJEMEN PENGGUNA (ADMIN ONLY) ---
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // 👈 KEMBALIKAN YANG HILANG
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // --- RUTE MANAJEMEN TIKET ---
    // 1. RUTE STATIS (Wajib ditaruh di paling atas!)
    Route::get('/tickets/export', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // 2. RUTE DINAMIS (Yang memiliki parameter {ticket} di URL-nya)
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // 3. RUTE AKSI SPESIFIK (Tombol-tombol proses) 👇 KEMBALIKAN YANG HILANG 👇
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('/tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('tickets.reject');

    // --- RUTE MANAJEMEN KATEGORI ---
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

});

require __DIR__.'/auth.php';
