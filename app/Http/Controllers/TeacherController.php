<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\Teacher;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.delete')->only(['delete']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('entity.teacher.index', compact('period'));
    }

    public function create(CreateTeacherRequest $request, $period_id)
    {
        $emailExists = Teacher::where('institutional_email', $request->input('institutional_email'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();
        $codeExists = Teacher::where('code', $request->input('code'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();

        if ($emailExists || $codeExists) {
            return response()->json([
                'status' => 'error',
                'msg' => $emailExists && $codeExists ? 'The email and code already exist' : ($emailExists ? 'The email already exists' : 'The code already exists')
            ], 400);
        }

        $teacher = new Teacher([
            'dni' => $request->input('dni'),
            'type' => $request->input('type'),
            'first_name' => $request->input('first_name'),
            'other_names' => $request->input('other_names'),
            'surname' => $request->input('surname'),
            'mother_surname' => $request->input('mother_surname'),
            'code' => $request->input('code'),
            'institutional_email' => $request->input('institutional_email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $teacher->save();

        $this->generateUser($teacher, $period_id);

        $count = Teacher::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher,
            'count' => $count,
        ], 201);
    }

    public function generateUser($teacher, $period_id)
    {
        $user = new User([
            'username' => $teacher->code,
            'email' => $teacher->institutional_email,
            'name' => $teacher->first_name,
            'surname' => $teacher->surname,
            'password' => Hash::make('123456789'),
            'phoneNumber' => $teacher->phone,
            'profilePicture' => '/assets/img/avatars/1.png',
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 3,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);
    }

    public function delete($period_id, $id)
    {
        $teacher = Teacher::where('id', $id)->where('IsDeleted', false)->where('TenantId')->first();

        if (empty($teacher)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The teacher does not exist'
            ], 404);
        }

        $teacher->IsDeleted = true;
        $teacher->DeleterUserId = Auth::id();
        $teacher->DeletionTime = now()->format('Y-m-d H:i:s');
        $teacher->save();

        $count = Teacher::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher,
            'count' => $count
        ]);
    }

    public function get($period_id, $id)
    {
        $teacherExist = DB::table('teachers')->where('id', $id)->where('TenantId', $period_id)->first();

        if (empty($teacherExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The teacher does not exist'
            ], 404);
        }

        $teacher = Teacher::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher
        ]);
    }

    public function getAll($period_id)
    {
        $teachers = Teacher::leftJoin('teacher_classrooms as tc', function ($join) use ($period_id) {
            $join->on('teachers.id', '=', 'tc.teacher_id')
                ->where('tc.TenantId', '=', $period_id);
        })
            ->leftJoin('class_rooms as c', 'c.id', '=', 'tc.classroom_id')
            ->leftJoin('teacher_courses as tco', function ($join) use ($period_id) {
                $join->on('teachers.id', '=', 'tco.teacher_id')
                    ->where('tco.TenantId', '=', $period_id);
            })
            ->leftJoin('courses as co', 'co.id', '=', 'tco.course_id')
            ->where('teachers.IsDeleted', false)
            ->where('teachers.TenantId', $period_id)
            ->select('teachers.*', 'c.description as classroom', 'co.description as course')
            ->get();

        $count = count($teachers);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'teachers' => $teachers
        ]);
    }

    public function update(UpdateTeacherRequest $request, $period_id, $id)
    {
        $teacher = Teacher::leftJoin('teacher_classrooms as tc', function ($join) use ($period_id) {
            $join->on('teachers.id', '=', 'tc.teacher_id')
                ->where('tc.TenantId', '=', $period_id);
        })
            ->leftJoin('class_rooms as c', 'c.id', '=', 'tc.classroom_id')
            ->leftJoin('teacher_courses as tco', function ($join) use ($period_id) {
                $join->on('teachers.id', '=', 'tco.teacher_id')
                    ->where('tco.TenantId', '=', $period_id);
            })
            ->leftJoin('courses as co', 'co.id', '=', 'tco.course_id')
            ->where('teachers.id', $id)
            ->where('teachers.IsDeleted', false)
            ->where('teachers.TenantId', $period_id)
            ->select('teachers.*', 'c.description as classroom', 'co.description as course')
            ->first();

        if (empty($teacher)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The teacher does not exist'
            ], 404);
        }

        $teacher->dni = $request->input('dni');
        $teacher->type = $request->input('type');
        $teacher->first_name = $request->input('first_name');
        $teacher->other_names = $request->input('other_names');
        $teacher->surname = $request->input('surname');
        $teacher->mother_surname = $request->input('mother_surname');
        $teacher->code = $request->input('code');
        $teacher->institutional_email = $request->input('institutional_email');
        $teacher->phone = $request->input('phone');
        $teacher->address = $request->input('address');
        $teacher->LastModificationTime = now()->format('Y-m-d H:i:s');
        $teacher->LastModifierUserId = Auth::id();
        $teacher->save();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher
        ]);
    }
}
