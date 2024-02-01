<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\CourseCompetencia;
use App\Models\CourseGrade;
use App\Models\Grade;
use App\Models\InvoiceNumber;
use App\Models\SchoolRegistration;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClassroom;
use Carbon\Carbon;

class AcademicPeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.period')->only(['index', 'home']);
        $this->middleware('check.permissions:Admin,pages.period.modify')->only(['store']);
    }

    public function periodHome()
    {
        $academic_periods = AcademicPeriod::where('IsDeleted', false)->get();
        return view('home.period', compact('academic_periods'));
    }

    public function index()
    {
        $academic_periods = AcademicPeriod::where('IsDeleted', false)->get();
        return view('setup.academic-period', compact('academic_periods'));
    }

    public function store(Request $request)
    {
        if (AcademicPeriod::all()->count()) {
            $last_period_id = AcademicPeriod::all()->last()->id + 1;
        } else {
            $last_period_id = 1;
        }

        AcademicPeriod::create([
            'id' => $last_period_id,
            'TenantId' => $last_period_id,
            'name' => 'pa-' . $request->name,
            'year' => $request->name,
            'yearName' => $request->yearName,
            'CreatorUserId' => Auth::id(),
        ]);

        $this->generateGrades($last_period_id);
        $this->generateSections($last_period_id);
        $this->generateCourses($last_period_id);

        $grades = Grade::where('TenantId', $last_period_id)->get();
        $sections = Section::where('TenantId', $last_period_id)->get();
        $courses = Course::where('TenantId', $last_period_id)->get();

        for ($i = 0; $i < count($grades); $i++) {
            for ($j = 0; $j < count($sections); $j++) {
                $class_room = new ClassRoom();
                $class_room->grade_id = $grades[$i]->id;
                $class_room->section_id  = $sections[$j]->id;
                $class_room->description =  $grades[$i]->description . ' ' . $sections[$j]->description;
                $class_room->limit = 25;
                $class_room->students_number = 0;
                $class_room->TenantId = $last_period_id;
                $class_room->CreatorUserId = Auth::id();
                $class_room->save();
            }
        }

        for ($i = 0; $i < count($courses); $i++) {
            for ($j = 0; $j < count($grades); $j++) {
                $course_grade = new CourseGrade();
                $course_grade->course_id = $courses[$i]->id;
                $course_grade->grade_id = $grades[$j]->id;
                $course_grade->TenantId = $last_period_id;
                $course_grade->save();
            }
        }

        $last_academic_period = AcademicPeriod::where('year', $request->name - 1)->first();

        $promoted_students = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->join('grades as g', 'g.id', 'cr.grade_id')
            ->where('student_classroom.TenantId', $last_academic_period->id)
            ->where('student_classroom.grade_final', 'PROMOVIDO')
            ->select('student_classroom.*', 'g.id as grade_id')
            ->get();

        foreach ($promoted_students as $promoted_student) {
            $promoted_classroom = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
                ->join('sections as s', 's.id', 'class_rooms.section_id')
                ->where('class_rooms.TenantId', $last_academic_period->id)
                ->where('class_rooms.IsDeleted', false)
                ->where('g.id', $promoted_student->grade_id + 1)
                ->select('class_rooms.*', 's.description as section_name', 'g.description as grade_description')
                ->first();

            $student = Student::find($promoted_student->student_id);

            $newStudent = new Student([
                'dni' => $student->dni,
                'first_name' => $student->first_name,
                'other_names' => $student->other_names,
                'surname' => $student->surname,
                'mother_surname' => $student->mother_surname,
                'code' => $student->code,
                'institutional_email' => $student->institutional_email,
                'phone' => $student->phone,
                'address' => $student->address,
                'gender' => $student->gender,
                'CreatorUserId' => Auth::id(),
                'TenantId' => $last_period_id,
            ]);

            $newStudent->save();

            $classroom = ClassRoom::where('description', $promoted_classroom->grade_description . ' ' . $promoted_classroom->section_name)
                ->where('TenantId', $last_period_id)
                ->where('IsDeleted', false)
                ->first();

            SchoolRegistration::create([
                'CreatorUserId' => Auth::id(),
                'TenantId' => $last_period_id,
                'student_id' => $newStudent->id,
                'classroom_id' => $classroom->id,
                'year' => $request->name,
                'status' => 'CONTINUA'
            ]);
        }

        InvoiceNumber::create([
            'type' => 'Boleta electrónica',
            'serie' => 'B00' . $last_period_id,
            'initial_number' => 1001,
            'invoicing_started' => '1',
            'status' => 'ACTIVO',
            'TenantId' => $last_period_id
        ]);

        return back();
    }

    public function generateGrades($period_id)
    {
        $grade = new Grade();
        $grade->description = '1er grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();

        $grade = new Grade();
        $grade->description = '2do grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();

        $grade = new Grade();
        $grade->description = '3er grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();

        $grade = new Grade();
        $grade->description = '4to grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();

        $grade = new Grade();
        $grade->description = '5to grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();

        $grade = new Grade();
        $grade->description = '6to grado';
        $grade->TenantId = $period_id;
        $grade->CreatorUserId = Auth::id();
        $grade->save();
    }

    public function generateSections($period_id)
    {
        $section = new Section();
        $section->description = 'A';
        $section->TenantId = $period_id;
        $section->CreatorUserId = Auth::id();
        $section->save();

        $section = new Section();
        $section->description = 'B';
        $section->TenantId = $period_id;
        $section->CreatorUserId = Auth::id();
        $section->save();

        $section = new Section();
        $section->description = 'C';
        $section->TenantId = $period_id;
        $section->CreatorUserId = Auth::id();
        $section->save();
    }

    public function generateCourses($period_id)
    {
        // Course 1
        $course1 = new Course();
        $course1->description = 'Comunicación';
        $course1->type = 'GENERAL';
        $course1->TenantId = $period_id;
        $course1->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Se comunica oralmente en su lengua materna';
        $competencia->course_id = $course1->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Lee diversos tipos de textos escritos en su lengua materna';
        $competencia->course_id = $course1->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Escribe diversos tipos de textos en su lengua materna';
        $competencia->course_id = $course1->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 2
        $course2 = new Course();
        $course2->description = 'Matemática';
        $course2->type = 'GENERAL';
        $course2->TenantId = $period_id;
        $course2->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de cantidad';
        $competencia->course_id = $course2->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas deregularidad, equivalencia y cambio';
        $competencia->course_id = $course2->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de forma, movimiento y localización';
        $competencia->course_id = $course2->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de gestión de datos e incertidumbre';
        $competencia->course_id = $course2->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 3
        $course3 = new Course();
        $course3->description = 'Ciencia y Tecnología';
        $course3->type = 'GENERAL';
        $course3->TenantId = $period_id;
        $course3->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Indaga mediante métodos científicos para construir sus conocimientos';
        $competencia->course_id = $course3->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Explica el mundo físico basándose en conocimientos sobre los seres vivos, materia y energía, biodiversidad, Tierra y universo';
        $competencia->course_id = $course3->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Diseña y construye soluciones tecnológicas para resolver problemas de entorno';
        $competencia->course_id = $course3->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 4
        $course4 = new Course();
        $course4->description = 'Personal Social';
        $course4->type = 'GENERAL';
        $course4->TenantId = $period_id;
        $course4->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye su identidad';
        $competencia->course_id = $course4->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Convive y participa democráticamente en la búsqueda del bien común';
        $competencia->course_id = $course4->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye interpretaciones históricas';
        $competencia->course_id = $course4->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Gestiona responsablemente el espacio y el ambiente';
        $competencia->course_id = $course4->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Gestiona responsablemente los recursos económicos';
        $competencia->course_id = $course4->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 5
        $course5 = new Course();
        $course5->description = 'Arte y cultura';
        $course5->type = 'AREA';
        $course5->TenantId = $period_id;
        $course5->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Apreca de manera crítica manifestaciones artístico-culturales';
        $competencia->course_id = $course5->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Crea proyectos desde los lenguajes artísticos';
        $competencia->course_id = $course5->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 6
        $course6 = new Course();
        $course6->description = 'Educación Religiosa';
        $course6->type = 'AREA';
        $course6->TenantId = $period_id;
        $course6->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente, comprendiendo la doctrina de su propia religión. abierto al diálogo con las que le son cercanas';
        $competencia->course_id = $course6->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Asume la experiencia del encuentro personal y comunitario con Dios en su proyecto de vida en coherencia con su creencia religiosa';
        $competencia->course_id = $course6->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 7
        $course7 = new Course();
        $course7->description = 'Educación física';
        $course7->type = 'AREA';
        $course7->TenantId = $period_id;
        $course7->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Se desenvuelve de manera autónoma a través de su motricidad';
        $competencia->course_id = $course7->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Asume una vida saludable';
        $competencia->course_id = $course7->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Interactúa a través de sus habilidades sociomotrices';
        $competencia->course_id = $course7->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        // Course 8
        $course8 = new Course();
        $course8->description = 'Inglés';
        $course8->type = 'AREA';
        $course8->TenantId = $period_id;
        $course8->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Se comunica oralmente en ingés como lengua extranjera';
        $competencia->course_id = $course8->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Lee diversos tipos de textos escritos en inglés como lengua extranjera';
        $competencia->course_id = $course8->id;
        $competencia->TenantId = $period_id;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Escribe diversos tipos de textos en inglés como lengua extranjera';
        $competencia->course_id = $course8->id;
        $competencia->TenantId = $period_id;
        $competencia->save();
    }

    public function home($period_name)
    {
        $diaActual = Carbon::now('America/Lima')->locale('es')->isoFormat('dddd');
        $fechaActual = Carbon::now('America/Lima')->locale('es')->isoFormat("MMMM D, YYYY");
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('academic.period', compact('diaActual', 'fechaActual', 'period'));
    }
}
