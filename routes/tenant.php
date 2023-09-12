<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });

    // Auth
    Route::get('login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
    Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
    Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
    Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
    Route::post('store', [AuthController::class, 'register'])->name('auth.store');
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
});