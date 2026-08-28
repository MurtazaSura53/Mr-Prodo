<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Support\Facades\Route;

Route::get('/signup', [SignupController::class, 'index'])
    ->name('signup');
Route::post('/signup', [SignupController::class, 'store'])
    ->name('signup.store');
