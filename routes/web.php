<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AcedemicSilabusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::get('bienvenido', [AuthController::class, 'home'])->name('auth.home');
Route::get('perfil', [AuthController::class, 'profile'])->name('auth.profile');
Route::put('perfil/{id}', [AuthController::class, 'storeProfile'])->name('auth.profile.store');

Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/getAll', [RoleController::class, 'getAll'])->name('roles.getall');
Route::get('/roles/get/{id}', [RoleController::class, 'get'])->name('roles.get');
Route::get('/roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
Route::post('/roles/store', [RoleController::class, 'create'])->name('roles.create');
Route::put('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/getAll', [UserController::class, 'getAll'])->name('users.getall');
Route::get('/users/get/{id}', [UserController::class, 'get'])->name('users.get');
Route::get('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
Route::post('/users/store', [UserController::class, 'create'])->name('users.create');
Route::post('/users/assign', [UserController::class, 'assign'])->name('users.assign');
Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

Route::get('/periods', [AcademicPeriodController::class, 'index'])->name('periods.index');
Route::post('/periods', [AcademicPeriodController::class, 'store'])->name('periods.store');

Route::get('/profesores', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/profesores/getAll', [TeacherController::class, 'getAll'])->name('teachers.getall');
Route::get('/profesores/get/{id}', [TeacherController::class, 'get'])->name('teachers.get');
Route::get('/profesores/delete/{id}', [TeacherController::class, 'delete'])->name('teachers.delete');
Route::post('/profesores/store', [TeacherController::class, 'create'])->name('teachers.create');
Route::put('/profesores/update/{id}', [TeacherController::class, 'update'])->name('teachers.update');

Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
Route::get('/estudiantes/getAll', [StudentController::class, 'getAll'])->name('students.getall');
Route::get('/estudiantes/get/{id}', [StudentController::class, 'get'])->name('students.get');
Route::get('/estudiantes/delete/{id}', [StudentController::class, 'delete'])->name('students.delete');
Route::post('/estudiantes/store', [StudentController::class, 'create'])->name('students.create');
Route::put('/estudiantes/update/{id}', [StudentController::class, 'update'])->name('students.update');

Route::get('/secretarias', [SecretaryController::class, 'index'])->name('secretaries.index');
Route::get('/secretarias/getAll', [SecretaryController::class, 'getAll'])->name('secretaries.getall');
Route::get('/secretarias/get/{id}', [SecretaryController::class, 'get'])->name('secretaries.get');
Route::get('/secretarias/delete/{id}', [SecretaryController::class, 'delete'])->name('secretaries.delete');
Route::post('/secretarias/store', [SecretaryController::class, 'create'])->name('secretaries.create');
Route::put('/secretarias/update/{id}', [SecretaryController::class, 'update'])->name('secretaries.update');

Route::group(['prefix' => '{id}/'], function () {
    Route::get('/inicio', [AcademicPeriodController::class, 'home'])->name('periods.home');
    Route::get('/calendario', [AcademicCalendarController::class, 'index'])->name('calendars.index');
    Route::post('/calendario', [AcademicCalendarController::class, 'store'])->name('calendars.store');
    Route::get('/silabus', [AcedemicSilabusController::class, 'index'])->name('silabus.index');
});

// Auth
Route::get('login', [AuthController::class, 'index'])->name('auth.login');
Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
Route::post('store', [AuthController::class, 'register'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
