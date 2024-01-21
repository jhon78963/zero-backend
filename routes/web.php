<?php

use App\Http\Controllers\Academic\RoleController as AcademicRoleController;
use App\Http\Controllers\Academic\UserController as AcademicUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AcedemicSilabusController;
use App\Http\Controllers\AttendanceTeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolRegistrationController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkloadController;

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

// SIN PERIODO ACADEMICO

Route::get('/periods', [AcademicPeriodController::class, 'index'])->name('periods.index');
Route::post('/periods', [AcademicPeriodController::class, 'store'])->name('periods.store');
Route::get('/periodo-academico', [AcademicPeriodController::class, 'periodHome'])->name('periods.home.index');

Route::get('/calendario-academico', [AcademicCalendarController::class, 'index'])->name('calendars.home.index');
Route::post('/calendario', [AcademicCalendarController::class, 'store'])->name('calendars.store');
Route::put('/calendario-academico/{id}/actualizar', [AcademicCalendarController::class, 'update'])->name('calendars.update');
Route::delete('/calendario-academico/{id}/eliminar', [AcademicCalendarController::class, 'destroy'])->name('calendars.delete');

Route::get('/roles-academico', [RoleController::class, 'index'])->name('roles.home.index');
Route::get('/roles/getAll', [RoleController::class, 'getAll'])->name('roles.home.getall');
Route::get('/roles/get/{id}', [RoleController::class, 'get'])->name('roles.home.get');
Route::get('/roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.home.delete');
Route::post('/roles/store', [RoleController::class, 'create'])->name('roles.home.create');
Route::put('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.home.update');

Route::get('/users-academico', [UserController::class, 'index'])->name('users.home.index');
Route::get('/users/getAll', [UserController::class, 'getAll'])->name('users.home.getall');
Route::get('/users/get/{user_id}', [UserController::class, 'get'])->name('users.home.get');
Route::get('/users/delete/{user_id}', [UserController::class, 'delete'])->name('users.home.delete');
Route::post('/users/store', [UserController::class, 'create'])->name('users.home.create');
Route::post('/users/assign', [UserController::class, 'assign'])->name('users.home.assign');
Route::put('/users/update/{user_id}', [UserController::class, 'update'])->name('users.home.update');

// PERIODO ACADEMICO
Route::get('principal', [AuthController::class, 'homePrincipal'])->name('auth.home.principal');
Route::get('bienvenido', [AuthController::class, 'home'])->name('auth.home');
Route::get('perfil', [AuthController::class, 'profile'])->name('auth.profile');
Route::put('perfil/{id}', [AuthController::class, 'storeProfile'])->name('auth.profile.store');

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


Route::get('/grados/getAll', [GradeController::class, 'getAll'])->name('grades.getall');
Route::post('/grados/store', [GradeController::class, 'create'])->name('grades.create');

Route::get('/secciones/getAll', [SectionController::class, 'getAll'])->name('sections.getall');
Route::post('/secciones/store', [SectionController::class, 'create'])->name('sections.create');

Route::get('/aulas/getAll', [ClassRoomController::class, 'getAll'])->name('class-room.getall');
Route::get('/aulas/get/{grade_id}/{section_id}', [ClassRoomController::class, 'get'])->name('class-room.get');
Route::get('/aulas/delete/{id}', [ClassRoomController::class, 'delete'])->name('class-room.delete');
Route::post('/aulas/store', [ClassRoomController::class, 'create'])->name('class-room.create');
Route::put('/aulas/update/{grade_id}/{section_id}', [ClassRoomController::class, 'update'])->name('class-room.update');

Route::get('/cursos/getAll', [CourseController::class, 'getAll'])->name('courses.getall');
Route::get('/cursos/get/{id}', [CourseController::class, 'get'])->name('courses.get');
Route::get('/cursos/delete/{id}', [CourseController::class, 'delete'])->name('courses.delete');
Route::post('/cursos/assign', [CourseController::class, 'assign'])->name('courses.assign');
Route::post('/cursos/store', [CourseController::class, 'create'])->name('courses.create');
Route::put('/cursos/update/{id}', [CourseController::class, 'update'])->name('courses.update');

Route::get('carga-horario/getAll', [WorkloadController::class, 'getAll'])->name('workload.getall');
Route::post('save', [WorkloadController::class, 'saveSchedule'])->name('save-schedule');
Route::post('carga-horaria/assign/classroom', [WorkloadController::class, 'assignClassroomTeacher'])->name('workload.classroom.assign');
Route::post('carga-horaria/assign/course', [WorkloadController::class, 'assignCourseTeacher'])->name('workload.course.assign');


