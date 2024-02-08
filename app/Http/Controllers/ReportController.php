<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\UserRole;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $students = Student::groupBy('gender')
            ->where('TenantId', $period->id)
            ->where('IsDeleted', false)
            ->where('status', '1')
            ->selectRaw('gender, count(*) as count')
            ->get();

        $roles = UserRole::join('users as u', 'u.id', 'user_roles.userId')
            ->join('roles as r', 'r.id', 'user_roles.roleId')
            ->where('user_roles.TenantId', $period->id)
            ->where('user_roles.roleId', '!=', 4)
            ->groupBy('r.name')
            ->selectRaw('r.name, count(*) as count')
            ->get();

        $studentByGrade = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->join('grades as g', 'g.id', 'cr.grade_id')
            ->where('student_classroom.TenantId', $period->id)
            ->groupBy('g.description')
            ->selectRaw('g.description, count(*) as count')
            ->get();

        $limitClassrooms = ClassRoom::where('TenantId', $period->id)
            ->where('IsDeleted', false)
            ->groupBy('description', 'limit', 'students_number')
            ->selectRaw('description, SUM(`limit` - `students_number`) as vacante')
            ->get();

        return view('reports.index', compact('period', 'students', 'roles', 'studentByGrade', 'limitClassrooms'));
    }
}
