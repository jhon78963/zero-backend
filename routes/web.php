<?php

use App\Http\Controllers\Academic\RoleController as AcademicRoleController;
use App\Http\Controllers\Academic\UserController as AcademicUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AcedemicSilabusController;
use App\Http\Controllers\AdminCompetenciaController;
use App\Http\Controllers\AttendanceAdminController;
use App\Http\Controllers\AttendanceStudentController;
use App\Http\Controllers\AttendanceTeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolRegistrationController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentCompetenciaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TeacherCompetenciaController;
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
Route::get('/periodo-academico', [AcademicPeriodController::class, 'periodHome'])->name('periods.home.index');
Route::post('/periods', [AcademicPeriodController::class, 'store'])->name('periods.store');

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

Route::get('principal', [AuthController::class, 'homePrincipal'])->name('auth.home.principal');
Route::get('perfil', [AuthController::class, 'profile'])->name('auth.profile');
Route::put('perfil/{id}', [AuthController::class, 'storeProfile'])->name('auth.profile.store');
// SIN PERIODO ACADEMICO


Route::group(['prefix' => '{period_id}/'], function () {

    // Home
    Route::get('/inicio', [AcademicPeriodController::class, 'home'])->name('periods.home');
    Route::get('bienvenido', [AuthController::class, 'home'])->name('auth.home');

    // Users
    Route::get('/users', [AcademicUserController::class, 'index'])->name('users.index');
    Route::get('/academico/users/getAll', [AcademicUserController::class, 'getAll'])->name('users.getall');
    Route::get('/academico/users/get/{user_id}', [AcademicUserController::class, 'get'])->name('users.get');
    Route::get('/academico/users/delete/{user_id}', [AcademicUserController::class, 'delete'])->name('users.delete');
    Route::post('/academico/users/store', [AcademicUserController::class, 'create'])->name('users.create');
    Route::post('/academico/users/assign', [AcademicUserController::class, 'assign'])->name('users.assign');
    Route::put('/academico/users/update/{user_id}', [AcademicUserController::class, 'update'])->name('users.update');

    // Roles
    Route::get('/roles', [AcademicRoleController::class, 'index'])->name('roles.index');
    Route::get('/academico/roles/getAll', [AcademicRoleController::class, 'getAll'])->name('roles.getall');
    Route::get('/academico/roles/get/{id}', [AcademicRoleController::class, 'get'])->name('roles.get');
    Route::get('/academico/roles/delete/{id}', [AcademicRoleController::class, 'delete'])->name('roles.delete');
    Route::post('/academico/roles/store', [AcademicRoleController::class, 'create'])->name('roles.create');
    Route::put('/academico/roles/update/{id}', [AcademicRoleController::class, 'update'])->name('roles.update');

    // Estudiantes
    Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
    Route::get('/estudiantes/getAll', [StudentController::class, 'getAll'])->name('students.getall');
    Route::get('/estudiantes/get/{id}', [StudentController::class, 'get'])->name('students.get');
    Route::get('/estudiantes/delete/{id}', [StudentController::class, 'delete'])->name('students.delete');
    Route::post('/estudiantes/store', [StudentController::class, 'create'])->name('students.create');
    Route::put('/estudiantes/update/{id}', [StudentController::class, 'update'])->name('students.update');

    // Profesores
    Route::get('/profesores', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/profesores/getAll', [TeacherController::class, 'getAll'])->name('teachers.getall');
    Route::get('/profesores/get/{id}', [TeacherController::class, 'get'])->name('teachers.get');
    Route::get('/profesores/delete/{id}', [TeacherController::class, 'delete'])->name('teachers.delete');
    Route::post('/profesores/store', [TeacherController::class, 'create'])->name('teachers.create');
    Route::put('/profesores/update/{id}', [TeacherController::class, 'update'])->name('teachers.update');

    // Secretarias
    Route::get('/secretarias', [SecretaryController::class, 'index'])->name('secretaries.index');
    Route::get('/secretarias/getAll', [SecretaryController::class, 'getAll'])->name('secretaries.getall');
    Route::get('/secretarias/get/{id}', [SecretaryController::class, 'get'])->name('secretaries.get');
    Route::get('/secretarias/delete/{id}', [SecretaryController::class, 'delete'])->name('secretaries.delete');
    Route::post('/secretarias/store', [SecretaryController::class, 'create'])->name('secretaries.create');
    Route::put('/secretarias/update/{id}', [SecretaryController::class, 'update'])->name('secretaries.update');

    // Grados
    Route::get('/grados/getAll', [GradeController::class, 'getAll'])->name('grades.getall');
    Route::post('/grados/store', [GradeController::class, 'create'])->name('grades.create');

    // Secciones
    Route::get('/secciones/getAll', [SectionController::class, 'getAll'])->name('sections.getall');
    Route::post('/secciones/store', [SectionController::class, 'create'])->name('sections.create');

    // Aulas
    Route::get('/aulas', [ClassRoomController::class, 'index'])->name('class-room.index');
    Route::get('/aulas/getAll', [ClassRoomController::class, 'getAll'])->name('class-room.getall');
    Route::get('/aulas/get/{grade_id}/{section_id}', [ClassRoomController::class, 'get'])->name('class-room.get');
    Route::get('/aulas/delete/{id}', [ClassRoomController::class, 'delete'])->name('class-room.delete');
    Route::post('/aulas/store', [ClassRoomController::class, 'create'])->name('class-room.create');
    Route::put('/aulas/update/{grade_id}/{section_id}', [ClassRoomController::class, 'update'])->name('class-room.update');

    // Cursos
    Route::get('/cursos', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/cursos/getAll', [CourseController::class, 'getAll'])->name('courses.getall');
    Route::get('/cursos/get/{id}', [CourseController::class, 'get'])->name('courses.get');
    Route::get('/cursos/delete/{id}', [CourseController::class, 'delete'])->name('courses.delete');
    Route::post('/cursos/assign', [CourseController::class, 'assign'])->name('courses.assign');
    Route::post('/cursos/store', [CourseController::class, 'create'])->name('courses.create');
    Route::put('/cursos/update/{id}', [CourseController::class, 'update'])->name('courses.update');

    // Carga horaria
    Route::get('/carga-horaria', [WorkloadController::class, 'index'])->name('workload.index');
    Route::get('/carga-horaria/docentes', [WorkloadController::class, 'teacher'])->name('workload.teacher');
    Route::get('carga-horario/getAll', [WorkloadController::class, 'getAll'])->name('workload.getall');
    Route::post('carga-horaria/save', [WorkloadController::class, 'saveSchedule'])->name('save-schedule');
    Route::post('carga-horaria/assign/classroom', [WorkloadController::class, 'assignClassroomTeacher'])->name('workload.classroom.assign');
    Route::post('carga-horaria/assign/course', [WorkloadController::class, 'assignCourseTeacher'])->name('workload.course.assign');

    // Sílabus
    Route::get('/silabus', [AcedemicSilabusController::class, 'index'])->name('silabus.index');
    Route::get('/docente/silabus', [SyllabusController::class, 'index'])->name('teacher.silabus.index');

    // Matrículas
    Route::get('/matriculas', [SchoolRegistrationController::class, 'index'])->name('school-registration.index');
    Route::get('/matriculas/registrar', [SchoolRegistrationController::class, 'create'])->name('school-registration.create');
    Route::get('/matriculas/getAll', [SchoolRegistrationController::class, 'getAll'])->name('school-registration.getall');
    Route::post('/matriculas/store', [SchoolRegistrationController::class, 'store'])->name('school-registration.store');
    Route::post('/matriculas/{id}/cambiar-aula', [SchoolRegistrationController::class, 'change'])->name('school-registration.change');
    Route::delete('/matriculas/{id}/anular', [SchoolRegistrationController::class, 'deny'])->name('school-registration.deny');

    // Tesorería
    Route::get('/tesoreria', [TreasuryController::class, 'index'])->name('treasuries.index');
    Route::get('/tesoreria/registrar-pago', [TreasuryController::class, 'create'])->name('treasuries.create');
    Route::post('/tesoreria/store', [TreasuryController::class, 'store'])->name('treasuries.store');
    Route::get('/tesoreria/cancel/{id}', [TreasuryController::class, 'cancel'])->name('treasuries.cancel');

    // Horario
    Route::get('/horario/docente', [WorkloadController::class, 'scheduleTeacher'])->name('workload.schedule.teacher');
    Route::get('/horario/estudiante', [WorkloadController::class, 'scheduleStudent'])->name('workload.schedule.student');

    // Asistencia docente
    Route::get('/asistencia/docente', [AttendanceTeacherController::class, 'index'])->name('attendance.teacher.index');
    Route::get('/asistencia/docente/registrar', [AttendanceTeacherController::class, 'createTeacherAttendance'])->name('attendance.teacher.create');
    Route::get('/asistencia/estudiante/registrar/{fecha_id}', [AttendanceTeacherController::class, 'createStudentAttendance'])->name('attendance.student.create');
    Route::post('/asistencia/aperturar', [AttendanceTeacherController::class, 'openAttendance'])->name('attendance.enable');
    Route::post('/asistencia/cerrar/{fecha_id}', [AttendanceTeacherController::class, 'closeAttendance'])->name('attendance.disable');
    Route::post('/marcar-asistencia/{fecha}', [AttendanceTeacherController::class, 'mark'])->name('attendance.mark');
    Route::post('/marcar-asistencia/{fecha}/presente/{student_id}', [AttendanceTeacherController::class, 'changePresent'])->name('attendance.change.present');
    Route::post('/marcar-asistencia/{fecha}/falta/{student_id}', [AttendanceTeacherController::class, 'changeMissing'])->name('attendance.change.missing');

    // Asistencia estudiante
    Route::get('/asistencia/studiante', [AttendanceStudentController::class, 'index'])->name('attendance.student.index');

    // Asistencia admin - secretaria
    Route::get('/asistencia/admin', [AttendanceAdminController::class, 'index'])->name('attendance.admin.index');

    //Notas docente
    Route::get('/notas/docente', [TeacherCompetenciaController::class, 'index'])->name('grade.teacher.index');
    Route::get('/notas/docente/{classroom_id}/registrar/{student_id}', [TeacherCompetenciaController::class, 'create'])->name('grade.teacher.create');
    Route::post('/notas/docente/{classroom_id}/guardar/{student_id}', [TeacherCompetenciaController::class, 'store'])->name('grade.teacher.store');

    //Notas estudiante
    Route::get('/notas/estudiante', [StudentCompetenciaController::class, 'index'])->name('grade.student.index');

    //Notas admin - secretaria
    Route::get('/notas/admin', [AdminCompetenciaController::class, 'index'])->name('grade.admin.index');
    Route::get('/notas/admin/estudiante/{student_id}', [AdminCompetenciaController::class, 'show'])->name('grade.admin.show');
    Route::get('/notas/admin/estudiante/{student_id}/next', [AdminCompetenciaController::class, 'showNext'])->name('grade.admin.showNext');
    Route::get('/notas/admin/estudiante/{student_id}/previous', [AdminCompetenciaController::class, 'showPrevious'])->name('grade.admin.showPrevious');

    Route::get('reportes', [ReportController::class, 'index'])->name('reports.index');
});

// Auth
Route::get('login', [AuthController::class, 'index'])->name('auth.login');
Route::post('validate', [AuthController::class, 'login'])->name('auth.validate');
Route::get('recuperar-contraseña', [AuthController::class, 'forgotPassword'])->name('auth.password');
Route::post('recuperar-contrasena', [AuthController::class, 'recovery'])->name('auth.recovery');
Route::get('registro', [AuthController::class, 'create'])->name('auth.register');
Route::post('store', [AuthController::class, 'register'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
