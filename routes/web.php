<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Pages (GET routes)
Route::middleware('guest')->group(function () {
    // All these routes show the same login/register form
    Route::get('/auth', function () {
        return view('auth.login-register');
    })->name('auth.login-register');
    
    Route::get('/login', function () {
        return view('auth.login-register');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.login-register');
    })->name('register');
});

// Authentication Actions (POST routes)
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

// Fallback for any auth routes
Route::fallback(function () {
    return redirect('/');
});