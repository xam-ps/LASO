<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevenueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });
    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/create', [RevenueController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [RevenueController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RevenueController::class, 'update'])->name('update');
        Route::post('/', [RevenueController::class, 'store'])->name('store');
    });
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
