<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\LaporanDonasiController;
use App\Http\Controllers\Manage\BackupController;
use App\Http\Controllers\Manage\MenuController;
use App\Http\Controllers\Manage\RoleController;
use App\Http\Controllers\Manage\SettingAppController;
use App\Http\Controllers\Manage\UserController;
use App\Http\Controllers\Manage\WaTemplateController;
use App\Http\Controllers\ProgramSedekahController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin', [DasborController::class, 'index'])->name('dasbor');

    Route::prefix('admin/manage')->group(function () {
        Route::resource('/roles', RoleController::class);

        Route::get('/profilku', [UserController::class, 'showProfile'])->name('profilku');
        Route::post('/profilku/update', [UserController::class, 'updateProfile'])->name('profilku.update');
        Route::resource('/users', UserController::class);

        Route::resource('/menus', MenuController::class);

        Route::get('/setting-app/desa-by-kecamatan/{kecamatan_id}', [SettingAppController::class, 'getDesaByKecamatan'])
            ->name('desa.by.kecamatan');

        Route::get('/setting-app', [SettingAppController::class, 'index'])->name('setting-app.index');
        Route::post('/setting-app', [SettingAppController::class, 'store'])->name('setting-app.store');
        Route::put('/setting-app/{settingApp}', [SettingAppController::class, 'update'])->name('setting-app.update');
        Route::delete('/setting-app', [SettingAppController::class, 'clear'])->name('setting-app.clear');

        Route::resource('/wa-template', WaTemplateController::class);

        Route::post('/backup/database', [BackupController::class, 'database'])
            ->name('backup.database');
    });

    Route::prefix('admin/master')->group(function () {
        Route::resource('/program-sedekah', ProgramSedekahController::class);

        Route::get('/donatur/generate-kode', [DonaturController::class, 'generateKode'])
            ->name('donatur.generate-kode');
        Route::get('/donatur/cari', [DonaturController::class, 'cariByKode'])
            ->name('donatur.cari');
        Route::resource('/donatur', DonaturController::class);
    });

    Route::prefix('admin/transaksi')->group(function () {

        Route::post('/donasi/{id}/wa-terkirim', [DonasiController::class, 'waTerkirim'])
            ->name('donasi.wa-terkirim');
        Route::post('/donasi/{donasi}/kirim-wa', [DonasiController::class, 'kirimWa'])
            ->name('donasi.kirim-wa');
        Route::resource('/donasi', DonasiController::class);

        Route::resource('/laporan-donasi', LaporanDonasiController::class);
    });

});
