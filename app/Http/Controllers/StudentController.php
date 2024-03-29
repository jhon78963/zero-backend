<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\AttendanceDetail;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentPayment;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
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
            'gender' => $request->input('gender'),
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
        $students = Student::leftJoin('student_classroom as sc', 'sc.student_id', 'students.id')
            ->leftJoin('class_rooms as c', 'c.id', 'sc.classroom_id')
            ->where('students.IsDeleted', false)
            ->where('students.TenantId', $period_id)
            ->select('students.*', 'c.description as classroom')
            ->get();

        $count = count($students);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'students' => $students
        ]);
    }

    public function search($period_id, $value)
    {
        $students = Student::leftJoin('student_classroom as sc', 'sc.student_id', 'students.id')
            ->leftJoin('class_rooms as c', 'c.id', 'sc.classroom_id')
            ->where('students.IsDeleted', false)
            ->where('students.TenantId', $period_id)
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

        $count = count($students);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'students' => $students
        ]);
    }

    public function show($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student = Student::find($student_id);
        $classroom = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->where('student_classroom.student_id', $student_id)
            ->select('cr.id', 'cr.description')
            ->first();

        return view('entity.student.show', compact('period', 'student', 'classroom'));
    }

    public function missing($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $missing = AttendanceDetail::where('TenantId', $period->id)
            ->where('student_id', $student_id)
            ->where('status', 'FALTA')
            ->select(DB::raw("DATE_FORMAT(CreationTime, '%d-%m-%Y') as date"), 'status')
            ->get();
        $student = Student::findOrFail($student_id);
        $classroom = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->where('student_classroom.student_id', $student_id)
            ->select('cr.id', 'cr.description')
            ->first();

        return view('entity.student.missing', compact('period', 'missing', 'student', 'classroom'));
    }

    public function getMissing($period_id, $student_id)
    {
        $missing = AttendanceDetail::where('TenantId', $period_id)
            ->where('student_id', $student_id)
            ->where('status', 'FALTA')
            ->select(DB::raw("DATE_FORMAT(CreationTime, '%d-%m-%Y') as date"), 'status')
            ->get();

        return response()->json($missing);
    }

    public function getMissingSearch($period_id, $student_id, $value)
    {
        $missing = AttendanceDetail::where('TenantId', $period_id)
            ->where('student_id', $student_id)
            ->where('status', 'FALTA')
            ->where(function ($query) use ($value) {
                $query->where(DB::raw("DATE_FORMAT(CreationTime, '%d-%m-%Y')"), 'LIKE', '%' . $value . '%');
            })
            ->select(DB::raw("DATE_FORMAT(CreationTime, '%d-%m-%Y') as date"), 'status')
            ->get();

        return response()->json($missing);
    }

    public function payment($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student = Student::findOrFail($student_id);
        $classroom = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->where('student_classroom.student_id', $student_id)
            ->select('cr.id', 'cr.description')
            ->first();

        $payments = StudentPayment::join('payments as p', 'p.id', 'student_payments.payment_id')
            ->where('student_payments.TenantId', $period->id)
            ->where('student_payments.student_id', $student_id)
            ->where('student_payments.isPaid', true)
            ->select(DB::raw("DATE_FORMAT(student_payments.CreationTime, '%d-%m-%Y') as date"), 'p.description', 'p.cost')
            ->get();

        return view('entity.student.payment', compact('period', 'payments', 'student', 'classroom'));
    }

    public function getPayments($period_id, $student_id)
    {
        $payments = StudentPayment::join('payments as p', 'p.id', 'student_payments.payment_id')
            ->where('student_payments.TenantId', $period_id)
            ->where('student_payments.student_id', $student_id)
            ->where('student_payments.isPaid', true)
            ->select(DB::raw("DATE_FORMAT(student_payments.CreationTime, '%d-%m-%Y') as date"), 'p.description', 'p.cost')
            ->get();

        return response()->json($payments);
    }

    public function getPaymentsSearch($period_id, $student_id, $value)
    {
        $payments = StudentPayment::join('payments as p', 'p.id', 'student_payments.payment_id')
            ->where('student_payments.TenantId', $period_id)
            ->where('student_payments.student_id', $student_id)
            ->where('student_payments.isPaid', true)
            ->where(function ($query) use ($value) {
                $query->where(DB::raw("DATE_FORMAT(student_payments.CreationTime, '%d-%m-%Y')"), 'LIKE', '%' . $value . '%')
                    ->orWhere('p.description', 'LIKE', '%' . $value . '%')
                    ->orWhere('p.cost', 'LIKE', '%' . $value . '%');
            })
            ->select(DB::raw("DATE_FORMAT(student_payments.CreationTime, '%d-%m-%Y') as date"), 'p.description', 'p.cost')
            ->get();

        return response()->json($payments);
    }

    public function paymentPdf($period_name, $student_id)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $student = Student::findOrFail($student_id);
        $classroom = StudentClassroom::join('class_rooms as cr', 'cr.id', 'student_classroom.classroom_id')
            ->where('student_classroom.student_id', $student_id)
            ->select('cr.id', 'cr.description')
            ->first();

        $payments = StudentPayment::join('payments as p', 'p.id', 'student_payments.payment_id')
            ->where('student_payments.TenantId', $period->id)
            ->where('student_payments.student_id', $student_id)
            ->where('student_payments.isPaid', true)
            ->select(DB::raw("DATE_FORMAT(student_payments.CreationTime, '%d/%m/%Y') as date"), 'p.description', 'p.cost')
            ->get();

        $pdf = DomPDF::loadView('entity.student.payment-pdf', compact('period', 'payments', 'student', 'classroom'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-pagos.pdf');
    }

    public function update(UpdateStudentRequest $request, $period_id, $id)
    {
        $student = Student::leftJoin('student_classroom as sc', 'sc.student_id', 'students.id')
            ->leftJoin('class_rooms as c', 'c.id', 'sc.classroom_id')
            ->where('students.id', $id)
            ->where('students.IsDeleted', false)
            ->where('students.TenantId', $period_id)
            ->select('students.*', 'c.description as classroom')
            ->first();

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
        $student->gender = $request->input('gender');
        $student->LastModificationTime = now()->format('Y-m-d H:i:s');
        $student->LastModifierUserId = Auth::id();
        $student->save();

        return response()->json([
            'status' => 'success',
            'student' => $student
        ]);
    }
}
