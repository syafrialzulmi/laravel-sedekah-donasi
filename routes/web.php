<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DasborController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/registrasi', [AuthController::class, 'showFormRegistrasi'])->name('registrasi');
    Route::post('/registrasi', [AuthController::class, 'submitFormRegistrasi'])->name('registrasi.submit');
    Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'submitFormLogin'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', [DasborController::class, 'index'])->name('dasbor');
    Route::resource('/admin/roles', RoleController::class);
    Route::resource('/admin/users', UserController::class);
    Route::resource('/admin/products', ProductController::class);
    Route::resource('/admin/menus', MenuController::class);
});
