<?php

use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/signup', [SignupController::class, 'index'])
    ->name('signup');
Route::post('/signup', [SignupController::class, 'store'])
    ->name('signup.store');

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete("/categories/{category}", [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('product.create');
    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/edit/{product}', [ProductController::class, 'edit'])
        ->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.delete');
});
