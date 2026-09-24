<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterSealingController;

// 1. Route Tamu (Hanya untuk yang BELUM login)
Route::middleware(['guest.custom'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD
// Route::get('/dashboard', function () {
//     return view('dashboard', [
//         'activeMenu' => 'Dashboard'
//     ]);
// })->name('dashboard');

Route::get('/dashboard/slide', function () {
    return view('dashboardslide', [
        'activeMenu' => 'Dashboard Slide'
    ]);
})->name('dashboardslide');

// DASHBOARD/coating
Route::get('/dashboard/coating', function () {
    return view('/dashboard/coating', [
        'activeMenu' => 'Dashboard Coating'
    ]);
})->name('dashboard.coating');

// DASHBOARD/sealing
Route::get('/dashboard/sealing', [DashboardController::class, 'sealing'])->name('dashboard.sealing');

// 2. Route yang Membutuhkan Login (Custom Auth)
Route::middleware(['auth.custom'])->group(function () {
    // MASTER SEALING
    Route::prefix('master-sealing')->name('master.sealing.')->group(function () {
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
        Route::put('/bom/update/{id}', [MasterSealingController::class, 'updateBom'])->name('bom.update');
        Route::delete('/bom/delete/{id}', [MasterSealingController::class, 'destroyBom'])->name('bom.destroy');
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
});

// 3. Pengaturan Root URL ('/') langsung menampilkan view slide
Route::get('/', function () {
    return view('dashboard', [
        'activeMenu' => 'Dashboard'
    ]);
});
