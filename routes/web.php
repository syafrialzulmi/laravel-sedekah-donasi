<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DasborController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SettingAppController;
use App\Http\Controllers\CustomerController;

use Mews\Captcha\Facades\Captcha;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/registrasi', [AuthController::class, 'showFormRegistrasi'])->name('registrasi');
    Route::post('/registrasi', [AuthController::class, 'submitFormRegistrasi'])->name('registrasi.submit');
    Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'submitFormLogin'])->name('login.submit');
    Route::get('reload-captcha', function () {
        return response()->json([
            'url' => captcha_src('flat'), // hanya URL gambar
        ]);
    });
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', [DasborController::class, 'index'])->name('dasbor');
    Route::resource('/admin/roles', RoleController::class);
    Route::resource('/admin/users', UserController::class);
    Route::resource('/admin/products', ProductController::class);
    Route::resource('/admin/menus', MenuController::class);

    Route::get('/setting-app', [SettingAppController::class, 'index'])->name('setting-app.index');
    Route::post('/setting-app', [SettingAppController::class, 'store'])->name('setting-app.store');
    Route::put('/setting-app/{settingApp}', [SettingAppController::class, 'update'])->name('setting-app.update');
    Route::delete('/setting-app', [SettingAppController::class, 'clear'])->name('setting-app.clear');

    Route::resource('customers', CustomerController::class);

});
