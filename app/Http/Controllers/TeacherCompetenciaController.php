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
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '>', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where(function ($query) use ($student) {
                                $query->where('students.mother_surname', '>', $student->mother_surname)
                                    ->orWhere(function ($query) use ($student) {
                                        $query->where('students.mother_surname', '=', $student->mother_surname)
                                            ->where(function ($query) use ($student) {
                                                $query->where('students.first_name', '>', $student->first_name)
                                                    ->orWhere(function ($query) use ($student) {
                                                        $query->where('students.first_name', '=', $student->first_name)
                                                            ->where('students.other_names', '>', $student->other_names);
                                                    });
                                            });
                                    });
                            });
                    });
            })
            ->orderBy('students.surname')->orderBy('students.mother_surname')->orderBy('students.first_name')->orderBy('students.other_names')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '<', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where('students.first_name', '<', $student->first_name);
                    });
            })
            ->orderBy('students.surname', 'DESC')->orderBy('students.mother_surname', 'DESC')->orderBy('students.first_name', 'DESC')->orderBy('students.other_names', 'DESC')
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

        $courseFailed = FailedCourse::where('classroom_id', $classroom_id)->where('student_id', $student_id)->get();

        foreach ($promediosPorCurso as $cursoId => $promedio) {
            $failedCourse = $courseFailed->where('course_id', $cursoId)->first();

            if ($failedCourse) {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => $failedCourse->grade_extension]);
            } else {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => null]);
            }
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso', 'courseFailed'));
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
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '>', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where(function ($query) use ($student) {
                                $query->where('students.mother_surname', '>', $student->mother_surname)
                                    ->orWhere(function ($query) use ($student) {
                                        $query->where('students.mother_surname', '=', $student->mother_surname)
                                            ->where(function ($query) use ($student) {
                                                $query->where('students.first_name', '>', $student->first_name)
                                                    ->orWhere(function ($query) use ($student) {
                                                        $query->where('students.first_name', '=', $student->first_name)
                                                            ->where('students.other_names', '>', $student->other_names);
                                                    });
                                            });
                                    });
                            });
                    });
            })
            ->orderBy('students.surname')->orderBy('students.mother_surname')->orderBy('students.first_name')->orderBy('students.other_names')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '<', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where('students.first_name', '<', $student->first_name);
                    });
            })
            ->orderBy('students.surname', 'DESC')->orderBy('students.mother_surname', 'DESC')->orderBy('students.first_name', 'DESC')->orderBy('students.other_names')
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

        $courseFailed = FailedCourse::where('classroom_id', $classroom_id)->where('student_id', $student_id)->get();

        foreach ($promediosPorCurso as $cursoId => $promedio) {
            $failedCourse = $courseFailed->where('course_id', $cursoId)->first();

            if ($failedCourse) {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => $failedCourse->grade_extension]);
            } else {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => null]);
            }
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso', 'courseFailed'));
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
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '>', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where(function ($query) use ($student) {
                                $query->where('students.mother_surname', '>', $student->mother_surname)
                                    ->orWhere(function ($query) use ($student) {
                                        $query->where('students.mother_surname', '=', $student->mother_surname)
                                            ->where(function ($query) use ($student) {
                                                $query->where('students.first_name', '>', $student->first_name)
                                                    ->orWhere(function ($query) use ($student) {
                                                        $query->where('students.first_name', '=', $student->first_name)
                                                            ->where('students.other_names', '>', $student->other_names);
                                                    });
                                            });
                                    });
                            });
                    });
            })
            ->orderBy('students.surname')->orderBy('students.mother_surname')->orderBy('students.first_name')->orderBy('students.other_names')
            ->select('students.*')
            ->first();

        $previousEstudiante = Student::join('student_classroom as sc', 'sc.student_id', 'students.id')
            ->where('students.TenantId', $period->id)
            ->where('students.status', true)
            ->where('sc.classroom_id', $student->classroom_id)
            ->where(function ($query) use ($student) {
                $query->where('students.surname', '<', $student->surname)
                    ->orWhere(function ($query) use ($student) {
                        $query->where('students.surname', '=', $student->surname)
                            ->where('students.first_name', '<', $student->first_name);
                    });
            })
            ->orderBy('students.surname', 'DESC')->orderBy('students.mother_surname', 'DESC')->orderBy('students.first_name', 'DESC')->orderBy('students.other_names', 'DESC')
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

        $courseFailed = FailedCourse::where('classroom_id', $classroom_id)->where('student_id', $student_id)->get();

        foreach ($promediosPorCurso as $cursoId => $promedio) {
            $failedCourse = $courseFailed->where('course_id', $cursoId)->first();

            if ($failedCourse) {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => $failedCourse->grade_extension]);
            } else {
                $promediosPorCurso[$cursoId] = array_merge($promediosPorCurso[$cursoId], ['grade_extension' => null]);
            }
        }

        return view('academic.note.create', compact('period', 'studentsGrade', 'student', 'nextEstudiante', 'previousEstudiante', 'courses', 'competenciasPorCurso', 'class_room', 'promediosPorCurso', 'courseFailed'));
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
                'prom_grade_b_1' => $this->calcularPromedio('grade_b_1', $competencias),
                'prom_grade_b_2' => $this->calcularPromedio('grade_b_2', $competencias),
                'prom_grade_b_3' => $this->calcularPromedio('grade_b_3', $competencias),
                'prom_grade_b_4' => $this->calcularPromedio('grade_b_4', $competencias),
                'promedio_final_course_en_letra' => $this->convertirPromedioALetras(
                    ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                    ) / 4
                ),
                'promedio_final_course' => ($this->calcularPromedio('grade_b_1', $competencias) + $this->calcularPromedio('grade_b_2', $competencias) + $this->calcularPromedio('grade_b_3', $competencias) + $this->calcularPromedio('grade_b_4', $competencias)
                ) / 4,
            ];
        }

        $cursosAprobados = 0;
        $cursosJaladosCount = 0;
        $cursosJalados = [];
        $prom_nota_1 = 0;
        $prom_nota_2 = 0;
        $prom_nota_3 = 0;
        $prom_nota_4 = 0;

        foreach ($promediosPorCurso as $cursoId => $promedios) {
            $promedioFinal = $promedios['promedio_final_course_en_letra'];
            $prom_nota_1 = $promedios['prom_grade_b_1'];
            $prom_nota_2 = $promedios['prom_grade_b_2'];
            $prom_nota_3 = $promedios['prom_grade_b_3'];
            $prom_nota_4 = $promedios['prom_grade_b_4'];

            // Verificar si el curso tiene una calificación de "A" o "AD"
            if ($promedioFinal === 'A' || $promedioFinal === 'AD') {
                $cursosAprobados++;
            }

            if ($promedioFinal === 'C') {
                $cursosJaladosCount++;
                $cursosJalados[] = $cursoId;
            }
        }

        if ($prom_nota_1 > 0 && $prom_nota_2 > 0 && $prom_nota_3 > 0 && $prom_nota_4 > 0 && $cursosJaladosCount == 0) {
            $period = AcademicPeriod::find($period_id);
            $nextPeriod = AcademicPeriod::where('year', ($period->year + 1))->first();
            $student = Student::find($student_id);

            $student_classroom_r = StudentClassroom::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->first();
            $student_classroom_r->grade_extension = 'PROMOVIDO';
            $student_classroom_r->save();

            $school_registration_r = SchoolRegistration::where('IsDeleted', false)->where('TenantId', $period->id)->where('student_id', $student->id)->first();
            $school_registration_r->status = 'PROMOVIDO';
            $school_registration_r->save();

            $promoted_student = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->join('grades as g', 'g.id', 'cr.grade_id')
            ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.student_id', $student->id)
                ->select('student_classroom.*', 'g.id as grade_id')
                ->first();

            $promoted_classroom = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
            ->join('sections as s', 's.id', 'class_rooms.section_id')
            ->where('class_rooms.TenantId', $period->id)
                ->where('class_rooms.IsDeleted', false)
                ->where('g.id', $promoted_student->grade_id + 1)
                ->select('class_rooms.*', 's.description as section_name', 'g.description as grade_description')
                ->first();

            $studentExists = Student::where('TenantId', $nextPeriod->id)->where('dni', $student->dni)->exists();

            if ($studentExists == false) {
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
                    'CreatorUserId' => 1,
                    'TenantId' => $nextPeriod->id,
                ]);

                $newStudent->save();

                $classroom = ClassRoom::where('description', $promoted_classroom->grade_description . ' ' . $promoted_classroom->section_name)
                    ->where('TenantId', $nextPeriod->id)
                    ->where('IsDeleted', false)
                    ->first();

                SchoolRegistration::create([
                    'CreatorUserId' => 1,
                    'TenantId' => $nextPeriod->id,
                    'student_id' => $newStudent->id,
                    'classroom_id' => $classroom->id,
                    'year' => $nextPeriod->year,
                    'status' => 'CONTINUA'
                ]);
            }
        }

        // Verificar si se aprobaron al menos 4 cursos
        $count_courses = Course::where('IsDeleted', false)->where('TenantId', $period_id)->count();
        $student_classroom = StudentClassroom::where('TenantId', $period_id)->where('classroom_id', $classroom_id)->where('student_id', $student_id)->first();
        $school_registration = SchoolRegistration::where('IsDeleted', false)->where('TenantId', $period_id)->where('student_id', $student_id)->first();
        if ($prom_nota_1 != 0 && $prom_nota_2 != 0 && $prom_nota_3 != 0 && $prom_nota_4 != 0) {
            if ($cursosAprobados >= ($count_courses / 2)) {
                $student_classroom->grade_final = 'PROMOVIDO';
                $student_classroom->save();

                $school_registration->status = 'PROMOVIDO';
                $school_registration->save();

                if (count($cursosJalados) > 0) {
                    $school_registration->status = 'RECUPERACION';
                    $school_registration->save();
                }
            } else {
                $student_classroom->grade_final = 'PERMANENTE';
                $student_classroom->save();

                $school_registration->status = 'PERMANENTE';
                $school_registration->save();
            }
        }

        if ($prom_nota_1 > 0 && $prom_nota_2 > 0 && $prom_nota_3 > 0 && $prom_nota_4 > 0) {
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
        }

        if ($request->has('nota_recuperación')) {
            $failedCoursesCount = 0;
            for ($i = 0; $i < count($request->nota_recuperación); $i++) {
                list($course_id, $note) = explode('_', $request->nota_recuperación[$i]);

                $failedCourse = FailedCourse::where('classroom_id', $classroom_id)
                    ->where('student_id', $student_id)
                    ->where('course_id', $course_id)
                    ->first();

                $failedCourse->grade_extension = $note;
                $failedCourse->save();

                if ($note == 'C') {
                    $failedCoursesCount++;
                }
            }

            if ($failedCoursesCount > 0) {
                $period = AcademicPeriod::find($period_id);
                $nextPeriod = AcademicPeriod::where('year', ($period->year + 1))->first();
                $student = Student::find($student_id);

                $student_classroom_r = StudentClassroom::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->first();
                $student_classroom_r->grade_extension = 'PERMANENTE';
                $student_classroom_r->save();

                $school_registration_r = SchoolRegistration::where('IsDeleted', false)->where('TenantId', $period->id)->where('student_id', $student->id)->first();
                $school_registration_r->status = 'PERMANENTE';
                $school_registration_r->save();

                $promoted_student = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
                    ->join('grades as g', 'g.id', 'cr.grade_id')
                    ->where('student_classroom.TenantId', $period->id)
                    ->where('student_classroom.student_id', $student->id)
                    ->select('student_classroom.*', 'g.id as grade_id')
                    ->first();

                $promoted_classroom = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
                    ->join('sections as s', 's.id', 'class_rooms.section_id')
                    ->where('class_rooms.TenantId', $period->id)
                    ->where('class_rooms.IsDeleted', false)
                    ->where('g.id', $promoted_student->grade_id)
                    ->select('class_rooms.*', 's.description as section_name', 'g.description as grade_description')
                    ->first();

                $studentExists = Student::where('TenantId', $nextPeriod->id)->where('dni', $student->dni)->exists();

                if ($studentExists == false) {
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
                        'CreatorUserId' => 1,
                        'TenantId' => $nextPeriod->id,
                    ]);

                    $newStudent->save();

                    $classroom = ClassRoom::where('description', $promoted_classroom->grade_description . ' ' . $promoted_classroom->section_name)
                        ->where('TenantId', $nextPeriod->id)
                        ->where('IsDeleted', false)
                        ->first();

                    SchoolRegistration::create([
                        'CreatorUserId' => 1,
                        'TenantId' => $nextPeriod->id,
                        'student_id' => $newStudent->id,
                        'classroom_id' => $classroom->id,
                        'year' => $nextPeriod->year,
                        'status' => 'CONTINUA'
                    ]);
                }
            }else{
                $period = AcademicPeriod::find($period_id);
                $nextPeriod = AcademicPeriod::where('year', ($period->year + 1))->first();
                $student = Student::find($student_id);

                $student_classroom_r = StudentClassroom::where('TenantId', $period->id)->where('classroom_id', $classroom_id)->where('student_id', $student->id)->first();
                $student_classroom_r->grade_extension = 'PROMOVIDO';
                $student_classroom_r->save();

                $school_registration_r = SchoolRegistration::where('IsDeleted', false)->where('TenantId', $period->id)->where('student_id', $student->id)->first();
                $school_registration_r->status = 'PROMOVIDO';
                $school_registration_r->save();

                $promoted_student = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
                ->join('grades as g', 'g.id', 'cr.grade_id')
                ->where('student_classroom.TenantId', $period->id)
                ->where('student_classroom.student_id', $student->id)
                ->select('student_classroom.*', 'g.id as grade_id')
                ->first();

                $promoted_classroom = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
                ->join('sections as s', 's.id', 'class_rooms.section_id')
                ->where('class_rooms.TenantId', $period->id)
                ->where('class_rooms.IsDeleted', false)
                ->where('g.id', $promoted_student->grade_id + 1)
                ->select('class_rooms.*', 's.description as section_name', 'g.description as grade_description')
                ->first();

                $studentExists = Student::where('TenantId', $nextPeriod->id)->where('dni', $student->dni)->exists();

                if ($studentExists == false) {
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
                        'CreatorUserId' => 1,
                        'TenantId' => $nextPeriod->id,
                    ]);

                    $newStudent->save();

                    $classroom = ClassRoom::where('description', $promoted_classroom->grade_description . ' ' . $promoted_classroom->section_name)
                        ->where('TenantId', $nextPeriod->id)
                        ->where('IsDeleted', false)
                        ->first();

                    SchoolRegistration::create([
                        'CreatorUserId' => 1,
                        'TenantId' => $nextPeriod->id,
                        'student_id' => $newStudent->id,
                        'classroom_id' => $classroom->id,
                        'year' => $nextPeriod->year,
                        'status' => 'CONTINUA'
                    ]);
                }
            }
        }

        return back();
    }
}
