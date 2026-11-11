<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSealingController;
use App\Http\Controllers\SettingSealingController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SealingPlanController;
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

    // Manajemen User — GET (view-only) vs POST/PUT/DELETE (full access)
    Route::get('/manajemen-user', [AuthController::class, 'indexManajemenUser'])->middleware('permission:view-manajemen-user')->name('manajemen.user');
    Route::post('/manajemen-user/register', [AuthController::class, 'register'])->middleware('permission:access-manajemen-user')->name('register.process');
    Route::put('/manajemen-user/{id}', [AuthController::class, 'updateUser'])->middleware('permission:access-manajemen-user')->name('users.update');
    Route::delete('/manajemen-user/{id}', [AuthController::class, 'deleteUser'])->middleware('permission:access-manajemen-user')->name('users.delete');

        // Histori Session / Riwayat Login-Logout (Khusus Admin)
        Route::get('/histori-session', [AuthController::class, 'historiSession'])->name('session.history');

        // Operator / Manajemen Operator (Khusus Admin)
        Route::get('/operator', [AuthController::class, 'indexOperator'])->name('operator.index');
        Route::post('/operator/store', [AuthController::class, 'storeOperator'])->name('operator.store');
        Route::put('/operator/{id}', [AuthController::class, 'updateOperator'])->name('operator.update');
        Route::delete('/operator/{id}', [AuthController::class, 'deleteOperator'])->name('operator.destroy');
    });
    
    // MASTER SEALING — GET (view-only) vs POST/PUT/DELETE (full access)
    Route::prefix('master-sealing')->name('master.sealing.')->group(function () {
        // View-only routes
        Route::get('/', [MasterSealingController::class, 'index'])->middleware('permission:view-master-part')->name('index');
        // Write routes (full access)
        Route::post('/fg/store', [MasterSealingController::class, 'storeFg'])->middleware('permission:access-master-part')->name('fg.store');
        Route::put('/fg/update/{id}', [MasterSealingController::class, 'updateFg'])->middleware('permission:access-master-part')->name('fg.update');
        Route::delete('/fg/delete/{id}', [MasterSealingController::class, 'destroyFg'])->middleware('permission:access-master-part')->name('fg.destroy');
        Route::post('/cp/store', [MasterSealingController::class, 'storeCp'])->middleware('permission:access-master-part')->name('cp.store');
        Route::put('/cp/update/{id}', [MasterSealingController::class, 'updateCp'])->middleware('permission:access-master-part')->name('cp.update');
        Route::delete('/cp/delete/{id}', [MasterSealingController::class, 'destroyCp'])->middleware('permission:access-master-part')->name('cp.destroy');
        Route::post('/bom/store', [MasterSealingController::class, 'storeBom'])->middleware('permission:access-master-part')->name('bom.store');
        Route::put('/bom/update/{barcode}/{childPart}', [MasterSealingController::class, 'updateBom'])->where(['barcode' => '[^/]+', 'childPart' => '[^/]+'])->middleware('permission:access-master-part')->name('bom.update');
        Route::delete('/bom/delete/{barcode}/{childPart}', [MasterSealingController::class, 'destroyBom'])->where(['barcode' => '[^/]+', 'childPart' => '[^/]+'])->middleware('permission:access-master-part')->name('bom.destroy');
    });
// SEALING SETTING — GET (view-only) vs PUT (full access)
    Route::get('/sealing-setting', [SettingSealingController::class, 'index'])
        ->middleware('permission:view-sealing-setting')
        ->name('sealing.setting');

    Route::put('/sealing-setting', [SettingSealingController::class, 'update'])
        ->middleware('permission:access-sealing-setting')
        ->name('sealing.setting.update');

// SEALING PLAN — GET (view-only) vs POST (full access)
    Route::prefix('sealing-plan')->name('sealing.plan.')->group(function () {
        Route::get('/', [SealingPlanController::class, 'index'])->middleware('permission:view-sealing-plan')->name('index');
        Route::post('/', [SealingPlanController::class, 'store'])->middleware('permission:access-sealing-plan')->name('store');
        Route::post('/update/{id}', [SealingPlanController::class, 'update'])->middleware('permission:access-sealing-plan')->name('update');
    });

    // SCAN/console — GET (view-only) vs POST (full access)
    Route::get('/scan/console', [ScanController::class, 'console'])->middleware('permission:view-scan-console')->name('scan.console');
    Route::post('/scan/store', [ScanController::class, 'consolestore'])->middleware('permission:access-scan-console')->name('scan.store');
    Route::get('/scan/stock-card', [ScanController::class, 'stockCard'])->middleware('permission:view-stock-card')->name('scan.stock_card');
    Route::post('/scan/stock-adjustment/store', [ScanController::class, 'adjustmentStore'])->middleware('permission:access-stock-adjustment')->name('scan.stock_adjustment.store');
    Route::get('/scan/stock-in', [ScanController::class, 'stockInForm'])->middleware('permission:view-stock-in')->name('scan.stock_in.form');
    Route::post('/scan/stock-in', [ScanController::class, 'stockInStore'])->middleware('permission:access-stock-in')->name('scan.stock_in.store');
    Route::get('/scan/nglog', [ScanController::class, 'ngLog'])->middleware('permission:view-ng-log')->name('scan.nglog');
    Route::post('/scan/nglog/store', [ScanController::class, 'ngLogStore'])->middleware('permission:access-ng-log')->name('scan.nglog.store');
    Route::get('/scan/riwayat-scan', [ScanController::class, 'riwayatScan'])->name('scan.history');

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
Route::get('/sealing/chart-data', [DashboardController::class, 'sealingChartData'])->name('dashboard.sealing.chart.data');

// Root URL ('/')
Route::get('/', function () {
    return view('dashboard', [
        'activeMenu' => 'Dashboard',
    ]);
});
