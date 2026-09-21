<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route bantuan setup admin
Route::get('/setup-admin', function () {
    $admin = \App\Models\User::updateOrCreate(
        ['email' => 'admin@aulacoffee.id'],
        [
            'name' => 'Admin',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );

    $owner = \App\Models\User::updateOrCreate(
        ['email' => 'owner@aulacoffee.id'],
        [
            'name' => 'Owner',
            'password' => 'password',
            'role' => 'owner',
            'email_verified_at' => now(),
        ]
    );

    $karyawan = \App\Models\User::updateOrCreate(
        ['email' => 'karyawan1@gmail.com'],
        [
            'name' => 'Karyawan 1',
            'password' => 'password',
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Admin, Owner, dan Karyawan berhasil dibuat / diperbarui!',
        'users' => \App\Models\User::all(['id', 'name', 'email', 'role']),
    ]);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    Route::get('/api/notifikasi/count', [NotifikasiController::class, 'countUnread']);
    Route::get('/api/notifikasi/unread', [NotifikasiController::class, 'getUnread']);

    // Admin Routes
    Route::middleware(['role:admin,owner'])->group(function () {
        // Barang Management
        Route::resource('barang', BarangController::class);
        
        // Stok View
        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
        Route::post('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
        Route::post('/laporan/tahunan', [LaporanController::class, 'tahunan'])->name('laporan.tahunan');
        Route::post('/laporan/download/harian', [LaporanController::class, 'downloadHarian'])->name('laporan.download-harian');
        Route::post('/laporan/download/bulanan', [LaporanController::class, 'downloadBulanan'])->name('laporan.download-bulanan');
        Route::post('/laporan/download/tahunan', [LaporanController::class, 'downloadTahunan'])->name('laporan.download-tahunan');
        // Employee Status for Owner
        Route::get('/karyawan-status', [DashboardController::class, 'karyawanStatus'])->name('owner.karyawan.status');
        
        // User Management
        Route::get('/users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [App\Http\Controllers\AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [App\Http\Controllers\AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Karyawan Routes
    Route::middleware(['role:karyawan'])->group(function () {
        // Shift
        Route::get('/shift/start', [ShiftController::class, 'create'])->name('shift.create');
        Route::post('/shift/start', [ShiftController::class, 'store'])->name('shift.store');
        Route::get('/shift/end', [ShiftController::class, 'end'])->name('shift.end');
        Route::post('/shift/end', [ShiftController::class, 'endShift'])->name('shift.end-shift');

        // Stok Input
        Route::get('/stok/input', [StokController::class, 'create'])->name('stok.create');
        Route::post('/stok/input', [StokController::class, 'store'])->name('stok.store');
        Route::get('/stok/lihat', [StokController::class, 'lihatStok'])->name('stok.lihat');
    });
});

