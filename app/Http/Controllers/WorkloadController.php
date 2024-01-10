<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\GradeSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class WorkloadController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.course')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.delete')->only(['delete']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.assign')->only(['assign']);
        $this->academic_period = View::shared('academic_period');
    }

    public function teacher()
    {
        return view('academic.workload.teacher');
    }

    public function getAll()
    {
        $teachers = Teacher::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();

        $data = [];

        foreach ($teachers as $teacher) {
            if ($teacher->type == 'GENERAL') {
                $data[] = [
                    'id' => $teacher->id,
                    'name' => $teacher->first_name . ' ' . $teacher->surname,
                    'type' => $teacher->type,
                    'classrooms' => $teacher->teacherClassrooms->pluck('classroom.description')
                ];
            } else {
                $data[] = [
                    'id' => $teacher->id,
                    'name' => $teacher->first_name . ' ' . $teacher->surname,
                    'type' => $teacher->type,
                    'courses' => $teacher->teacherCourses->pluck('course.description')
                ];
            }
        }

        $count = count($teachers);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'teachers' => $data
        ]);
    }

    public function student()
    {
        $schedule = $this->generateSchedule();
        $courses = Course::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        return view('academic.workload.student', compact('schedule', 'courses'));
    }

    private function generateSchedule()
    {
        $schedule = [];
        $startHour = 7;
        $endHour = 14;
        $recessStart = 10.5;
        $recessEnd = 11;

        // Obtener datos de la base de datos (por ejemplo, todos los registros de la tabla 'schedules' con su relación 'course')
        $databaseSchedules = GradeSchedule::with('course')->get();

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $row = [
                'time' => sprintf('%02d:30 - %02d:30', $hour, $hour + 1),
                'days' => [],
            ];

            for ($day = 1; $day <= 5; $day++) {

                $class = '';
                $content = '';

                // Buscar en los registros de la base de datos
                $matchingSchedule = $databaseSchedules->first(function ($item) use ($hour, $day) {
                    // Usar una tolerancia de 0.5 para la comparación de horas (redondeando a la mitad)
                    return $item->day == $day && $item->hour == sprintf('%02d:30 - %02d:30', $hour, $hour + 1);
                });


                // Verificar si el bloque está ocupado
                if ($matchingSchedule) {
                    $class = 'occupied';
                    $content = $matchingSchedule->course->description; // Aquí asumí que la relación se llama 'course' y el atributo con el nombre del curso es 'name'
                } else {
                    // Check if it's recess time
                    if ($hour + 0.5 >= $recessStart && $hour < $recessEnd) {
                        $class = 'recess';
                        $content = 'Recreo';
                    } else {
                        $class = '';
                        $content = 'Clase';
                    }
                }

                $row['days'][] = ['class' => $class, 'content' => $content, 'day' => $day];
            }

            $schedule[] = $row;
        }

        return $schedule;
    }



    public function saveSchedule(Request $request)
    {

        $courseId = $request->input('course_id');
        $selectedBlocks = json_decode($request->input('selected_blocks'), true);

        foreach ($selectedBlocks as $block) {
            // Guardar en la base de datos, por ejemplo:
            GradeSchedule::create([
                'grade_id' => 1,
                'course_id' => $courseId,
                'hour' => $block['hour'],
                'day' => $block['day'],
            ]);
        }

        return back();

        // return response()->json([
        //     'message' => 'Información guardada correctamente.',
        // ]);
    }
}
