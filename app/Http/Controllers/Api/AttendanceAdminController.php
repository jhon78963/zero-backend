<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceAdminController extends Controller
{
    public $period_name = 'pa-2024';
    public $period_id = 1;

    public function index($classroom_id, $date)
    {
        $period = AcademicPeriod::where('name', $this->period_name)->first();
        $classrooms = ClassRoom::join('teacher_classrooms as tc', 'tc.classroom_id', 'class_rooms.id')
            ->where('class_rooms.TenantId', $period->id)
            ->where('class_rooms.IsDeleted', false)
            ->select('class_rooms.*', 'tc.teacher_id')
            ->get();

        $today = now()->format('Y-m-d');
        $attendances = AttendanceDetail::join('classroom_attendances as ca', 'classroom_student_attendance.attendance_id', 'ca.id')
            ->join('students as s', 's.id', 'classroom_student_attendance.student_id')
            ->where('ca.TenantId', $period->id);

        if ($classroom_id != null) {
            $attendances->where('ca.classroom_id', $classroom_id);
        }

        if ($date != null) {
            $attendances->where(DB::raw("DATE_FORMAT(classroom_student_attendance.CreationTime, '%Y-%m-%d')"), $date);
            $today = $date;
        } else {
            $attendances->where(DB::raw("DATE_FORMAT(classroom_student_attendance.CreationTime, '%Y-%m-%d')"), $today);
        }

        $attendances = $attendances->select('classroom_student_attendance.*', 'ca.classroom_id', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
            ->get();

        $classroomSelected = ClassRoom::join('teacher_classrooms as tc', 'tc.classroom_id', 'class_rooms.id')
            ->where('class_rooms.TenantId', $period->id)
            ->where('class_rooms.IsDeleted', false)
            ->where('class_rooms.id', $classroom_id)
            ->select('class_rooms.*', 'tc.teacher_id')
            ->first();

        return response()->json([
            'attendances' => $attendances,
            'period' => $period,
            'classrooms' => $classrooms,
            'classroomSelected' => $classroomSelected,
            'today' => $today,
        ]);
    }

    public function showMissing($classroom_id)
    {
        $period = AcademicPeriod::where('name', $this->period_name)->first();
        $classrooms = ClassRoom::where('TenantId', $period->id)->where('IsDeleted', false)->get();
        $today = now()->format('Y-m-d');
        $students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
            ->where('student_classroom.TenantId', $period->id);

        if ($classroom_id != null) {
            $students->where('student_classroom.classroom_id', $classroom_id);
        }

        $students = $students->select('student_classroom.classroom_id', 's.first_name', 's.other_names', 's.surname', 's.mother_surname', 's.id as student_id')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
            ->get();

        $classroomSelected = ClassRoom::where('TenantId', $period->id)
            ->where('IsDeleted', false)
            ->where('id', $classroom_id)
            ->first();

        return response()->json([
            'students' => $students,
            'period' => $period,
            'classrooms' => $classrooms,
            'classroomSelected' => $classroomSelected,
            'today' => $today,
        ]);
    }

    public function generatePDF($classroom_id, $student_id)
    {
        $period = AcademicPeriod::where('name', $this->period_name)->first();
        $missing = AttendanceDetail::where('TenantId', $period->id)->where('student_id', $student_id)->where('status', 'FALTA')->get();
        $student = Student::findOrFail($student_id);
        $classroom = ClassRoom::findOrFail($classroom_id);

        $pdf = DomPDF::loadView('academic.attendance.admin.pdf', compact('period', 'missing', 'student', 'classroom'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-faltas.pdf');
    }

    public function createTeacherAttendance()
    {
        $period = AcademicPeriod::where('name', $this->period_name)->first();
        $fecha = now()->format('Y-m-d');
        $today = now()->format('d-m-Y');
        $url = route('attendance.student.create', [$period->name, $fecha]);
        $generateQr = QrCode::generate($url);

        $studentAttendances = AttendanceDetail::join('classroom_attendances as ca', 'classroom_student_attendance.attendance_id', 'ca.id')
            ->join('students as s', 's.id', 'classroom_student_attendance.student_id')
            ->where('classroom_student_attendance.TenantId', $period->id)
            ->where(DB::raw("DATE(classroom_student_attendance.CreationTime)"), $fecha)
            ->select('classroom_student_attendance.*', 'ca.classroom_id', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
            ->get();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period->id)->first();

        return response()->json([
            'generateQr' => $generateQr,
            'fecha' => $fecha,
            'today' => $today,
            'studentAttendances' => $studentAttendances,
            'attendance' => $attendance,
            'period' => $period,
        ]);
    }

    public function closeAttendance($fecha)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->period_id)->first();
        $attendance->status = false;
        $attendance->save();

        $studentAttendances = AttendanceDetail::where('attendance_id', $attendance->id)
            ->pluck('student_id')
            ->toArray();

        $students = StudentClassroom::where('classroom_id', $attendance->classroom_id)
            ->pluck('student_id')
            ->toArray();



        $studentsWithoutAttendance = array_diff($students, $studentAttendances);

        // Crea registros de asistencia para los estudiantes sin asistencia
        foreach ($studentsWithoutAttendance as $studentId) {
            $newAttendanceDetail = new AttendanceDetail();
            $newAttendanceDetail->TenantId = $this->period_id;
            $newAttendanceDetail->status = 'FALTA';
            $newAttendanceDetail->student_id = $studentId;
            $newAttendanceDetail->attendance_id = $attendance->id;
            $newAttendanceDetail->save();
        }

        return redirect()->back();
    }

    public function openAttendance($teacher_id)
    {
        $teacher = Teacher::where('id', $teacher_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->period_id)
            ->first();

        $class_room = TeacherClassroom::where('teacher_id', $teacher->id)
            ->where('TenantId', $this->period_id)
            ->first();

        $attendance = new Attendance();
        $attendance->date = now()->format('Y-m-d');
        $attendance->teacher_id = $teacher->id;
        $attendance->classroom_id = $class_room->classroom_id;
        $attendance->TenantId = $this->period_id;
        $attendance->save();

        $classroom_students = StudentClassroom::where('classroom_id', $attendance->classroom_id)->get();

        foreach ($classroom_students as $classroom_student) {
            $attendance_detail = new AttendanceDetail();
            $attendance_detail->CreatorUserId = 1;
            $attendance_detail->TenantId = $this->period_id;
            $attendance_detail->status = 'FALTA';
            $attendance_detail->student_id = $classroom_student->student_id;
            $attendance_detail->attendance_id = $attendance->id;
            $attendance_detail->save();
        }

        return redirect()->back();
    }

    public function changePresent($fecha, $student_id)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->period_id)->first();

        $attendanceDetail = AttendanceDetail::where('student_id', $student_id)->where('attendance_id', $attendance->id)->first();
        $attendanceDetail->status = 'PRESENTE';
        $attendanceDetail->save();

        return response()->json([
            'message' => 'ok'
        ]);
    }
    public function changeMissing($fecha, $student_id)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->period_id)->first();

        $attendanceDetail = AttendanceDetail::where('student_id', $student_id)->where('attendance_id', $attendance->id)->first();
        $attendanceDetail->status = 'FALTA';
        $attendanceDetail->save();

        return response()->json([
            'message' => 'ok'
        ]);
    }
}
