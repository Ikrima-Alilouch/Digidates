<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
    Route::post('/reset-password/{token}', [AuthController::class, 'handleResetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Profile Routes
    Route::get('/profiles', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profiles.update');
    Route::get('/profile/{id}/photo', [\App\Http\Controllers\ProfileController::class, 'photo'])->name('profiles.photo');
});

