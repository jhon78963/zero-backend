<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GradeController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.delete')->only(['delete']);
        $this->academic_period = View::shared('academic_period');
    }

    public function create(Request $request)
    {
        if ($request->input('description') == null) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El grado es requerido!'
            ], 400);
        }

        $gradeExists = Grade::where('description', $request->input('description'))
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->exists();

        if ($gradeExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El grado ya existe!'
            ], 400);
        }

        $grade = new Grade([
            'description' => $request->input('description'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);

        $grade->save();

        $count = Grade::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'grade' => $grade,
            'count' => $count,
        ], 201);
    }

    public function getAll()
    {
        $grades = Grade::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $count = count($grades);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'grades' => $grades
        ]);
    }

    public function update(Request $request, $id)
    {
        $grade = Grade::where('id', $id)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

        if (empty($grade)) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El grado no existe!'
            ], 404);
        }

        if ($grade->description == $request->input('description')) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El grado ya existe!'
            ], 400);
        }

        $grade->description = $request->input('description');
        $grade->LastModificationTime = now()->format('Y-m-d H:i:s');
        $grade->LastModifierUserId = Auth::id();
        $grade->save();

        return response()->json([
            'status' => 'success',
            'grade' => $grade
        ]);
    }
}
