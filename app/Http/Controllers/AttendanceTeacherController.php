<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('check.permissions:Docente,pages.silabus')->only(['index']);
    }

    public function index(Request $request, $period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        // $classrooms = ClassRoom::where('TenantId', $period->id)->where('IsDeleted', false)->get();

        $today = now()->format('Y-m-d');
        $attendances = AttendanceDetail::join('classroom_attendances as ca', 'classroom_student_attendance.attendance_id', 'ca.id')
            ->join('students as s', 's.id', 'classroom_student_attendance.student_id')
            ->where('ca.TenantId', $period->id);

        if ($request->classroom_id != null) {
            $attendances->where('ca.classroom_id', $request->classroom_id);
        }

        if ($request->date != null) {
            $attendances->where(DB::raw("DATE_FORMAT(classroom_student_attendance.CreationTime, '%Y-%m-%d')"), $request->date);
            $today = $request->date;
        } else {
            $attendances->where(DB::raw("DATE_FORMAT(classroom_student_attendance.CreationTime, '%Y-%m-%d')"), $today);
        }

        $attendances = $attendances->select('classroom_student_attendance.*', 'ca.classroom_id', 's.first_name', 's.other_names', 's.surname', 's.mother_surname')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
            ->get();

        $teacher_email = Auth::user()->email;
        $teacher = Teacher::where('institutional_email', $teacher_email)->first();

        $classrooms = TeacherClassroom::join('class_rooms as cs', 'cs.id', 'teacher_classrooms.classroom_id')
            ->where('cs.TenantId', $period->id)
            ->where('teacher_classrooms.teacher_id', $teacher->id)
            ->where('cs.IsDeleted', false)
            ->select('cs.*')
            ->get();

        if ($request->classroom_id != null) {
            $classroomSelected = TeacherClassroom::join('class_rooms as cs', 'cs.id', 'teacher_classrooms.classroom_id')
                ->where('cs.TenantId', $period->id)
                ->where('cs.IsDeleted', false)
                ->where('teacher_classrooms.teacher_id', $teacher->id)
                ->where('cs.id', $request->classroom_id)
                ->select('cs.*')
                ->first();

            return view('academic.attendance.teacher.index', compact('attendances', 'period', 'classrooms', 'classroomSelected', 'today'));
        } else {
            $classroomSelected = TeacherClassroom::join('class_rooms as cs', 'cs.id', 'teacher_classrooms.classroom_id')
                ->where('cs.TenantId', $period->id)
                ->where('cs.IsDeleted', false)
                ->where('teacher_classrooms.teacher_id', $teacher->id)
                ->where('cs.id', 1)
                ->select('cs.*')
                ->first();

            return view('academic.attendance.teacher.index', compact('attendances', 'period', 'classrooms', 'classroomSelected', 'today'));
        }
    }

    public function createTeacherAttendance($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
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

        return view('academic.attendance.teacher.create', compact('generateQr', 'fecha', 'today', 'studentAttendances', 'attendance', 'period'));
    }

    public function createStudentAttendance($period_name, $fecha)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period->id)->first();

        if ($attendance != [] && $attendance->status == true) {
            $student_email = Auth::user()->email;
            $student = Student::where('institutional_email', $student_email)
                ->where('IsDeleted', false)
                ->where('TenantId', $period->id)
                ->first();

            $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period->id)->first();

            $attendanceCheck = AttendanceDetail::where('TenantId', $period->id)
                ->where('student_id', $student->id)
                ->where('attendance_id', $attendance->id)
                ->exists();

            return view('academic.attendance.student.attendance', compact('fecha', 'attendanceCheck', 'period'));
        } else {
            return abort(404);
        }
    }

    public function closeAttendance($period_id, $fecha)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period_id)->first();
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
            $newAttendanceDetail->TenantId = $period_id;
            $newAttendanceDetail->status = 'FALTA';
            $newAttendanceDetail->student_id = $studentId;
            $newAttendanceDetail->attendance_id = $attendance->id;
            $newAttendanceDetail->save();
        }

        return redirect()->back();
    }

    public function openAttendance($period_id)
    {
        $teacher_email = Auth::user()->email;
        $teacher = Teacher::where('institutional_email', $teacher_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $class_room = TeacherClassroom::where('teacher_id', $teacher->id)
            ->where('TenantId', $period_id)
            ->first();

        $attendance = new Attendance();
        $attendance->date = now()->format('Y-m-d');
        $attendance->teacher_id = $teacher->id;
        $attendance->classroom_id = $class_room->classroom_id;
        $attendance->TenantId = $period_id;
        $attendance->save();

        $classroom_students = StudentClassroom::where('classroom_id', $attendance->classroom_id)->get();

        foreach ($classroom_students as $classroom_student) {
            $attendance_detail = new AttendanceDetail();
            $attendance_detail->CreatorUserId = Auth::id();
            $attendance_detail->TenantId = $period_id;
            $attendance_detail->status = 'FALTA';
            $attendance_detail->student_id = $classroom_student->student_id;
            $attendance_detail->attendance_id = $attendance->id;
            $attendance_detail->save();
        }

        return redirect()->back();
    }

    public function mark($period_id, $fecha)
    {
        $student_email = Auth::user()->email;
        $student = Student::where('institutional_email', $student_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        $attendanceCheck = AttendanceDetail::where('TenantId', $period_id)
            ->where('student_id', $student->id)
            ->where('attendance_id', $attendance->id)
            ->exists();


        if ($attendanceCheck == false) {
            $attendance_detail = new AttendanceDetail();
            $attendance_detail->status = 'PRESENTE';
            $attendance_detail->student_id = $student->id;
            $attendance_detail->attendance_id = $attendance->id;
            $attendance_detail->TenantId = $period_id;
            $attendance_detail->save();
        }

        return redirect()->back();
    }

    public function changePresent($period_id, $fecha, $student_id)
    {
        $student = Student::where('id', $student_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        $attendanceDetail = AttendanceDetail::where('student_id', $student->id)->where('attendance_id', $attendance->id)->first();
        $attendanceDetail->status = 'PRESENTE';
        $attendanceDetail->save();

        return back();
    }
    public function changeMissing($period_id, $fecha, $student_id)
    {
        $student = Student::where('id', $student_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        $attendanceDetail = AttendanceDetail::where('student_id', $student->id)->where('attendance_id', $attendance->id)->first();
        $attendanceDetail->status = 'FALTA';
        $attendanceDetail->save();

        return back();
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
