<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OfficeController;      // Controller Kantor
use App\Http\Controllers\ShiftController;       // Controller Shift
use App\Http\Controllers\AttendanceController;  // Controller Absensi
use App\Http\Controllers\DashboardController;   // <--- WAJIB ADA: Controller Dashboard
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect homepage ke login
Route::get('/', function () {
    return redirect('/login');
});

// --- ROUTE DASHBOARD (UPDATED) ---
// Sekarang menggunakan Controller, bukan function biasa lagi
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- GRUP ROUTE (Hanya bisa diakses jika Login) ---
Route::middleware('auth')->group(function () {

    // 1. MANAGEMENT PROFILE (Bawaan Laravel)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. MANAGEMENT KANTOR (CRUD + Peta)
    Route::resource('offices', OfficeController::class);

    // 3. MANAGEMENT SHIFT
    Route::resource('shifts', ShiftController::class);

    // 4. MANAGEMENT ABSENSI (Sistem Utama)
    // A. Halaman Riwayat Absensi (Letakkan di atas resource agar prioritas)
    Route::get('/riwayat-absensi', [AttendanceController::class, 'history'])->name('attendances.history');

    // A2. Halaman Admin - Pending Absensi (NOTIFICATION CENTER) - BARU
    Route::get('/admin/pending-absensi', [AttendanceController::class, 'adminPending'])->name('attendances.admin-pending');

    // B. Halaman Utama (Daftar Kantor untuk Absen)
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');

    // C. Halaman Form Absen (Kamera & Peta) - Butuh ID Kantor
    Route::get('/attendances/create/{office}', [AttendanceController::class, 'create'])->name('attendances.create');

    // D. Proses Simpan Absensi (POST)
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    // E. Admin Approve Absensi (PATCH)
    Route::patch('/attendances/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendances.approve');

    // F. Admin Reject Absensi (PATCH)
    Route::patch('/attendances/{attendance}/reject', [AttendanceController::class, 'reject'])->name('attendances.reject');

});

require __DIR__.'/auth.php';