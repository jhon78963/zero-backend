<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SectionController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.section')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.section.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.section.delete')->only(['delete']);
        $this->academic_period = View::shared('academic_period');
    }

    public function create(Request $request, $period_id)
    {
        if ($request->input('description') == null) {
            return response()->json([
                'status' => 'error',
                'message' => '¡La sección es requerida!'
            ], 400);
        }

        $sectionExists = Section::where('description', $request->input('description'))
            ->where('IsDeleted', false)
            ->where('TenantId', $period_id)
            ->exists();

        if ($sectionExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section already exist'
            ], 400);
        }

        $section = new Section([
            'description' => $request->input('description'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $section->save();

        $count = Section::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'section' => $section,
            'count' => $count,
        ], 201);
    }

    public function getAll($period_id)
    {
        $sections = Section::where('IsDeleted', false)->where('TenantId', $period_id)->get();
        $count = count($sections);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'sections' => $sections
        ]);
    }
}
