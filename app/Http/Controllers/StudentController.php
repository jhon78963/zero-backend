<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.student')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.student.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.student.delete')->only(['delete']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('entity.student.index', compact('period'));
    }

    public function create(CreateStudentRequest $request, $period_id)
    {
        $emailExists = Student::where('institutional_email', $request->input('institutional_email'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();
        $codeExists = Student::where('code', $request->input('code'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();

        if ($emailExists || $codeExists) {
            return response()->json([
                'status' => 'error',
                'msg' => $emailExists && $codeExists ? 'The email and code already exist' : ($emailExists ? 'The email already exists' : 'The code already exists')
            ], 400);
        }

        $student = new Student([
            'dni' => $request->input('dni'),
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

        $student->save();

        $this->generateUser($student, $period_id);

        $count = Student::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'student' => $student,
            'count' => $count,
        ]);
    }

    public function generateUser($student, $period_id)
    {
        $user = new User([
            'username' => $student->code,
            'email' => $student->institutional_email,
            'name' => $student->first_name,
            'surname' => $student->surname,
            'password' => Hash::make('123456789'),
            'phoneNumber' => $student->phone,
            'profilePicture' => '/assets/img/avatars/1.png',
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 4,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);
    }

    public function delete($period_id, $id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($student)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The student does not exist'
            ], 404);
        }

        $student->IsDeleted = true;
        $student->DeleterUserId = Auth::id();
        $student->DeletionTime = now()->format('Y-m-d H:i:s');
        $student->save();

        $count = Student::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'student' => $student,
            'count' => $count,
        ]);
    }

    public function get($period_id, $id)
    {
        $studentExist = DB::table('students')->where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($studentExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The student does not exist'
            ], 404);
        }

        $student = Student::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'student' => $student
        ]);
    }

    public function getAll($period_id)
    {
        $students = Student::where('IsDeleted', false)->where('TenantId', $period_id)->get();
        $count = count($students);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'students' => $students
        ]);
    }

    public function update(UpdateStudentRequest $request, $period_id, $id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($student)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The student does not exist'
            ], 404);
        }

        $student->dni = $request->input('dni');
        $student->first_name = $request->input('first_name');
        $student->other_names = $request->input('other_names');
        $student->surname = $request->input('surname');
        $student->mother_surname = $request->input('mother_surname');
        $student->code = $request->input('code');
        $student->institutional_email = $request->input('institutional_email');
        $student->phone = $request->input('phone');
        $student->address = $request->input('address');
        $student->LastModificationTime = now()->format('Y-m-d H:i:s');
        $student->LastModifierUserId = Auth::id();
        $student->save();

        return response()->json([
            'status' => 'success',
            'student' => $student
        ]);
    }
}
