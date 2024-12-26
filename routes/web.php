<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TravelAllowanceController;
use App\Http\Controllers\VatNoticeController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('statement')->name('statement.')->group(function () {
        Route::get('/', [StatementController::class, 'index'])->name('index');
        Route::get('/{year}', [StatementController::class, 'index'])->name('year');
    });

    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/create', [RevenueController::class, 'create'])->name('create');
        Route::post('/', [RevenueController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RevenueController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RevenueController::class, 'update'])->name('update');
        Route::delete('/{id}', [RevenueController::class, 'destroy'])->name('delete');
    });

    Route::prefix('expense')->name('expense.')->group(function () {
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExpenseController::class, 'destroy'])->name('delete');
    });

    Route::prefix('vat-notice')->name('vat-notice.')->group(function () {
        Route::get('/', [VatNoticeController::class, 'index'])->name('index');
        Route::get('/create', [VatNoticeController::class, 'create'])->name('create');
        Route::get('/create/{year}', [VatNoticeController::class, 'create'])->name('createYear');
        Route::get('/{year}', [VatNoticeController::class, 'index'])->name('year');
        Route::post('/', [VatNoticeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VatNoticeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VatNoticeController::class, 'update'])->name('update');
        Route::delete('/{id}', [VatNoticeController::class, 'destroy'])->name('delete');
    });

    Route::prefix('travel-allowance')->name('travel-allowance.')->group(function () {
        Route::get('/', [TravelAllowanceController::class, 'index'])->name('index');
        Route::get('/all/{year}', [TravelAllowanceController::class, 'index'])->name('year');
        Route::get('/create', [TravelAllowanceController::class, 'create'])->name('create');
        Route::post('/', [TravelAllowanceController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TravelAllowanceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TravelAllowanceController::class, 'update'])->name('update');
        Route::delete('/{id}', [TravelAllowanceController::class, 'destroy'])->name('delete');
    });

    Route::prefix('asset')->name('asset.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/{year}', [DashboardController::class, 'index'])->name('year');
    });
});
