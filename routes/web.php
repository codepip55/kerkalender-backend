<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;

Route::get('/', [PagesController::class, 'getIndex'])->name('home');
Route::get('/profile', [PagesController::class, 'getProfile'])->middleware('auth', 'verified')->name('profile');

Route::get('/login', [PagesController::class, 'getLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [PagesController::class, 'getRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/change-password', [PagesController::class, 'getChangePassword'])->name('change-password');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
Route::get('/forgot-password', [PagesController::class, 'getForgotPassword'])->name('password.email');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/email/verify', [PagesController::class, 'getEmailVerify'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'sendEmailVerificationNotification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
