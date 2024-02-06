<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\CourseGrade;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.course')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.delete')->only(['delete']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.assign')->only(['assign']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('academic.course.index', compact('period'));
    }

    public function assign(Request $request, $period_id)
    {
        $courseExists = Course::where('id', $request->course_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->exists();

        if (!$courseExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El curso no existe!'
            ], 400);
        }

        CourseGrade::where('course_id', $request->course_id)
            ->where('TenantId', $period_id)
            ->delete();

        $grades = $request->grades;

        foreach ($grades as $grade) {
            $grade_save = new CourseGrade([
                'course_id' => $request->input('course_id'),
                'grade_id' => $grade,
                'CreatorUserId' => Auth::id(),
                'TenantId' => $period_id,
            ]);

            $grade_save->save();
        }

        $course = Course::where('id', $request->course_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $course->courseGrades->pluck('grade.description');

        $position = Course::where('id', '<=', $course->id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'position' => $position,
        ], 201);
    }

    public function create(Request $request, $period_id)
    {
        $courseExists = Course::where('description', $request->input('description'))
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->exists();

        if ($courseExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El curso ya existe!'
            ], 400);
        }

        $course = new Course([
            'description' => $request->input('description'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $course->save();

        $course->courseGrades->pluck('grade.description');

        $count = Course::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'count' => $count,
        ], 201);
    }

    public function delete($period_id, $id)
    {
        $course = Course::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($course)) {
            return response()->json([
                'status' => 'error',
                'message' => 'El curso no existe'
            ], 404);
        }

        $course->IsDeleted = true;
        $course->DeleterUserId = Auth::id();
        $course->DeletionTime = now()->format('Y-m-d H:i:s');
        $course->save();

        $count = Course::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'count' => $count
        ]);
    }

    public function get($period_id, $id)
    {
        $course = Course::where('id', $id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->first();

        $grades = $course->courseGrades->pluck('grade.description');
        $grade_id = $course->courseGrades->pluck('grade.id');

        $data = [
            'id' => $course->id,
            'description' => $course->description,
            'grades' => $grades,
            'grade_id' => $grade_id,
        ];

        return response()->json([
            'status' => 'success',
            'course' => $data
        ]);
    }

    public function getAll($period_id)
    {
        $courses = Course::where('IsDeleted', false)->where('TenantId', $period_id)->get();

        $data = [];

        foreach ($courses as $course) {
            $data[] = [
                'id' => $course->id,
                'description' => $course->description,
                'grades' => $course->courseGrades->pluck('grade.description')
            ];
        }

        $count = count($courses);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'courses' => $data
        ]);
    }

    public function update(Request $request, $period_id, $id)
    {
        $courseIdExists = Course::where('id', $id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->exists();

        if (!$courseIdExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El curso no existe!'
            ], 400);
        }

        $coursesExists = Course::where('description', $request->input('description'))
            ->where('id', '!=', $id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->exists();

        if ($coursesExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El curso ya existe!'
            ], 400);
        }

        $course = Course::find($id);

        $course->description = $request->input('description');
        $course->LastModificationTime = now()->format('Y-m-d H:i:s');
        $course->LastModifierUserId = Auth::id();
        $course->save();

        $course->courseGrades->pluck('grade.description');

        $position = Course::where('id', '<=', $course->id)
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'position' => $position,
        ], 200);
    }
}
