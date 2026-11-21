<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;

// หน้าแรก -> Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Login & Register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'checkLogin'])->name('login.check');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ต้อง login ก่อนถึงเข้าได้
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);

    Route::get('/products-history', [PurchaseHistoryController::class, 'index'])->name('products.history');

    Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');

    Route::get('/reports/best-sellers', [ReportController::class, 'bestSellersReport'])->name('reports.best_sellers');

    Route::get('/reports/low-stock', [ReportController::class, 'lowStockReport'])->name('reports.low_stock');

    Route::resource('customers', CustomerController::class);

    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
});

// สำหรับผู้จัดการ
Route::middleware(['auth', 'role:manager'])->group(function() {
    Route::resource('employees', EmployeeController::class);
});

// ✅ เส้นทางสำหรับการขาย
Route::get('/sales/receipt/{id}', [SaleController::class, 'receipt'])->name('sales.receipt');
Route::post('/save-sale', [SaleController::class, 'store'])->name('sales.store');