Route::get('/matriculas/getAll', [SchoolRegistrationController::class, 'getAll'])->name('school-registration.getall');
Route::post('/matriculas/store', [SchoolRegistrationController::class, 'create'])->name('school-registration.create');

Route::post('/tesoreria/store', [TreasuryController::class, 'store'])->name('treasuries.store');
Route::get('/tesoreria/cancel/{id}', [TreasuryController::class, 'cancel'])->name('treasuries.cancel');

Route::post('/desactivar-token/{fecha_id}', [AttendanceTeacherController::class, 'disableToken'])->name('attendance.disable');
Route::post('/activar-token', [AttendanceTeacherController::class, 'enableToken'])->name('attendance.enable');
Route::post('/marcar-asistencia/{fecha}', [AttendanceTeacherController::class, 'mark'])->name('attendance.mark');
Route::post('/marcar-asistencia/{fecha}/{student_id}', [AttendanceTeacherController::class, 'change'])->name('attendance.change');

Route::group(['prefix' => '{period_id}/'], function () {
    Route::get('/inicio', [AcademicPeriodController::class, 'home'])->name('periods.home');
    Route::get('/silabus', [AcedemicSilabusController::class, 'index'])->name('silabus.index');
    Route::get('/docente/silabus', [SyllabusController::class, 'index'])->name('teacher.silabus.index');
    Route::get('/aulas', [ClassRoomController::class, 'index'])->name('class-room.index');
    Route::get('/cursos', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/carga-horario', [WorkloadController::class, 'index'])->name('workload.index');
    Route::get('/carga-horario/docentes', [WorkloadController::class, 'teacher'])->name('workload.teacher');
    Route::get('/matriculas', [SchoolRegistrationController::class, 'index'])->name('school-registration.index');
    Route::get('/matriculas/registrar', [SchoolRegistrationController::class, 'register'])->name('school-registration.register');
    Route::get('/tesoreria', [TreasuryController::class, 'index'])->name('treasuries.index');
    Route::get('/tesoreria/registrar-pago', [TreasuryController::class, 'create'])->name('treasuries.create');
    Route::get('/horario/docente', [WorkloadController::class, 'scheduleTeacher'])->name('workload.schedule.teacher');
    Route::get('/horario/estudiante', [WorkloadController::class, 'scheduleStudent'])->name('workload.schedule.student');
    Route::get('/asistencia/docente', [AttendanceTeacherController::class, 'index'])->name('attendance.teacher.index');
    Route::get('/asistencia/docente/registrar', [AttendanceTeacherController::class, 'create'])->name('attendance.teacher.create');
    Route::get('/asistencia/estudiante/registrar/{fecha_id}', [AttendanceTeacherController::class, 'registerAttendance'])->name('attendance.student.create');

    //users
    Route::get('/users', [AcademicUserController::class, 'index'])->name('users.index');

    //roles
    Route::get('/roles', [AcademicRoleController::class, 'index'])->name('roles.index');
});

//users
Route::get('/academico/users/getAll', [AcademicUserController::class, 'getAll'])->name('users.getall');
Route::get('/academico/users/get/{user_id}', [AcademicUserController::class, 'get'])->name('users.get');
Route::get('/academico/users/delete/{user_id}', [AcademicUserController::class, 'delete'])->name('users.delete');
Route::post('/academico/users/store', [AcademicUserController::class, 'create'])->name('users.create');
Route::post('/academico/users/assign', [AcademicUserController::class, 'assign'])->name('users.assign');
Route::put('/academico/users/update/{user_id}', [AcademicUserController::class, 'update'])->name('users.update');

//roles
Route::get('/academico/roles/getAll', [AcademicRoleController::class, 'getAll'])->name('roles.getall');
Route::get('/academico/roles/get/{id}', [AcademicRoleController::class, 'get'])->name('roles.get');
Route::get('/academico/roles/delete/{id}', [AcademicRoleController::class, 'delete'])->name('roles.delete');
Route::post('/academico/roles/store', [AcademicRoleController::class, 'create'])->name('roles.create');
Route::put('/academico/roles/update/{id}', [AcademicRoleController::class, 'update'])->name('roles.update');

// Auth
Route::get('login', [AuthController::class, 'index'])->name('auth.login');
Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
Route::post('store', [AuthController::class, 'register'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
