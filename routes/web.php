<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('bienvenido', [AuthController::class, 'home'])->name('admin.auth.home');

    Route::get('roles', [RoleController::class, 'index'])->middleware('check.permissions:Admin,pages.role')->name('admin.roles.index');
    Route::get('roles/getAll',[RoleController::class,'getAll'])->middleware('check.permissions:Admin,pages.role')->name('admin.roles.getall');

    Route::get('users', [UserController::class, 'index'])->middleware('check.permissions:Admin,pages.user')->name('admin.users.index');
    Route::get('/users/getAll',[UserController::class,'getAll'])->middleware('check.permissions:Admin,pages.user')->name('admin.users.getall');
});

Route::group(['prefix' => 'invitado'], function () {
    Route::get('bienvenido', [AuthController::class, 'home'])->name('guest.auth.home');

    Route::get('roles', [RoleController::class, 'index'])->middleware('check.permissions:Guest,pages.role')->name('guest.roles.index');
    Route::get('users', [UserController::class, 'index'])->middleware('check.permissions:Guest,pages.user')->name('guest.users.index');
});

// Auth
Route::get('login', [AuthController::class, 'index'])->name('auth.login');
Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
Route::post('store', [AuthController::class, 'register'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
