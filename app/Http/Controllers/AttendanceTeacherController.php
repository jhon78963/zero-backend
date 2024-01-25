<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Student;
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

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $attendances = Attendance::where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->get();

        $generateQr = QrCode::generate('Make me into a QrCode!');

        return view('academic.attendance.teacher.index', compact('attendances', 'period'));
    }

    public function createTeacherAttendance($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $fecha = now()->format('Y-m-d');
        $today = now()->format('d-m-Y');
        $url = route('attendance.student.create', [$period->name, $fecha]);
        $generateQr = QrCode::generate($url);
        $studentAttendances = AttendanceDetail::where('TenantId', $period->id)->where(DB::raw("DATE(CreationTime)"), $fecha)->get();
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

    public function change($period_id, $fecha, $student_id)
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
