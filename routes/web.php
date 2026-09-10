<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadLoanController;
use App\Http\Controllers\RecoveryStatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('bad-loans.index');
});

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Application Routes
Route::middleware(['auth'])->group(function () {
    // Bad Loans (Basic Information CRUD & Bulk Import)
    Route::get('bad-loans/template/download', [BadLoanController::class, 'downloadTemplate'])
        ->name('bad-loans.template.download');

    Route::post('bad-loans/import', [BadLoanController::class, 'import'])
        ->name('bad-loans.import');

    Route::resource('bad-loans', BadLoanController::class);

    // Central Recovery Office Exclusive Routes
    Route::middleware(['role:central'])->group(function () {
        // Recovery Modules Management
        Route::prefix('loans/{badLoan}/recovery')->as('recovery.')->group(function () {
            Route::get('/', [RecoveryStatusController::class, 'manage'])->name('manage');
            Route::post('/blacklist', [RecoveryStatusController::class, 'updateBlacklist'])->name('blacklist.update');
            Route::post('/auction', [RecoveryStatusController::class, 'updateAuction'])->name('auction.update');
            Route::post('/partial-release', [RecoveryStatusController::class, 'updatePartialRelease'])->name('partial-release.update');
            Route::post('/nba', [RecoveryStatusController::class, 'updateNba'])->name('nba.update');
            Route::post('/interest-rebate', [RecoveryStatusController::class, 'updateInterestRebate'])->name('interest-rebate.update');
            Route::post('/drt', [RecoveryStatusController::class, 'updateDrt'])->name('drt.update');
        });

        // User & Role Management
        Route::resource('users', UserController::class);

        // Branch Directory Management
        Route::resource('branches', BranchController::class)->except(['create', 'show']);
    });
});
