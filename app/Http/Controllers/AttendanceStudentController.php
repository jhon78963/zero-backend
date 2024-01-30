<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\AttendanceDetail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('check.permissions:Docente,pages.silabus')->only(['index']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student_email = Auth::user()->email;
        $student = Student::where('institutional_email', $student_email)
            ->where('IsDeleted', false)
            ->where('TenantId', $period->id)
            ->first();

        $attendances = AttendanceDetail::where('TenantId', $period->id)
            ->where('student_id', $student->id)
            ->get();

        return view('academic.attendance.student.index', compact('attendances', 'period'));
    }
}
