<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.course')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.delete')->only(['delete']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.course.assign')->only(['assign']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('academic.course.index');
    }

    public function assign($id)
    {
        //
    }

    public function create(Request $request)
    {
        $courseExists = Course::where('description', $request->input('description'))
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
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
            'TenantId' => $this->academic_period->id,
        ]);

        $course->save();

        $count = Course::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'count' => $count,
        ], 201);
    }

    public function delete($id)
    {
        $course = Course::where('id', $id)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

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

        $count = Course::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'count' => $count
        ]);
    }

    public function get($id)
    {
        $course = Course::where('id', $id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'course' => $course
        ]);
    }

    public function getAll()
    {
        $courses = Course::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();

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

    public function update(Request $request, $id)
    {
        $courseIdExists = Course::where('id', $id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
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
            ->where('TenantId', $this->academic_period->id)
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

        $position = Course::where('id', '<=', $course->id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->count();

        return response()->json([
            'status' => 'success',
            'course' => $course,
            'position' => $position,
        ], 200);
    }
}
