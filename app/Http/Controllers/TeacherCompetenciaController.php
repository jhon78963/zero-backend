<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassRoom;
use App\Models\StudentClassroom;
use App\Models\StudentCompetencia;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherCompetenciaController extends Controller
{
    public function index($period_name)
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

        $room = ClassRoom::where('id', $class_room->classroom_id)->first();

        $classroom_students = StudentClassroom::where('classroom_id', $class_room->classroom_id)->get();
        //$studentsGrade = StudentCompetencia::where('TenantId', $period->id)->where('classroom_id', $class_room->classroom_id)->get();
        return view('academic.note.index', compact('period', 'classroom_students', 'room'));
    }
}
