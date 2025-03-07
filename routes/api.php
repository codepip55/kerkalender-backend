<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternalAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;

// Internal Auth
Route::get('/auth/kerkalender', [InternalAuthController::class, 'login'])->middleware('web');
Route::get('/auth/silent', [InternalAuthController::class, 'silentAuth'])->middleware('web');
Route::get('/auth/logout', [AuthController::class, 'logoutApi']);

Route::get('/user/bearer', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// API Routes
Route::post('/services', [ServiceController::class, 'createService'])->middleware('auth:api');
