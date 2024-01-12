<?php

namespace App\Http\Controllers;

use App\Models\Api\User;
use App\Models\ClassRoom;
use App\Models\CourseCompetencia;
use App\Models\CourseGrade;
use App\Models\Grade;
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
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.delete')->only(['delete']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('academic.school-registration.index');
    }

    public function register()
    {
        $grades = Grade::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $students = Student::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        return view('academic.school-registration.create', compact('grades', 'students'));
    }

    public function getAll()
    {
        $schoolRegistration = SchoolRegistration::with('student', 'classroom')->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();

        $count = count($schoolRegistration);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'schoolRegistration' => $schoolRegistration
        ]);
    }

    public function create(Request $request)
    {
        $matricula = new SchoolRegistration();
        $matricula->classroom_id = $request->aula_id;
        $matricula->year = $request->matr_año_ingreso;
        $matricula->status = 'ACTIVO';
        $matricula->TenantId = $this->academic_period->id;
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
            $alumno_seccion->TenantId = $this->academic_period->id;
            $alumno_seccion->CreatorUserId = Auth::id();
            $alumno_seccion->save();

            $curso_secciones = CourseGrade::where('course_id', $request->grado_id)->get();
            $course_competencias = DB::table('course_competencias as cc')
                ->join('course_grades as cg', 'cg.course_id', 'cc.course_id')
                ->join('class_rooms as cr', 'cr.grade_id', 'cg.grade_id')
                ->where('cc.TenantId', $this->academic_period->id)
                ->where('cr.section_id', $request->secc_id)
                ->select('cc.id as course_competencia_id')
                ->get();

            foreach ($course_competencias as $course_competencia) {
                $nota = new StudentCompetencia();
                $nota->student_id = $request->alum_id;
                $nota->classroom_id = $request->aula_id;
                $nota->course_competencia_id = $course_competencia->course_competencia_id;
                $nota->CreatorUserId = Auth::id();
                $nota->TenantId = $this->academic_period->id;
                $nota->save();
            }

            return redirect()->route('school-registration.index', $this->academic_period->name)->with('datos', 'Matricula Registrada ...!');
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
                'TenantId' => $this->academic_period->id,
            ]);

            $student->save();

            $this->generateUser($student);

            $matricula->student_id = $student->id;
            $matricula->save();

            $aula = ClassRoom::Find($request->aula_id);
            $aula->students_number += 1;
            $aula->update();

            $alumno_seccion = new StudentClassroom();
            $alumno_seccion->student_id = $student->id;
            $alumno_seccion->classroom_id = $request->aula_id;
            $alumno_seccion->CreatorUserId = Auth::id();
            $alumno_seccion->TenantId = $this->academic_period->id;
            $alumno_seccion->save();

            $curso_secciones = CourseGrade::where('course_id', $request->grado_id)->get();
            $course_competencias = DB::table('course_competencias as cc')
                ->join('course_grades as cg', 'cg.course_id', 'cc.course_id')
                ->join('class_rooms as cr', 'cr.grade_id', 'cg.grade_id')
                ->where('cc.TenantId', $this->academic_period->id)
                ->where('cr.section_id', $request->secc_id)
                ->select('cc.id as course_competencia_id')
                ->get();

            foreach ($course_competencias as $course_competencia) {
                $nota = new StudentCompetencia();
                $nota->student_id = $student->id;
                $nota->classroom_id = $request->aula_id;
                $nota->course_competencia_id = $course_competencia->course_competencia_id;
                $nota->CreatorUserId = Auth::id();
                $nota->TenantId = $this->academic_period->id;
                $nota->save();
            }

            return redirect()->route('school-registration.index', $this->academic_period->name)->with('datos', 'Matricula Registrada ...!');
        }
    }

    public function generateUser($student)
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
            'TenantId' => $this->academic_period->id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 4,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);
    }
}
