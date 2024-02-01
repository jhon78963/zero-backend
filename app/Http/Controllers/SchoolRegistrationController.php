<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\ClassRoom;
use App\Models\CourseCompetencia;
use App\Models\CourseGrade;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\SchoolRegistration;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentCompetencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class SchoolRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.delete')->only(['delete']);
    }

    public function index($period_name)
    {
        $today = now()->format('Y-m-d');
        $period = AcademicPeriod::where('name', $period_name)->first();
        $payments = Payment::all();
        $classrooms = ClassRoom::where('IsDeleted', false)->where('TenantId', $period->id)->get();

        return view('academic.school-registration.index', compact('period', 'payments', 'classrooms'));
    }

    public function create($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $grades = Grade::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $students = Student::where('IsDeleted', false)->where('TenantId', $period->id)->where('status', '0')->get();

        return view('academic.school-registration.create', compact('grades', 'students', 'period'));
    }

    public function getAll($period_id)
    {
        $schoolRegistration = SchoolRegistration::with('student', 'classroom')->where('IsDeleted', false)->where('TenantId', $period_id)->get();
        $count = count($schoolRegistration);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'schoolRegistration' => $schoolRegistration
        ]);
    }

    public function getStudentClassrooms($period_id, $student_id)
    {
        $schoolRegistration = SchoolRegistration::with('student', 'classroom')
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->where('student_id', $student_id)
            ->first();

        $classrooms = ClassRoom::where('grade_id', $schoolRegistration->classroom->grade_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->get();

        return response()->json([
            'classrooms' => $classrooms
        ]);
    }

    public function store(Request $request, $period_id)
    {
        $period = AcademicPeriod::where('id', $period_id)->first();

        $matricula = new SchoolRegistration();
        $matricula->classroom_id = $request->aula_id;
        $matricula->year = $request->matr_año_ingreso;
        $matricula->status = 'ACTIVO';
        $matricula->TenantId = $period_id;
        $matricula->CreatorUserId = Auth::id();

        if ($request->alum_id != null) {
            $matricula->student_id = $request->alum_id;
            $matricula->save();

            $alumno = Student::Find($request->alum_id);
            $alumno->status = '1';
            $alumno->save();

            $aula = ClassRoom::Find($request->aula_id);
            $aula->students_number += 1;
            $aula->save();

            $alumno_seccion = new StudentClassroom();
            $alumno_seccion->student_id = $request->alum_id;
            $alumno_seccion->classroom_id = $request->aula_id;
            $alumno_seccion->TenantId = $period_id;
            $alumno_seccion->CreatorUserId = Auth::id();
            $alumno_seccion->save();

            $course_competencias = DB::table('course_competencias as cc')
                ->where('cc.TenantId', $period_id)
                ->select('cc.id as course_competencia_id')
                ->get();

            foreach ($course_competencias as $course_competencia) {
                $nota = new StudentCompetencia();
                $nota->student_id = $request->alum_id;
                $nota->classroom_id = $request->aula_id;
                $nota->course_competencia_id = $course_competencia->course_competencia_id;
                $nota->CreatorUserId = Auth::id();
                $nota->TenantId = $period_id;
                $nota->save();
            }

            return redirect()->route('school-registration.index', $period->name)->with('datos', 'Matricula Registrada ...!');
        } else {
            $student = new Student([
                'dni' => $request->input('alum_dni'),
                'first_name' => $request->input('alum_primerNombre'),
                'other_names' => $request->input('alum_otrosNombres'),
                'surname' => $request->input('alum_apellidoPaterno'),
                'mother_surname' => $request->input('alum_apellidoMaterno'),
                'code' => $request->input('alum_dni'),
                'institutional_email' => $request->input('alum_dni') . '@sage.edu.pe',
                'phone' => $request->input('alum_celular'),
                'address' => $request->input('alum_direccion'),
                'CreatorUserId' => Auth::id(),
                'TenantId' => $period_id,
                'gender' => $request->gender,
                'status' => '1',
            ]);

            $student->save();

            $this->generateUser($student, $period);

            $matricula->student_id = $student->id;
            $matricula->save();

            $aula = ClassRoom::Find($request->aula_id);
            $aula->students_number += 1;
            $aula->update();

            $alumno_seccion = new StudentClassroom();
            $alumno_seccion->student_id = $student->id;
            $alumno_seccion->classroom_id = $request->aula_id;
            $alumno_seccion->CreatorUserId = Auth::id();
            $alumno_seccion->TenantId = $period_id;
            $alumno_seccion->save();

            $course_competencias = DB::table('course_competencias as cc')
                ->where('cc.TenantId', $period_id)
                ->select('cc.id as course_competencia_id')
                ->get();

            foreach ($course_competencias as $course_competencia) {
                $nota = new StudentCompetencia();
                $nota->student_id = $student->id;
                $nota->classroom_id = $request->aula_id;
                $nota->course_competencia_id = $course_competencia->course_competencia_id;
                $nota->CreatorUserId = Auth::id();
                $nota->TenantId = $period_id;
                $nota->save();
            }

            return redirect()->route('school-registration.index', $period->name)->with('datos', 'Matricula Registrada ...!');
        }
    }

    public function promoted(Request $request, $period_id, $matricula_id)
    {
        $period = AcademicPeriod::where('id', $period_id)->first();

        $matricula = SchoolRegistration::find($matricula_id);
        $matricula->classroom_id = $request->aula_id;
        $matricula->status = 'ACTIVO';
        $matricula->TenantId = $period_id;

        if ($request->alum_id != null) {
            $matricula->student_id = $request->alum_id;
            $matricula->save();

            $alumno = Student::Find($request->alum_id);
            $alumno->status = '1';
            $alumno->save();

            $aula = ClassRoom::Find($request->aula_id);
            $aula->students_number += 1;
            $aula->save();

            $alumno_seccion = new StudentClassroom();
            $alumno_seccion->student_id = $request->alum_id;
            $alumno_seccion->classroom_id = $request->aula_id;
            $alumno_seccion->TenantId = $period_id;
            $alumno_seccion->CreatorUserId = Auth::id();
            $alumno_seccion->save();

            $course_competencias = DB::table('course_competencias as cc')
                ->where('cc.TenantId', $period_id)
                ->select('cc.id as course_competencia_id')
                ->get();

            foreach ($course_competencias as $course_competencia) {
                $nota = new StudentCompetencia();
                $nota->student_id = $request->alum_id;
                $nota->classroom_id = $request->aula_id;
                $nota->course_competencia_id = $course_competencia->course_competencia_id;
                $nota->CreatorUserId = Auth::id();
                $nota->TenantId = $period_id;
                $nota->save();
            }

            return redirect()->route('school-registration.index', $period->name)->with('datos', 'Matricula Registrada ...!');
        }
    }

    public function generateUser($student, $period)
    {
        $user = new User([
            'username' => $student->code,
            'email' => $student->institutional_email,
            'name' => $student->first_name,
            'surname' => $student->surname,
            'password' => Hash::make('123456789'),
            'phoneNumber' => $student->phone,
            'profilePicture' => '/assets/img/avatars/1.png',
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period->id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 4,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period->id,
        ]);
    }

    public function change(Request $request, $period_id, $registration_id)
    {
        $matricula = SchoolRegistration::where('TenantId', $period_id)->where('IsDeleted', false)->find($registration_id);

        $aula_anterior = ClassRoom::where('TenantId', $period_id)->where('IsDeleted', false)->find($matricula->classroom_id);
        $aula_anterior->students_number -= 1;
        $aula_anterior->save();

        $aula_nueva = ClassRoom::where('TenantId', $period_id)->where('IsDeleted', false)->find($request->classroom_id);
        $aula_nueva->students_number += 1;
        $aula_nueva->save();

        $aula_seccion = StudentClassroom::where('TenantId', $period_id)->where('student_id', $request->student_id)->first();
        $aula_seccion->classroom_id = $aula_nueva->id;
        $aula_seccion->save();

        $matricula->classroom_id = $aula_nueva->id;
        $matricula->save();

        return back();
    }

    public function deny($period_id, $registration_id)
    {
        $matricula = SchoolRegistration::where('TenantId', $period_id)->where('IsDeleted', false)->find($registration_id);
        $matricula->DeleterUserId = Auth::id();
        $matricula->IsDeleted = true;
        $matricula->DeletionTime = now()->format('Y-m-d h:i:s');
        $matricula->save();

        $alumno = Student::Find($matricula->student_id);
        $alumno->status = 'ANULADO';
        $alumno->save();

        $user = User::where('email', $alumno->institutional_email)->where('TenantId', $period_id)->where('IsDeleted', false)->first();

        DB::table('user_roles')->where('userId', $user->id)->where('roleId', 4)->where('TenantId', $period_id)->delete();

        $user->delete();

        $aula = ClassRoom::where('TenantId', $period_id)->where('IsDeleted', false)->find($matricula->classroom_id);
        $aula->students_number -= 1;
        $aula->save();

        StudentClassroom::where('student_id', $matricula->student_id)
            ->where('classroom_id', $matricula->classroom_id)
            ->where('TenantId', $period_id)
            ->delete();

        StudentCompetencia::where('student_id', $matricula->student_id)
            ->where('classroom_id', $matricula->classroom_id)
            ->where('TenantId', $period_id)
            ->delete();

        return back();
    }
}
