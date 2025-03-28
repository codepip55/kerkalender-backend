<?php

use App\Http\Controllers\SetlistController;
use App\Http\Controllers\UserController;
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
// Services
Route::get('/services', [ServiceController::class, 'findServices'])->middleware('auth:jwt-custom');
Route::get('/services/{service_id}', [ServiceController::class, 'findServiceById'])->middleware('auth:jwt-custom');
Route::post('/services', [ServiceController::class, 'createService'])->middleware('auth:jwt-custom');
Route::put('/services/{service_id}', [ServiceController::class, 'updateService'])->middleware('auth:jwt-custom');
Route::delete('/services/{service_id}', [ServiceController::class, 'deleteService'])->middleware('auth:jwt-custom');

// Requests
Route::get('/requests/{user_id}', [ServiceController::class, 'findUserRequests'])->middleware('auth:jwt-custom');
Route::post('/requests', [ServiceController::class, 'updateStatus'])->middleware('auth:jwt-custom');

// Setlist
Route::get('/setlists/{setlist_id}', [SetlistController::class, 'findSetlistById'])->middleware('auth:jwt-custom');
Route::get('/setlists/service/{service_id}', [SetlistController::class, 'findSetlistByServiceId'])->middleware('auth:jwt-custom');
Route::put('/setlists/{setlist_id}', [SetlistController::class, 'updateSetlistById'])->middleware('auth:jwt-custom');

// Users
Route::get('/users', [UserController::class, 'findUsers'])->middleware('auth:jwt-custom');
