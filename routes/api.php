<?php

use App\Http\Controllers\Api\AttendanceAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherCompetenciaController;
use App\Http\Controllers\TreasuryController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

// Users
Route::get('/users/get/{id}', [UserController::class, 'get']);
Route::get('/users/getAll', [UserController::class, 'getAll']);
Route::get('/users/getRoles/{id}', [UserController::class, 'getRolesc']);
Route::post('/users/create', [UserController::class, 'create']);
Route::post('/users/assign', [UserController::class, 'assign']);
Route::post('/users/unassign', [UserController::class, 'unassign']);
Route::put('/users/update/{id}', [UserController::class, 'update']);
Route::patch('/users/delete/{id}', [UserController::class, 'delete']);

// Roles
Route::get('/roles/get/{id}', [RoleController::class, 'get']);
Route::get('/roles/getAll', [RoleController::class, 'getAll']);
Route::post('/roles/create', [RoleController::class, 'create']);
Route::put('/roles/update/{id}', [RoleController::class, 'update']);
Route::patch('/roles/delete/{id}', [RoleController::class, 'delete']);
Route::delete('/roles/revoke/{id}', [RoleController::class, 'revoke']);

Route::get('role/permissions', function () {
    $roles = Role::where('IsDeleted', false)->get();
    $permissions = Permission::distinct('name')->whereNotIn('id', $roles->pluck('name'))->get(['name']);
    return $permissions;
});

Route::get('grade/{id}/section/{period_id}', function ($grade_id, $period_id) {
    $sections = DB::table('class_rooms as cr')
        ->join('sections as s', 's.id', 'cr.section_id')
        ->where('grade_id', $grade_id)
        ->where('s.TenantId', $period_id)
        ->where('s.IsDeleted', false)
        ->select('s.id', 's.description')
        ->get();
    return $sections;
});

Route::get('class-room/{grade_id}/{section_id}/{period_id}', function ($grade_id, $section_id, $period_id) {
    $aulas = DB::table('class_rooms')
        ->where('grade_id', $grade_id)
        ->where('section_id', $section_id)
        ->where('TenantId', $period_id)
        ->where('IsDeleted', false)
        ->select('id', 'limit', 'students_number')
        ->first();
    return $aulas;
});

Route::get('/consulta-dni/{cust_dni}', [App\Http\Controllers\ConsultaController::class, 'consultaDNI']);
Route::get('/consulta-ruc/{cust_ruc}', [App\Http\Controllers\ConsultaController::class, 'consultaRUC']);

Route::get('/{period_id}/payments/{student_id}', [TreasuryController::class, 'getPaymentByStudent']);

//students
Route::post('students', [StudentController::class, 'create']);
Route::delete('students/{id}', [StudentController::class, 'delete']);
Route::get('students/search/{id}', [StudentController::class, 'search']);
Route::get('students/{id}', [StudentController::class, 'get']);
Route::get('students', [StudentController::class, 'getAll']);
Route::put('students/{id}', [StudentController::class, 'update']);

//Notas docente
Route::get('/notas/docente/{classroom_id}', [TeacherCompetenciaController::class, 'index']);
Route::get('/notas/docente/{classroom_id}/estudiante/registrar/{student_id}', [TeacherCompetenciaController::class, 'create']);
Route::get('/notas/docente/{classroom_id}/estudiante/registrar/{student_id}/next', [TeacherCompetenciaController::class, 'createNext']);
Route::get('/notas/docente/{classroom_id}/estudiante/registrar/{student_id}/previous', [TeacherCompetenciaController::class, 'createPrevious']);
Route::get('/notas/report/estudiante/{student_id}', [TeacherCompetenciaController::class, 'generatePDF']);
Route::post('/notas/docente/{classroom_id}/guardar/{student_id}', [TeacherCompetenciaController::class, 'store']);

// Asistencia admin - secretaria
Route::get('/asistencia/faltas/{classroom_id}', [AttendanceAdminController::class, 'showMissing']);
Route::get('/asistencia/{classroom_id}/{date_id}', [AttendanceAdminController::class, 'index']);
Route::get('/reporte-asistencia/admin/{classroom_id}/estudiante/{student_id}', [AttendanceAdminController::class, 'generatePDF']);
