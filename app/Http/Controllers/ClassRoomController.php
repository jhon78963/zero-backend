<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ClassRoomController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.classroom')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.classroom.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.classroom.delete')->only(['delete']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('academic.classroom.index');
    }

    public function create(Request $request)
    {
        $classRoomExists = ClassRoom::where('grade_id', $request->input('grade_id'))
            ->where('section_id', $request->input('section_id'))
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->exists();

        if ($classRoomExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El aula seleccionada ya existe!'
            ], 400);
        }

        $grade = Grade::find($request->input('grade_id'));
        $section = Section::find($request->input('section_id'));

        $classRoom = new ClassRoom([
            'grade_id' => $request->input('grade_id'),
            'section_id' => $request->input('section_id'),
            'description' => $grade->description . ' ' . $section->description,
            'limit' => $request->input('limit'),
            'students_number' => 0,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);

        $classRoom->save();

        $count = ClassRoom::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'classRoom' => $classRoom,
            'count' => $count,
        ], 201);
    }

    public function delete($id)
    {
        $classRoom = ClassRoom::where('id', $id)->where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->first();

        if (empty($classRoom)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'El aula no existe'
            ], 404);
        }

        $classRoom->IsDeleted = true;
        $classRoom->DeleterUserId = Auth::id();
        $classRoom->DeletionTime = now()->format('Y-m-d H:i:s');
        $classRoom->save();

        $count = ClassRoom::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'classRoom' => $classRoom,
            'count' => $count
        ]);
    }

    public function get($grade_id, $section_id)
    {
        $classRoom = ClassRoom::where('grade_id', $grade_id)
            ->where('section_id', $section_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'classRoom' => $classRoom
        ]);
    }

    public function getAll()
    {
        $classRooms = ClassRoom::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $count = count($classRooms);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'classRooms' => $classRooms
        ]);
    }

    public function update(Request $request, $grade_id, $section_id)
    {
        $classRoomExists = ClassRoom::where('grade_id', '!=', $grade_id)
            ->where('section_id', '!=', $section_id)
            ->where('IsDeleted', false)
            ->where('TenantId', $this->academic_period->id)
            ->exists();

        if ($classRoomExists) {
            return response()->json([
                'status' => 'error',
                'message' => '¡El aula seleccionada ya existe!'
            ], 400);
        }

        $grade = Grade::find($request->input('grade_id'));
        $section = Section::find($request->input('section_id'));

        $classRoom = ClassRoom::where('grade_id', $grade_id)
            ->where('section_id', $section_id)
            ->first();

        $classRoom->grade_id = $request->input('grade_id');
        $classRoom->section_id = $request->input('section_id');
        $classRoom->description = $grade->description . ' ' . $section->description;
        $classRoom->limit = $request->input('limit');
        $classRoom->students_number = 0;
        $classRoom->LastModificationTime = now()->format('Y-m-d H:i:s');
        $classRoom->LastModifierUserId = Auth::id();
        $classRoom->save();

        $position = ClassRoom::where('id', '<=', $classRoom->id)->count();

        return response()->json([
            'status' => 'success',
            'classRoom' => $classRoom,
            'position' => $position,
        ], 200);
    }
}
