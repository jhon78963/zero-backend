<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout']);
Route::post('/refresh',[AuthController::class,'refresh']);

// Users
Route::get('/users/get/{id}',[UserController::class,'get']);
Route::get('/users/getAll',[UserController::class,'getAll']);
Route::get('/users/getRoles/{id}',[UserController::class,'getRolesc']);
Route::post('/users/create',[UserController::class,'create']);
Route::post('/users/assign',[UserController::class,'assign']);
Route::post('/users/unassign',[UserController::class,'unassign']);
Route::put('/users/update/{id}',[UserController::class,'update']);
Route::patch('/users/delete/{id}',[UserController::class,'delete']);

// Roles
Route::get('/roles/get/{id}',[RoleController::class,'get']);
Route::get('/roles/getAll',[RoleController::class,'getAll']);
Route::post('/roles/create',[RoleController::class,'create']);
Route::put('/roles/update/{id}',[RoleController::class,'update']);
Route::patch('/roles/delete/{id}',[RoleController::class,'delete']);
Route::delete('/roles/revoke/{id}',[RoleController::class,'revoke']);
