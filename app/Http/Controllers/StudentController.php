<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = view()->shared('academic_period');
    }

    public function index()
    {
        return view('entity.student.index');
    }

    public function create(CreateStudentRequest $request)
    {
        $emailExists = Student::where('intitutional_email', $request->input('intitutional_email'))->where('IsDeleted', false)->exists();
        $codeExists = Student::where('code', $request->input('code'))->where('IsDeleted', false)->exists();

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
            'intitutional_email' => $request->input('intitutional_email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);

        $student->save();

        $count = Student::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'student' => $student,
            'count' => $count,
        ], 201);
    }

    public function delete($id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->first();

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

        $count = Student::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'student' => $student,
            'count' => $count,
        ]);
    }

    public function get($id)
    {
        $studentExist = DB::table('students')->where('id', $id)->first();

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
        $students = Student::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $count = count($students);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'students' => $students
        ]);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = Student::where('id', $id)->where('IsDeleted', false)->first();

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
        $student->intitutional_email = $request->input('intitutional_email');
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
