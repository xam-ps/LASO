<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\StatementController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('statement')->name('statement.')->group(function () {
        Route::get('/', [StatementController::class, 'index'])->name('index');
        Route::get('/{year}', [StatementController::class, 'showStatement'])->name('year');
    });

    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/create', [RevenueController::class, 'create'])->name('create');
        Route::post('/', [RevenueController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RevenueController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RevenueController::class, 'update'])->name('update');
    });

    Route::prefix('expense')->name('expense.')->group(function () {
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ExpenseController::class, 'update'])->name('update');
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
