<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'login.validation']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware(['guest'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware(['guest']);

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->middleware(['guest'])->name('password.email');

Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware(['guest'])->name('password.reset');

Route::post('/password/reset', [AuthController::class, 'reset'])->middleware(['guest'])->name('password.update');
