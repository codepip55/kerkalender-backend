<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternalAuthController;

// Internal Auth
Route::get('/auth/kerkalender', [InternalAuthController::class, 'login']);
Route::get('/user/bearer', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// API Routes
