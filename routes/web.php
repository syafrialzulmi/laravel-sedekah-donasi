<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DasborController;

use App\Http\Controllers\Manage\RoleController;
use App\Http\Controllers\Manage\UserController;
use App\Http\Controllers\Manage\MenuController;
use App\Http\Controllers\Manage\SettingAppController;

use App\Http\Controllers\ProgramSedekahController;

// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\CustomerController;

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
    Route::get('/lupa-password', [AuthController::class, 'showFormForgot'])->name('lupa-password');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    // Form reset password
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

    // Proses update password
    Route::post('/reset-password', [AuthController::class, 'reset'])
        ->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', [DasborController::class, 'index'])->name('dasbor');

    Route::prefix('admin/manage')->group(function () {
        Route::resource('/roles', RoleController::class);

        Route::get('/profilku', [UserController::class, 'showProfile'])->name('profilku');
        Route::post('/profilku/update', [UserController::class, 'updateProfile'])->name('profilku.update');
        Route::resource('/users', UserController::class);
        
        // Route::resource('/products', ProductController::class);
        Route::resource('/menus', MenuController::class);

        Route::get('/setting-app/desa-by-kecamatan/{kecamatan_id}', [SettingAppController::class, 'getDesaByKecamatan'])
            ->name('desa.by.kecamatan');

        Route::get('/setting-app', [SettingAppController::class, 'index'])->name('setting-app.index');
        Route::post('/setting-app', [SettingAppController::class, 'store'])->name('setting-app.store');
        Route::put('/setting-app/{settingApp}', [SettingAppController::class, 'update'])->name('setting-app.update');
        Route::delete('/setting-app', [SettingAppController::class, 'clear'])->name('setting-app.clear');

        // Route::resource('customers', CustomerController::class);
    });

    Route::prefix('admin/master')->group(function () {
        Route::resource('/program-sedekah', ProgramSedekahController::class);
    });

});
