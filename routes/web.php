<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [TicketController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute yang harus login dulu baru bisa diakses
Route::middleware('auth')->group(function () {
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // 👇 TAMBAHKAN BARIS INI 👇
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    // Rute untuk melihat detail tiket
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    // Rute khusus Admin untuk menugaskan teknisi
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
});

require __DIR__.'/auth.php';
