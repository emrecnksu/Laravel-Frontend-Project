<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/login', [UserController::class, 'loginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/register', [UserController::class, 'signup'])->name('register');
Route::post('/register', [UserController::class, 'register']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/index', [ProductController::class, 'index']);

Route::get('/products', [ProductController::class, 'index'])->name('index');
Route::get('/products/create', [ProductController::class, 'create'])->name('create');
Route::post('/products', [ProductController::class, 'store'])->name('store');
Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('show');
Route::get('/products/update/{id}', [ProductController::class, 'edit'])->name('edit');
Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('update');
Route::post('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('destroy');