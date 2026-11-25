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

    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function() {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/users/{id}/ban', [\App\Http\Controllers\AdminController::class, 'ban'])->name('admin.users.ban');
        Route::post('/admin/users/{id}/unban', [\App\Http\Controllers\AdminController::class, 'unban'])->name('admin.users.unban');
        
        // Tags Management
        Route::get('/admin/tags', [\App\Http\Controllers\AdminTagController::class, 'index'])->name('admin.tags.index');
        Route::post('/admin/tags', [\App\Http\Controllers\AdminTagController::class, 'store'])->name('admin.tags.store');
        Route::put('/admin/tags/{id}', [\App\Http\Controllers\AdminTagController::class, 'update'])->name('admin.tags.update');
        Route::delete('/admin/tags/{id}', [\App\Http\Controllers\AdminTagController::class, 'destroy'])->name('admin.tags.destroy');
    });

    // Profile Routes
    Route::get('/profiles', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profiles.edit');
    Route::get('/profile/{id}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profiles.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profiles.update');
    Route::get('/profile/{id}/photo', [\App\Http\Controllers\ProfileController::class, 'photo'])->name('profiles.photo');

    // Matches
    Route::post('/profile/{id}/like', [\App\Http\Controllers\MatchController::class, 'like'])->name('profiles.like');
    Route::post('/match/{id}/unmatch', [\App\Http\Controllers\MatchController::class, 'unmatch'])->name('match.unmatch');

    // Chat / Inbox
    Route::get('/inbox', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user_id}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user_id}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
});

