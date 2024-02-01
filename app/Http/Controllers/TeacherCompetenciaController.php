<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\FailedCourse;
use App\Models\SchoolRegistration;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentCompetencia;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherCompetenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();

        $teacher_email = Auth::user()->email;

        $teacher = Teacher::where('institutional_email', $teacher_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->first();

        $classrooms = TeacherClassroom::join('class_rooms as cs', 'cs.id', 'teacher_classrooms.classroom_id')
            ->where('cs.TenantId', $period->id)
            ->where('teacher_classrooms.teacher_id', $teacher->id)
            ->where('cs.IsDeleted', false)
            ->select('cs.*')
            ->get();

        if ($request->classroom_id != null) {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', $request->classroom_id)
                ->first();

            $classroom_students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
                ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.classroom_id', $request->classroom_id)
                ->select('student_classroom.*', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
                ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
                ->get();

            return view('academic.note.index', compact('period', 'classrooms', 'classroomSelected', 'classroom_students'));
        } else {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', 1)
                ->first();

            $classroom_students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
                ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.classroom_id', 1)
                ->select('student_classroom.*', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
                ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
                ->get();

            return view('academic.note.index', compact('period', 'classrooms', 'classroomSelected', 'classroom_students'));
        }



        // $room = ClassRoom::where('id', $class_room->classroom_id)->first();

        // $classroom_students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
        //     ->where('student_classroom.classroom_id', $class_room->classroom_id)
        //     ->select('student_classroom.*', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
        //     ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
        //     ->get();

        // return view('academic.note.index', compact('period', 'classroom_students'));
    }

    public function create($period_name, $classroom_id, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();

        $student = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('students.id', $student_id)
            ->select('students.*', 'sc.classroom_id')
            ->first();

        $nextEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '>', $student->id)
            ->orderBy('students.id')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '<', $student->id)
            ->orderBy('students.id')
            ->select('students.*')
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->get();
        $courses = Course::where('TenantId', $period->id)->get();
        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $competenciasPorCurso = [];
        foreach ($studentsGrade as $item) {
            $competenciasPorCurso[$item->competencia->course_id][] = [
                'id' => $item->competencia->id,
                'description' => $item->competencia->description,
                'grade_b_1' => $item->grade_b_1,
                'grade_b_2' => $item->grade_b_2,
                'grade_b_3' => $item->grade_b_3,
                'grade_b_4' => $item->grade_b_4
            ];
        }

        $promediosPorCurso = [];
        foreach ($competenciasPorCurso as $cursoId => $competencias) {
            $promediosPorCurso[$cursoId] = [
                'promedio_grade_b_1' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_1', $competencias)),
                'prom_grade_b_1' => $this->calcularPromedio('grade_b_1', $competencias),
                'promedio_grade_b_2' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_2', $competencias)),
                'prom_grade_b_2' => $this->calcularPromedio('grade_b_2', $competencias),
                'promedio_grade_b_3' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_3', $competencias)),
                'prom_grade_b_3' => $this->calcularPromedio('grade_b_3', $competencias),
                'promedio_grade_b_4' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_4', $competencias)),
                'prom_grade_b_4' => $this->calcularPromedio('grade_b_4', $competencias),
                'promedio_grade_course_final' => $this->convertirPromedioALetras(
                    ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                    ) / 4
                ),
                'prom_grade_course_final' => ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                ) / 4,
            ];
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
    }

    public function createNext($period_name, $classroom_id, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();

        $student = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('students.id', $student_id)
            ->select('students.*', 'sc.classroom_id')
            ->first();

        $nextEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '>', $student->id)
            ->orderBy('students.id')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '<', $student->id)
            ->orderBy('students.id', 'DESC')
            ->select('students.*')
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->get();
        $courses = Course::where('TenantId', $period->id)->get();
        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $competenciasPorCurso = [];
        foreach ($studentsGrade as $item) {
            $competenciasPorCurso[$item->competencia->course_id][] = [
                'id' => $item->competencia->id,
                'description' => $item->competencia->description,
                'grade_b_1' => $item->grade_b_1,
                'grade_b_2' => $item->grade_b_2,
                'grade_b_3' => $item->grade_b_3,
                'grade_b_4' => $item->grade_b_4
            ];
        }

        $promediosPorCurso = [];

        foreach ($competenciasPorCurso as $cursoId => $competencias) {
            $promediosPorCurso[$cursoId] = [
                'promedio_grade_b_1' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_1', $competencias)),
                'prom_grade_b_1' => $this->calcularPromedio('grade_b_1', $competencias),
                'promedio_grade_b_2' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_2', $competencias)),
                'prom_grade_b_2' => $this->calcularPromedio('grade_b_2', $competencias),
                'promedio_grade_b_3' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_3', $competencias)),
                'prom_grade_b_3' => $this->calcularPromedio('grade_b_3', $competencias),
                'promedio_grade_b_4' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_4', $competencias)),
                'prom_grade_b_4' => $this->calcularPromedio('grade_b_4', $competencias),
                'promedio_grade_course_final' => $this->convertirPromedioALetras(
                    ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                    ) / 4
                ),
                'prom_grade_course_final' => ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                ) / 4,
            ];
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
    }

    public function createPrevious($period_name, $classroom_id, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();

        $student = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('students.id', $student_id)
            ->select('students.*', 'sc.classroom_id')
            ->first();

        $nextEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '>', $student->id)
            ->orderBy('students.id')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where('students.id', '<', $student->id)
            ->orderBy('students.id', 'DESC')
            ->select('students.*')
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->get();
        $courses = Course::where('TenantId', $period->id)->get();
        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $competenciasPorCurso = [];
        foreach ($studentsGrade as $item) {
            $competenciasPorCurso[$item->competencia->course_id][] = [
                'id' => $item->competencia->id,
                'description' => $item->competencia->description,
                'grade_b_1' => $item->grade_b_1,
                'grade_b_2' => $item->grade_b_2,
                'grade_b_3' => $item->grade_b_3,
                'grade_b_4' => $item->grade_b_4
            ];
        }

        $promediosPorCurso = [];

        foreach ($competenciasPorCurso as $cursoId => $competencias) {
            $promediosPorCurso[$cursoId] = [
                'promedio_grade_b_1' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_1', $competencias)),
                'prom_grade_b_1' => $this->calcularPromedio('grade_b_1', $competencias),
                'promedio_grade_b_2' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_2', $competencias)),
                'prom_grade_b_2' => $this->calcularPromedio('grade_b_2', $competencias),
                'promedio_grade_b_3' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_3', $competencias)),
                'prom_grade_b_3' => $this->calcularPromedio('grade_b_3', $competencias),
                'promedio_grade_b_4' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_4', $competencias)),
                'prom_grade_b_4' => $this->calcularPromedio('grade_b_4', $competencias),
                'promedio_grade_course_final' => $this->convertirPromedioALetras(
                    ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                    ) / 4
                ),
                'prom_grade_course_final' => ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                ) / 4,
            ];
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
    }

    private function calcularPromedio($gradeKey, $competencias)
    {
        $total = 0;
        $count = count($competencias);

        foreach ($competencias as $competencia) {
            $total += $this->convertirNotaAPromedio($competencia[$gradeKey]);
        }

        return $count > 0 ? $total / $count : 0;
    }

    private function convertirNotaAPromedio($nota)
    {
        // Aquí puedes ajustar la conversión de nota a promedio según tu lógica
        switch ($nota) {
            case 'AD':
                return 4.0;
            case 'A':
                return 3.0;
            case 'B':
                return 2.0;
            case 'C':
                return 1.0;
            default:
                return 0.0;
        }
    }

    private function convertirPromedioALetras($promedio)
    {
        // Ajusta esta lógica según tus criterios para asignar letras a los promedios
        if ($promedio >= 4) {
            return 'AD';
        } elseif ($promedio >= 2.5) {
            return 'A';
        } elseif ($promedio >= 1.5) {
            return 'B';
        } else {
            return 'C';
        }
    }

    public function store(Request $request, $period_id, $classroom_id, $student_id)
    {
        $studentCopente = StudentCompetencia::where('classroom_id', $classroom_id)->where('student_id', $student_id)->where('TenantId', $period_id)->get();

        if ($request->has('nota_1')) {
            for ($i = 0; $i < count($request->nota_1); $i++) {
                $studentCopente[$i]->grade_b_1 = $request->nota_1[$i];
                $studentCopente[$i]->save();
            }
        }

        if ($request->has('nota_2')) {
            for ($i = 0; $i < count($request->nota_2); $i++) {
                $studentCopente[$i]->grade_b_2 = $request->nota_2[$i];
                $studentCopente[$i]->save();
            }
        }

        if ($request->has('nota_3')) {
            for ($i = 0; $i < count($request->nota_3); $i++) {
                $studentCopente[$i]->grade_b_3 = $request->nota_3[$i];
                $studentCopente[$i]->save();
            }
        }

        if ($request->has('nota_4')) {
            for ($i = 0; $i < count($request->nota_4); $i++) {
                $studentCopente[$i]->grade_b_4 = $request->nota_4[$i];
                $studentCopente[$i]->save();
            }
        }

        $competenciasPorCurso = [];
        foreach ($studentCopente as $item) {
            $competenciasPorCurso[$item->competencia->course_id][] = [
                'id' => $item->competencia->id,
                'description' => $item->competencia->description,
                'grade_b_1' => $item->grade_b_1,
                'grade_b_2' => $item->grade_b_2,
                'grade_b_3' => $item->grade_b_3,
                'grade_b_4' => $item->grade_b_4
            ];
        }



        $promediosPorCurso = [];

        foreach ($competenciasPorCurso as $cursoId => $competencias) {
            $promediosPorCurso[$cursoId] = [
                'promedio_final_course_en_letra' => $this->convertirPromedioALetras(
                    ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                    ) / 4
                ),
                'promedio_final_course' => ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                ) / 4,
            ];
        }

        //return dd($promediosPorCurso);

        $cursosAprobados = 0;
        $cursosJaladosCount = 0;
        $cursosJalados = [];

        foreach ($promediosPorCurso as $cursoId => $promedios) {
            $promedioFinal = $promedios['promedio_final_course_en_letra'];

            // Verificar si el curso tiene una calificación de "A" o "AD"
            if ($promedioFinal === 'A' || $promedioFinal === 'AD') {
                $cursosAprobados++;
            }

            if ($promedioFinal === 'C') {
                $cursosJaladosCount++;
                $cursosJalados[] = $cursoId;
            }
        }

        // Verificar si se aprobaron al menos 4 cursos
        $count_courses = Course::where('IsDeleted', false)->where('TenantId', $period_id)->count();
        $student_classroom = StudentClassroom::where('TenantId', $period_id)->where('classroom_id', $classroom_id)->where('student_id', $student_id)->first();
        $school_registration = SchoolRegistration::where('IsDeleted', false)->where('TenantId', $period_id)->where('student_id', $student_id)->first();
        if ($cursosAprobados >= ($count_courses / 2)) {
            $student_classroom->grade_final = 'PROMOVIDO';
            $student_classroom->save();

            $school_registration->status = 'PROMOVIDO';
            $school_registration->save();
        } else {
            $student_classroom->grade_final = 'PERMANENTE';
            $student_classroom->save();

            $school_registration->status = 'PERMANENTE';
            $school_registration->save();
        }

        DB::table('student_failed_course')->where('TenantId', $period_id)->where('classroom_id', $classroom_id)->where('student_id', $student_id)->delete();
        if (count($cursosJalados) > 0) {
            foreach ($cursosJalados as $cursoJalado) {
                $student_classroom->grade_final = 'RECUPERACION';
                $student_classroom->save();

                $failedCourse = new FailedCourse();
                $failedCourse->TenantId = $period_id;
                $failedCourse->course_id = $cursoJalado;
                $failedCourse->student_id = $student_id;
                $failedCourse->classroom_id = $classroom_id;
                $failedCourse->save();
            }
        }

        return back();
    }
}
