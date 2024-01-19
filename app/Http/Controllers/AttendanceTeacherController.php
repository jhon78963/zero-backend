<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceTeacherController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('check.permissions:Docente,pages.silabus')->only(['index']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        $attendances = Attendance::where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->get();

        $generateQr = QrCode::generate('Make me into a QrCode!');

        return view('academic.attendance.teacher.index', compact('attendances'));
    }

    public function create()
    {
        $fecha = now()->format('Y-m-d');
        $url = route('attendance.student.create', [$this->academic_period->name, $fecha]);
        $generateQr = QrCode::generate($url);
        $studentAttendances = AttendanceDetail::where('TenantId', $this->academic_period->id)->get();
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

        return view('academic.attendance.teacher.create', compact('generateQr', 'fecha', 'studentAttendances', 'attendance'));
    }

    public function registerAttendance($periodo, $fecha)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

        if ($attendance != [] && $attendance->status == true) {
            $student_email = Auth::user()->email;
            $student = Student::where('institutional_email', $student_email)
                ->where('IsDeleted', false)
                ->where('TenantId', $this->academic_period->id)
                ->first();

            $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

            $attendanceCheck = AttendanceDetail::where('TenantId', $this->academic_period->id)
                ->where('student_id', $student->id)
                ->where('attendance_id', $attendance->id)
                ->exists();

            return view('academic.attendance.teacher.attendance', compact('fecha', 'attendanceCheck'));
        } else {
            return abort(404);
        }
    }

    public function disableToken($fecha)
    {
        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();
        $attendance->status = false;
        $attendance->save();

        return redirect()->back();
    }

    public function enableToken()
    {
        $teacher_email = Auth::user()->email;
        $teacher = Teacher::where('institutional_email', $teacher_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        $class_room = TeacherClassroom::where('teacher_id', $teacher->id)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        $attendance = new Attendance();
        $attendance->date = now()->format('Y-m-d');
        $attendance->teacher_id = $teacher->id;
        $attendance->classroom_id = $class_room->id;
        $attendance->TenantId = $this->academic_period->id;
        $attendance->save();

        return redirect()->back();
    }

    public function mark($fecha)
    {
        $student_email = Auth::user()->email;
        $student = Student::where('institutional_email', $student_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

        $attendanceCheck = AttendanceDetail::where('TenantId', $this->academic_period->id)
            ->where('student_id', $student->id)
            ->where('attendance_id', $attendance->id)
            ->exists();


        if ($attendanceCheck == false) {
            $attendance_detail = new AttendanceDetail();
            $attendance_detail->status = 'PRESENTE';
            $attendance_detail->student_id = $student->id;
            $attendance_detail->attendance_id = $attendance->id;
            $attendance_detail->TenantId = $this->academic_period->id;
            $attendance_detail->save();
        }

        return redirect()->back();
    }

    public function change($fecha, $student_id)
    {
        $student = Student::where('id', $student_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        $attendance = Attendance::where('date', $fecha)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

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
