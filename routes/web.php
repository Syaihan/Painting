<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSealingController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

// 1. Route Tamu (Hanya untuk yang BELUM login)
Route::middleware(['guest.custom'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. Route yang Membutuhkan Login (Menggunakan group middleware standar/custom, TANPA Closure)
Route::middleware(['auth.custom'])->group(function () {

    // Manajemen User & Registrasi Terpusat (Khusus Admin)
    Route::get('/manajemen-user', [AuthController::class, 'indexManajemenUser'])->name('manajemen.user');
    Route::post('/manajemen-user/register', [AuthController::class, 'register'])->name('register.process');
    Route::put('/manajemen-user/{id}', [AuthController::class, 'updateUser'])->name('users.update');
    Route::delete('/manajemen-user/{id}', [AuthController::class, 'deleteUser'])->name('users.delete');

    // Histori Session / Riwayat Login-Logout (Khusus Admin)
    Route::get('/histori-session', [AuthController::class, 'historiSession'])->name('session.history');

    // MASTER SEALING
    Route::prefix('master-sealing')->middleware('permission:access-master-part')->name('master.sealing.')->group(function () {
        Route::get('/', [MasterSealingController::class, 'index'])->name('index');
        // Finish Good Routes
        Route::post('/fg/store', [MasterSealingController::class, 'storeFg'])->name('fg.store');
        Route::put('/fg/update/{id}', [MasterSealingController::class, 'updateFg'])->name('fg.update');
        Route::delete('/fg/delete/{id}', [MasterSealingController::class, 'destroyFg'])->name('fg.destroy');
        // Child Part Routes
        Route::post('/cp/store', [MasterSealingController::class, 'storeCp'])->name('cp.store');
        Route::put('/cp/update/{id}', [MasterSealingController::class, 'updateCp'])->name('cp.update');
        Route::delete('/cp/delete/{id}', [MasterSealingController::class, 'destroyCp'])->name('cp.destroy');
        // BOM Routes
        Route::post('/bom/store', [MasterSealingController::class, 'storeBom'])->name('bom.store');
        Route::put('/bom/update/{barcode}/{childPart}', [MasterSealingController::class, 'updateBom'])->where(['barcode' => '[^/]+', 'childPart' => '[^/]+'])->name('bom.update');
        Route::delete('/bom/delete/{barcode}/{childPart}', [MasterSealingController::class, 'destroyBom'])->where(['barcode' => '[^/]+', 'childPart' => '[^/]+'])->name('bom.destroy');
    });

    // SCAN/console
    Route::get('/scan/console', [ScanController::class, 'console'])->middleware('permission:access-scan-console')->name('scan.console');
    Route::post('/scan/store', [ScanController::class, 'consolestore'])->middleware('permission:access-scan-console')->name('scan.store');
    Route::get('/scan/stock-card', [ScanController::class, 'stockCard'])->middleware('permission:access-stock-card')->name('scan.stock_card');
    Route::post('/scan/stock-adjustment/store', [ScanController::class, 'adjustmentStore'])->middleware('permission:access-stock-adjustment')->name('scan.stock_adjustment.store');
    Route::get('/scan/stock-in', [ScanController::class, 'stockInForm'])->middleware('permission:access-stock-in')->name('scan.stock_in.form');
    Route::post('/scan/stock-in', [ScanController::class, 'stockInStore'])->middleware('permission:access-stock-in')->name('scan.stock_in.store');
    Route::get('/scan/nglog', [ScanController::class, 'ngLog'])->middleware('permission:access-ng-log')->name('scan.nglog');
    Route::post('/scan/nglog/store', [ScanController::class, 'ngLogStore'])->middleware('permission:access-ng-log')->name('scan.nglog.store');
    Route::get('/scan/riwayat-scan', [ScanController::class, 'riwayatScan'])->name('scan.history');
});

// DASHBOARD SLIDE & COATING (Bebas / Public atau sesuaikan kebutuhan)
Route::get('/slide', function () {
    return view('dashboardslide', [
        'activeMenu' => 'Dashboard Slide',
    ]);
})->name('dashboardslide');

Route::get('/coating', function () {
    return view('/dashboard/coating', [
        'activeMenu' => 'Dashboard Coating',
    ]);
})->name('dashboard.coating');

Route::get('/sealing', [DashboardController::class, 'sealing'])->name('dashboard.sealing');
Route::get('/sealing/data', [DashboardController::class, 'sealingData'])->name('dashboard.sealing.data');

// Root URL ('/')
Route::get('/', function () {
    return view('dashboard', [
        'activeMenu' => 'Dashboard',
    ]);
});
