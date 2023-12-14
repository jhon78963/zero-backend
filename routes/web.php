<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfessorController;
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
    Route::get('perfil', [AuthController::class, 'profile'])->name('admin.auth.profile');
    Route::put('perfil/{id}', [AuthController::class, 'storeProfile'])->name('admin.auth.profile.store');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('check.permissions:Admin,pages.role')->name('admin.roles.index');
    Route::get('/roles/getAll', [RoleController::class, 'getAll'])->middleware('check.permissions:Admin,pages.role')->name('admin.roles.getall');
    Route::get('/roles/get/{id}', [RoleController::class, 'get'])->middleware('check.permissions:Admin,pages.role')->name('admin.roles.get');
    Route::put('/roles/update/{id}', [RoleController::class, 'update'])->middleware('check.permissions:Admin,pages.role.modify')->name('admin.roles.update');
    Route::get('/roles/delete/{id}', [RoleController::class, 'delete'])->middleware('check.permissions:Admin,pages.role.delete')->name('admin.roles.delete');
    Route::post('/roles/store', [RoleController::class, 'create'])->middleware('check.permissions:Admin,pages.role.modify')->name('admin.roles.create');

    Route::get('/users', [UserController::class, 'index'])->middleware('check.permissions:Admin,pages.user')->name('admin.users.index');
    Route::get('/users/getAll', [UserController::class, 'getAll'])->middleware('check.permissions:Admin,pages.user')->name('admin.users.getall');
    Route::get('/users/get/{id}', [UserController::class, 'get'])->middleware('check.permissions:Admin,pages.user')->name('admin.users.get');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->middleware('check.permissions:Admin,pages.user.modify')->name('admin.users.update');
    Route::get('/users/delete/{id}', [UserController::class, 'delete'])->middleware('check.permissions:Admin,pages.user.delete')->name('admin.users.delete');
    Route::post('/users/store', [UserController::class, 'create'])->middleware('check.permissions:Admin,pages.user.modify')->name('admin.users.create');
    Route::post('/users/assign', [UserController::class, 'assign'])->middleware('check.permissions:Admin,pages.user.assign')->name('admin.users.assign');

    Route::get('/periods', [AcademicPeriodController::class, 'index'])->middleware('check.permissions:Admin,pages.period')->name('admin.periods.index');
    Route::post('/periods', [AcademicPeriodController::class, 'store'])->middleware('check.permissions:Admin,pages.period.modify')->name('admin.periods.store');
});

Route::group(['prefix' => '{id}/admin'], function () {
    Route::get('/inicio', [AcademicPeriodController::class, 'home'])->middleware('check.permissions:Admin,pages.period')->name('admin.periods.home');
    Route::get('/calendario', [AcademicCalendarController::class, 'index'])->middleware('check.permissions:Admin,pages.period.modify')->name('admin.calendars.index');
    Route::post('/calendario', [AcademicCalendarController::class, 'store'])->middleware('check.permissions:Admin,pages.period.modify')->name('admin.calendars.store');

    Route::get('/docente', [TeacherController::class, 'index'])->middleware('check.permissions:Admin,pages.teacher')->name('admin.teachers.index');
});

Route::group(['prefix' => 'invitado'], function () {
    Route::get('bienvenido', [AuthController::class, 'home'])->name('guest.auth.home');
    Route::get('perfil', [AuthController::class, 'profile'])->name('guest.auth.profile');
    Route::get('roles', [RoleController::class, 'index'])->name('guest.roles.index');
    Route::get('users', [UserController::class, 'index'])->name('guest.users.index');
});

// Auth
Route::get('login', [AuthController::class, 'index'])->name('auth.login');
Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
Route::post('store', [AuthController::class, 'register'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
