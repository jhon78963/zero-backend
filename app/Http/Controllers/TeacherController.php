<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
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
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.teacher.delete')->only(['delete']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('entity.teacher.index');
    }

    public function create(CreateTeacherRequest $request)
    {
        $emailExists = Teacher::where('institutional_email', $request->input('institutional_email'))->where('IsDeleted', false)->exists();
        $codeExists = Teacher::where('code', $request->input('code'))->where('IsDeleted', false)->exists();

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
            'TenantId' => $this->academic_period->id,
        ]);

        $teacher->save();

        $this->generateUser($teacher);

        $count = Teacher::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher,
            'count' => $count,
        ], 201);
    }

    public function generateUser($teacher)
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
            'TenantId' => $this->academic_period->id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 3,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);
    }

    public function delete($id)
    {
        $teacher = Teacher::where('id', $id)->where('IsDeleted', false)->first();

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

        $count = Teacher::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher,
            'count' => $count
        ]);
    }

    public function get($id)
    {
        $teacherExist = DB::table('teachers')->where('id', $id)->first();

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

    public function getAll()
    {
        $teachers = Teacher::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $count = count($teachers);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'teachers' => $teachers
        ]);
    }

    public function update(UpdateTeacherRequest $request, $id)
    {
        $teacher = Teacher::where('id', $id)->where('IsDeleted', false)->first();

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
