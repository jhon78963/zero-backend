<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentCompetencia;
use Illuminate\Http\Request;

class AdminCompetenciaController extends Controller
{
    public function index(Request $request, $period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $classrooms = ClassRoom::where('TenantId', $period->id)->where('IsDeleted', false)->get();

        if ($request->classroom_id != null) {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', $request->classroom_id)
                ->first();

            $students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
                ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.classroom_id', $request->classroom_id)
                ->select('student_classroom.*', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
                ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
                ->get();

            return view('academic.note.admin', compact('period', 'classrooms', 'classroomSelected', 'students'));
        } else {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', 1)
                ->first();

            $students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
                ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.classroom_id', 1)
                ->select('student_classroom.*', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
                ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
                ->get();

            return view('academic.note.admin', compact('period', 'classrooms', 'classroomSelected', 'students'));
        }

        // $student_email = Auth::user()->email;
        // $student = Student::where('institutional_email', $student_email)
        //     ->where('TenantId', $period->id)
        //     ->first();

        // $class_room = StudentClassroom::where('student_id', $student->id)
        //     ->where('TenantId', $period->id)
        //     ->first();

        // $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $class_room->classroom_id)->where('student_id', $student->id)->get();
        // $courses = Course::where('TenantId', $period->id)->get();
        // $class_room = StudentClassroom::where('student_id', $student->id)
        //     ->where('TenantId', $period->id)
        //     ->first();

        // $competenciasPorCurso = [];
        // foreach ($studentsGrade as $item) {
        //     $competenciasPorCurso[$item->competencia->course_id][] = [
        //         'id' => $item->competencia->id,
        //         'description' => $item->competencia->description,
        //         'grade_b_1' => $item->grade_b_1,
        //         'grade_b_2' => $item->grade_b_2,
        //         'grade_b_3' => $item->grade_b_3,
        //         'grade_b_4' => $item->grade_b_4
        //     ];
        // }

        // $promediosPorCurso = [];

        // foreach ($competenciasPorCurso as $cursoId => $competencias) {
        //     $promediosPorCurso[$cursoId] = [
        //         'promedio_grade_b_1' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_1', $competencias)),
        //         'prom_grade_b_1' => $this->calcularPromedio('grade_b_1', $competencias),
        //         'promedio_grade_b_2' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_2', $competencias)),
        //         'prom_grade_b_2' => $this->calcularPromedio('grade_b_2', $competencias),
        //         'promedio_grade_b_3' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_3', $competencias)),
        //         'prom_grade_b_3' => $this->calcularPromedio('grade_b_3', $competencias),
        //         'promedio_grade_b_4' => $this->convertirPromedioALetras($this->calcularPromedio('grade_b_4', $competencias)),
        //         'prom_grade_b_4' => $this->calcularPromedio('grade_b_4', $competencias),
        //     ];
        // }

        // return view('academic.note.student', compact('period', 'studentsGrade', 'student', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
        // return view('academic.note.admin', compact('period', 'classrooms'));
    }

    public function show($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student = Student::where('id', $student_id)
            ->where('TenantId', $period->id)
            ->first();

        $nextEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '>', $student_id)->orderBy('id')->first();
        $previousEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '<', $student_id)->orderBy('id')->first();

        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $class_room->classroom_id)->where('student_id', $student->id)->get();
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
            ];
        }

        return view('academic.note.admin-show', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
    }

    public function showNext($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();

        $nextEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '>', $student_id)->orderBy('id')->first();
        $previousEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '<', $student_id)->orderBy('id')->first();

        $student = Student::where('id', $student_id)
            ->where('TenantId', $period->id)
            ->first();

        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $class_room->classroom_id)->where('student_id', $student->id)->get();
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
            ];
        }

        return view('academic.note.admin-show', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
    }

    public function showPrevious($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $nextEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '>', $student_id)->orderBy('id')->first();
        $previousEstudiante = Student::where('TenantId', $period->id)->where('status', true)->where('id', '<', $student_id)->orderBy('id')->first();

        $student = Student::where('id', $student_id)
            ->where('TenantId', $period->id)
            ->first();

        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $class_room->classroom_id)->where('student_id', $student->id)->get();
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
            ];
        }

        return view('academic.note.admin-show', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso'));
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
}