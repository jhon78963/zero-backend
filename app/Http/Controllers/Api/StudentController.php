<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class StudentController extends Controller
{
    public function create(CreateStudentRequest $request)
    {
        $emailExists = Student::where('institutional_email', $request->input('institutional_email'))->where('IsDeleted', false)->where('TenantId', 1)->exists();
        $codeExists = Student::where('code', $request->input('code'))->where('IsDeleted', false)->where('TenantId', 1)->exists();

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
            'TenantId' => 1,
        ]);

        $student->save();

        $this->generateUser($student);

        return response()->json([
            'status' => 'success',
            'student' => $student,
        ]);
    }

    public function generateUser($student)
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
            'TenantId' => 1,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 4,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => 1,
        ]);
    }

    public function delete($id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->where('TenantId', 1)->first();

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

        return response()->json([
            'status' => 'success',
            'student' => $student,
        ]);
    }

    public function get($id)
    {
        $studentExist = DB::table('students')->where('id', $id)->where('IsDeleted', false)->where('TenantId', 1)->first();

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

    public function getAll()
    {
        $students = Student::leftJoin('student_classroom as sc', 'sc.student_id', 'students.id')
            ->leftJoin('class_rooms as c', 'c.id', 'sc.classroom_id')
            ->where('students.IsDeleted', false)
            ->select('students.*', 'c.description as classroom')
            ->get();

        return response()->json([
            'status' => 'success',
            'students' => $students
        ]);
    }

    public function search($value)
    {
        $students = Student::leftJoin('student_classroom as sc', 'sc.student_id', 'students.id')
            ->leftJoin('class_rooms as c', 'c.id', 'sc.classroom_id')
            ->where('students.IsDeleted', false)
            ->where(function ($query) use ($value) {
                $query->where('students.surname', 'LIKE', $value . '%')
                    ->orWhere('students.mother_surname', 'LIKE', $value . '%')
                    ->orWhere('students.first_name', 'LIKE', $value . '%')
                    ->orWhere('students.other_names', 'LIKE', $value . '%')
                    ->orWhere('students.institutional_email', 'LIKE', $value . '%')
                    ->orWhere('c.description', 'LIKE', $value . '%');
            })
            ->select('students.*', 'c.description as classroom')
            ->get();

        return response()->json([
            'status' => 'success',
            'students' => $students
        ]);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->where('TenantId', 1)->first();

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
