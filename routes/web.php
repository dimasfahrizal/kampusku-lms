<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassEnrollmentController; // Pastikan Controller ini di-import!
use Illuminate\Support\Facades\Route;

Route::get('/buat-storage', function () {
    \Artisan::call('storage:link');
    return 'Jembatan folder storage berhasil dibuat dengan sukses!';
});
// ==========================================
// 1. RUTE HALAMAN UTAMA (KAMPUSKU)
// ==========================================
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/course/{slug}', [FrontController::class, 'details'])->name('front.details');
Route::post('/course/{slug}/enroll', [FrontController::class, 'enroll'])->name('front.enroll');

// ==========================================
// 2. RUTE PROTECTED (HARUS LOGIN)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama Mahasiswa
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/join-class', [DashboardController::class, 'joinClass'])->name('dashboard.join-class');
    
    // Cetak Laporan Belajar (Fitur Minggu 3)
    Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
    
    // ➕ FITUR MAHASISWA: Join Kelas via Kode Invite
    Route::get('/student/join-class', [ClassEnrollmentController::class, 'index'])->name('student.join.class');
    Route::post('/student/join-class', [ClassEnrollmentController::class, 'enroll'])->name('student.join.class.post');

    // 🚀 SEKARANG DI SINI: Rute Tugas (Bisa diakses Mahasiswa maupun Dosen yang sudah login)
    Route::get('/dashboard/assignments/{assignment}', [DashboardController::class, 'showAssignment'])->name('dashboard.assignment.show');
    Route::post('/dashboard/assignments/{assignment}/submit', [DashboardController::class, 'submitAssignment'])->name('dashboard.assignment.submit');
    Route::post('/assignment/{id}/submit-quiz', [DashboardController::class, 'submitQuiz'])->name('dashboard.assignment.submit-quiz');

    // Pengaturan Profil Mahasiswa (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // 🔐 3. RUTE KHUSUS DOSEN & ADMIN (MULTIROLE)
    // ==========================================
    Route::middleware(['dosen'])->group(function () {
        // Taruh rute murni khusus dosen di sini jika ada halaman kustom di luar Filament.
        // Contoh: Route::get('/dosen/rekap-nilai', [DosenController::class, 'rekap'])->name('dosen.rekap');
    });

});

// Memanggil rute Login, Register, Logout dari Laravel Breeze
require __DIR__.'/auth.php';