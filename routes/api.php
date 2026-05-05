<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SaldoController;
use App\Http\Controllers\Api\QrisController;
use App\Http\Controllers\Api\CashflowController;
use App\Http\Controllers\Api\EdukasiController;
use App\Http\Controllers\Api\UMKMController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\PengaturanController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

//Publik (pelanggan/turis & super admin)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

//untuk pelanggan/turis yang telah login dan tidak di ban
Route::middleware(['auth:sanctum', 'banned'])->group(function () {
    //Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/set-pin', [AuthController::class, 'setPin']);

    //User
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user/send-otp-sensitive', [UserController::class, 'sendOtpForSensitive']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/change-pin', [UserController::class, 'changePin']);

    //Dashboard user
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //Saldo
    Route::get('/saldo', [SaldoController::class, 'show']);
    Route::post('/saldo/topup', [SaldoController::class, 'topup']);
    Route::post('/saldo/transfer', [SaldoController::class, 'transfer']);

    //QRIS
    Route::post('/qris/generate', [QrisController::class, 'generate']);
    Route::post('/qris/scan', [QrisController::class, 'scan']);

    //Cashflow
    Route::get('/cashflow', [CashflowController::class, 'laporan']);

    //Edukasi (lihat saja)
    Route::get('/edukasi', [EdukasiController::class, 'index']);
    Route::get('/edukasi/{id}', [EdukasiController::class, 'show']);

    //UMKM & Produk (lihat saja)
    Route::get('/umkm', [UMKMController::class, 'index']);
    Route::get('/umkm/{id}', [UMKMController::class, 'show']);
    Route::get('/produk', [ProdukController::class, 'index']);
});

//Untuk Super Admin
Route::middleware(['auth:sanctum', 'banned', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    //kelola user
    Route::apiResource('users', AdminUserController::class)->except(['show']);

    //monitoring transaksi
    Route::get('transaksi', [AdminTransaksiController::class, 'index']);
    Route::get('transaksi/{id}', [AdminTransaksiController::class, 'show']);

    //kelola edukasi
    Route::post('/edukasi', [EdukasiController::class, 'store']);
    Route::put('/edukasi/{id}', [EdukasiController::class, 'update']);
    Route::delete('/edukasi/{id}', [EdukasiController::class, 'destroy']);

    //kelola UMKM
    Route::post('/umkm', [UMKMController::class, 'store']);
    Route::put('/umkm/{id}', [UMKMController::class, 'update']);
    Route::delete('/umkm/{id}', [UMKMController::class, 'destroy']);

    //kelola Produk
    Route::post('/produk', [ProdukController::class, 'store']);
    Route::put('/produk/{id}', [ProdukController::class, 'update']);
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

    //pengaturan sistem
    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::put('/pengaturan', [PengaturanController::class, 'update']);
});