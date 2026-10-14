<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes for Foodie Backend
|--------------------------------------------------------------------------
*/

// Home redirects to Admin Dashboard
Route::get('/', function () {
    return redirect()->route('admin.orders.index');
});

// Default 'login' route alias for Laravel auth middleware
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');

// Struk Pembayaran Cetak / PDF Download (Fit to Content)
Route::get('/orders/{orderId}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Authenticated Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.orders.index');
        })->name('admin.dashboard');

        // 1. Orders Management (Dipesan, Diproses, Diantar, Selesai)
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{id}/mark-paid', [AdminOrderController::class, 'markPaid'])->name('admin.orders.mark-paid');
        Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');

        // 2. Menu Management (Food catalog CRUD)
        Route::get('/menu', [AdminMenuController::class, 'index'])->name('admin.menu.index');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('admin.menu.store');
        Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('admin.menu.destroy');
        Route::post('/menu/{id}/toggle-popular', [AdminMenuController::class, 'togglePopular'])->name('admin.menu.toggle-popular');

        // 3. Voucher Management (Voucher discount CRUD)
        Route::get('/vouchers', [AdminVoucherController::class, 'index'])->name('admin.vouchers.index');
        Route::post('/vouchers', [AdminVoucherController::class, 'store'])->name('admin.vouchers.store');
        Route::put('/vouchers/{id}', [AdminVoucherController::class, 'update'])->name('admin.vouchers.update');
        Route::delete('/vouchers/{id}', [AdminVoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
        Route::post('/vouchers/{id}/toggle-active', [AdminVoucherController::class, 'toggleActive'])->name('admin.vouchers.toggle-active');

        // 4. Sales Reporting (Omset, statistik, CSV export)
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('admin.reports.export');
    });
});
