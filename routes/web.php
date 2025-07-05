<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public routes for Google authentication
Route::get('/auth/google/redirect', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Set the root URL to the dashboard
    Route::get('/', [ProductController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('products', ProductController::class);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
