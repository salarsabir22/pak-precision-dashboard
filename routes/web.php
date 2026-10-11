<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/inbox', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/inbox/{submission}/files/{attachment}', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/inbox/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('superadmin')->group(function () {
        Route::get('/inbox-new', [SubmissionController::class, 'create'])->name('submissions.create');
        Route::post('/inbox', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::patch('/inbox/{submission}', [SubmissionController::class, 'update'])->name('submissions.update');
        Route::delete('/inbox/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');

        Route::get('/accounts', [UserController::class, 'index'])->name('users.index');
        Route::post('/accounts', [UserController::class, 'store'])->name('users.store');
        Route::delete('/accounts/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
