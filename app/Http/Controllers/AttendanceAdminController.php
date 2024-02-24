<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassroom;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $classrooms = ClassRoom::where('TenantId', $period->id)->where('IsDeleted', false)->get();
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

        if ($request->classroom_id != null) {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', $request->classroom_id)
                ->first();

            return view('academic.attendance.admin.index', compact('attendances', 'period', 'classrooms', 'classroomSelected', 'today'));
        } else {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->first();

            return view('academic.attendance.admin.index', compact('attendances', 'period', 'classrooms', 'classroomSelected', 'today'));
        }
    }

    public function ahowMissing(Request $request, $period_name, $classroom_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $classrooms = ClassRoom::where('TenantId', $period->id)->where('IsDeleted', false)->get();
        $today = now()->format('Y-m-d');
        $students = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
            ->where('student_classroom.TenantId', $period->id);

        if ($request->classroom_id != null) {
            $students->where('student_classroom.classroom_id', $request->classroom_id);
        }

        $students = $students->select('student_classroom.classroom_id', 's.first_name', 's.other_names', 's.surname', 's.mother_surname', 's.id as student_id')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('s.first_name')->orderBy('s.other_names')
            ->get();

        if ($request->classroom_id != null) {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', $request->classroom_id)
                ->first();

            return view('academic.attendance.admin.show', compact('students', 'period', 'classrooms', 'classroomSelected', 'today'));
        } else {
            $classroomSelected = ClassRoom::where('TenantId', $period->id)
                ->where('IsDeleted', false)
                ->where('id', 1)
                ->first();

            return view('academic.attendance.admin.show', compact('students', 'period', 'classrooms', 'classroomSelected', 'today'));
        }
    }

    public function generatePDF($period_name, $classroom_id, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $missing = AttendanceDetail::where('TenantId', $period->id)
            ->where('student_id', $student_id)
            ->where('status', 'FALTA')
            ->select(DB::raw("DATE_FORMAT(CreationTime, '%d/%m/%Y') as date"), 'status')
            ->get();
        $student = Student::findOrFail($student_id);
        $classroom = ClassRoom::findOrFail($classroom_id);

        $pdf = DomPDF::loadView('academic.attendance.admin.pdf', compact('period', 'missing', 'student', 'classroom'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-faltas.pdf');
    }
}
