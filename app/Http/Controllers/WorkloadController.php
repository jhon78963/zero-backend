<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\ClassRoomSchedule;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use App\Models\TeacherCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class WorkloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.course')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.delete')->only(['delete']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.assign')->only(['assign']);
    }

    public function teacher($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $classrooms = ClassRoom::where('status', false)->where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $courses = Course::where('type', 'AREA')->where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $assignCourses = TeacherCourse::where('TenantId', $period->id)->get();

        return view('academic.workload.teacher', compact('courses', 'classrooms', 'assignCourses', 'period'));
    }

    public function assignClassroomTeacher(Request $request, $period_id)
    {
        $lastClassroom = TeacherClassroom::where('teacher_id', $request->teacher_id)
            ->where('TenantId', $period_id)
            ->first();

        if ($lastClassroom) {
            $classRoom = ClassRoom::findOrFail($lastClassroom->classroom_id);
            $classRoom->status = false;
            $classRoom->save();

            $lastClassroom->delete();
        }

        $assignClassroom = new TeacherClassroom();
        $assignClassroom->CreatorUserId = Auth::id();
        $assignClassroom->TenantId = $period_id;
        $assignClassroom->teacher_id = $request->teacher_id;
        $assignClassroom->classroom_id = $request->classroom_id;
        $assignClassroom->save();

        if ($assignClassroom) {
            $classRoom = ClassRoom::findOrFail($assignClassroom->classroom_id);
            $classRoom->status = true;
            $classRoom->save();
        }

        return back();
    }

    public function assignCourseTeacher(Request $request, $period_id)
    {
        TeacherCourse::where('teacher_id', $request->teacher_id)
            ->where('TenantId', $period_id)
            ->delete();

        foreach ($request->course_id as $course) {
            $assignCourse = new TeacherCourse();
            $assignCourse->CreatorUserId = Auth::id();
            $assignCourse->TenantId = $period_id;
            $assignCourse->teacher_id = $request->teacher_id;
            $assignCourse->course_id = $course;
            $assignCourse->save();
        }

        return back();
    }

    public function getAll($period_id)
    {
        $teachers = Teacher::where('IsDeleted', false)->where('TenantId', $period_id)->get();

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

    public function index(Request $request, $period_name)
    {
        $classroom_id = $request->input('classroom_id');
        $period = AcademicPeriod::where('name', $period_name)->first();
        $request_classroom = ClassRoom::where('id', $classroom_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->select('description')
            ->first();

        $schedule = $this->generateSchedule($classroom_id, $period->id);
        $courses = Course::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $classrooms = ClassRoom::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        return view('academic.workload.index', compact('schedule', 'courses', 'classrooms', 'classroom_id', 'request_classroom', 'period'));
    }

    private function generateSchedule($classroom_id, $period_id)
    {
        $schedule = [];
        $startHour = 7;
        $endHour = 14;
        $recessStart = 10.5;
        $recessEnd = 11;

        $databaseSchedules = ClassRoomSchedule::with('course')->where('classroom_id', $classroom_id)->where('TenantId', $period_id)->get();

        //return dd($databaseSchedules);

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $row = [
                'time' => sprintf('%02d:30 - %02d:30', $hour, $hour + 1),
                'days' => [],
            ];

            for ($day = 1; $day <= 5; $day++) {

                $class = '';
                $content = '';

                $matchingSchedule = $databaseSchedules->first(function ($item) use ($hour, $day) {
                    return $item->day == $day && $item->hour == sprintf('%02d:30 - %02d:30', $hour, $hour + 1);
                });


                // Verificar si el bloque está ocupado
                if ($matchingSchedule) {
                    $class = 'occupied';
                    $content = $matchingSchedule->course->description;
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

    public function saveSchedule(Request $request, $period_id)
    {
        $courseId = $request->input('course_id');
        $classroomId = $request->input('classroom_id');
        $selectedBlocks = json_decode($request->input('selected_blocks'), true);

        foreach ($selectedBlocks as $block) {
            // Guardar en la base de datos, por ejemplo:
            ClassRoomSchedule::create([
                'classroom_id' => $classroomId,
                'course_id' => $courseId,
                'hour' => $block['hour'],
                'day' => $block['day'],
                'TenantId' => $period_id
            ]);
        }

        return back();

        // return response()->json([
        //     'message' => 'Información guardada correctamente.',
        // ]);
    }

    public function scheduleTeacher($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $teacher_email = Auth::user()->email;
        $teacher = Teacher::where('institutional_email', $teacher_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->first();

        $class_room = TeacherClassroom::where('teacher_id', $teacher->id)
            ->where('TenantId', $period->id)
            ->first();

        $classroom_id = $class_room->classroom_id;
        $request_classroom = ClassRoom::where('id', $classroom_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->select('description')
            ->first();

        $schedule = $this->generateSchedule($classroom_id, $period->id);
        $courses = Course::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $classrooms = ClassRoom::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        return view('academic.workload.user.teacher', compact('schedule', 'courses', 'classrooms', 'classroom_id', 'request_classroom', 'period'));
    }

    public function scheduleStudent($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student_email = Auth::user()->email;
        $student = Student::where('institutional_email', $student_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->first();

        $class_room = StudentClassroom::where('student_id', $student->id)
            ->where('TenantId', $period->id)
            ->first();

        $classroom_id = $class_room->classroom_id;
        $request_classroom = ClassRoom::where('id', $classroom_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->select('description')
            ->first();

        $schedule = $this->generateSchedule($classroom_id, $period->id);
        $courses = Course::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        $classrooms = ClassRoom::where('IsDeleted', false)->where('TenantId', $period->id)->get();
        return view('academic.workload.user.teacher', compact('schedule', 'courses', 'classrooms', 'classroom_id', 'request_classroom', 'period'));
    }
}
