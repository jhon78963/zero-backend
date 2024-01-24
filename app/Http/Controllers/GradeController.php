<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.grades')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.grades.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.grades.delete')->only(['delete']);
    }

    public function create(Request $request, $period_id)
    {
        if ($request->input('description') == null) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El grado es requerido!'
            ], 400);
        }

        $gradeExists = Grade::where('description', $request->input('description'))
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
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
            'TenantId' => $period_id,
        ]);

        $grade->save();

        $count = Grade::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'grade' => $grade,
            'count' => $count,
        ], 201);
    }

    public function getAll($period_id)
    {
        $grades = Grade::where('IsDeleted', false)->where('TenantId', $period_id)->get();
        $count = count($grades);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'grades' => $grades
        ]);
    }

    public function update(Request $request, $period_id, $id)
    {
        $grade = Grade::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

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
