<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\UserRole;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
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

        $registrationClassrooms = ClassRoom::where('TenantId', $period->id)
            ->where('IsDeleted', false)
            ->groupBy('description', 'students_number', 'id')
            ->selectRaw('description, students_number as registration, id as classroom_id')
            ->get();

        $registrationGrades = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
            ->where('class_rooms.TenantId', $period->id)
            ->where('class_rooms.IsDeleted', false)
            ->groupBy('g.description', 'g.id')
            ->selectRaw('g.description, SUM(class_rooms.students_number) as registration, g.id as grade_id')
            ->get();

        // $coursesFinalStatus = CourseGrade::join('courses as c', 'c.id', 'course_grades.course_id')
        //     ->join('grades as g', 'g.id', 'course_grades.grade_id')
        //     ->where('course_grades.TenantId', $period->id)
        //     ->select('c.description as course', 'g.description as grade')
        //     ->get();

        return view('reports.index', compact('period', 'students', 'roles', 'studentByGrade', 'limitClassrooms', 'registrationClassrooms', 'registrationGrades'));
    }

    public function generateClassroomPDF($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $registrationClassrooms = ClassRoom::where('TenantId', $period->id)
            ->where('IsDeleted', false)
            ->groupBy('description', 'students_number')
            ->selectRaw('description, students_number as registration')
            ->get();

        $pdf = DomPDF::loadView('reports.classroom-pdf', compact('period', 'registrationClassrooms'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-aulas.pdf');
    }

    public function generateGradePDF($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $registrationGrades = ClassRoom::join('grades as g', 'g.id', 'class_rooms.grade_id')
            ->where('class_rooms.TenantId', $period->id)
            ->where('class_rooms.IsDeleted', false)
            ->groupBy('g.description')
            ->selectRaw('g.description, SUM(class_rooms.students_number) as registration')
            ->get();

        $pdf = DomPDF::loadView('reports.grade-pdf', compact('period', 'registrationGrades'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-grados.pdf');
    }

    public function generateRegistrationByClassroomPDF($period_name, $classroom_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $classroom = ClassRoom::find($classroom_id);
        $registrationClassrooms = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
            ->where('student_classroom.TenantId', $period->id)
            ->where('student_classroom.classroom_id', $classroom_id)
            ->where('s.TenantId', $period->id)
            ->where('s.IsDeleted', false)
            ->where('s.status', '1')
            ->select('s.*')
            ->get();

        $pdf = DomPDF::loadView('reports.registration-classroom-pdf', compact('period', 'classroom', 'registrationClassrooms'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-matriculados-por-aula.pdf');
    }

    public function generateRegistrationByGradePDF($period_name, $grade_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $grade = Grade::find($grade_id);
        $registrationGrades = StudentClassroom::join('students as s', 's.id', 'student_classroom.student_id')
            ->join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->join('grades as g', 'g.id', 'cr.grade_id')
            ->where('student_classroom.TenantId', $period->id)
            ->where('g.id', $grade_id)
            ->where('s.TenantId', $period->id)
            ->where('s.IsDeleted', false)
            ->where('s.status', '1')
            ->select('s.*')
            ->get();

        $pdf = DomPDF::loadView('reports.registration-grade-pdf', compact('period', 'grade', 'registrationGrades'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-matriculados-por-aula.pdf');
    }
}
