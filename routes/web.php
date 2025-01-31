<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;

Route::get('/login', [PagesController::class, 'getLogin'])->name('login');
