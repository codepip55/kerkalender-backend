<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternalAuthController;

Route::prefix('api')->group(function () {
    // Internal Auth
    Route::middleware('auth:api')->get('/auth/kerkalender', [InternalAuthController::class, 'getUserFromLogin']);
    Route::post('/auth/silent', [InternalAuthController::class, 'refresh']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
