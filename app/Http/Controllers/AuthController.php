<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\RecoveryPasswordMail;
use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\SchoolRegistration;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Treasury;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['logout', 'home']]);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'El email es requerido',
                'email.max' => 'Maximo 20 caracteres para el username del usuario',
                'email.email' => 'El email debe tener un formato válido',
                'password.required' => 'La contraseña es requerida',
                'password.max' => 'Maximo 20 caracteres permitidos',
                'password.min' => 'Mínimo 8 caracteres permitidos',

            ]
        );

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Revise sus credenciales de acceso',
            ], 401);
        }

        $userExists = User::join('user_roles as ur', 'users.id', 'ur.userId')
            ->join('roles as r', 'ur.roleId', 'r.id')
            ->where('email', $request->email)
            ->select('users.id as userId', 'r.id as roleId', 'users.TenantId')
            ->first();

        $period = AcademicPeriod::where('id', $userExists->TenantId)->first();

        Auth::loginUsingId($userExists->userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Bienvenido al sistema',
            'role' => $userExists->roleId,
            'period' => $period
        ], 200);
    }

    public function create()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'cpassword' => 'required|string|min:8',
            ],
            [
                'name.required' => 'El nombre es requerido',
                'name.max' => 'Maximo 20 caracteres para el nombre del usuario',
                'email.required' => 'El email es requerido',
                'email.max' => 'Maximo 20 caracteres para el username del usuario',
                'email.email' => 'El email debe tener un formato válido',
                'email.unique' => 'El email ya existe en nuestra base de datos',
                'password.required' => 'La contraseña es requerida',
                'password.max' => 'Maximo 20 caracteres permitidos',
                'password.min' => 'Mínimo 8 caracteres permitidos',
                'cpassword.required' => 'La contraseña para validar es requerida',

            ]
        );

        if ($request->password != $request->cpassword) {
            return response()->json([
                'status' => 'error',
                'message' => 'Las contraseñas no coinciden, Porfavor verifique!',
            ], 401);
        }

        if (empty($request->termino)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Porfavor aceptar los terminos y condiciones',
            ], 401);
        }

        if (User::all()->count()) {
            $last_user_id = User::all()->last()->id + 1;
        } else {
            $last_user_id = 1;
        }

        $userCreated = User::create([
            'id' => $last_user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profilePicture' => '/assets/img/avatars/1.png',
        ]);

        if (isset($userCreated)) {
            $user_role = UserRole::create([
                "userId" => $last_user_id,
                "roleId" => 2
            ]);
        }

        Auth::loginUsingId($userCreated->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario creado con existo',
            'role' => $user_role->roleId
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('auth.login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function home($period_name)
    {
        $diaActual = Carbon::now('America/Lima')->locale('es')->isoFormat('dddd');
        $fechaActual = Carbon::now('America/Lima')->locale('es')->isoFormat("MMMM D, YYYY");
        $period = AcademicPeriod::where('name', $period_name)->first();
        $schoolRegistrationCount = SchoolRegistration::where('TenantId', $period->id)->where('IsDeleted', false)->where('status', '!=', 'ANULADO')->count();
        $teacherCount = Teacher::where('TenantId', $period->id)->where('IsDeleted', false)->count();
        $courseCount = Course::where('TenantId', $period->id)->where('IsDeleted', false)->count();
        $studentCount = Student::where('TenantId', $period->id)->where('IsDeleted', false)->count();
        $treasuryMount = Treasury::where('TenantId', $period->id)->where('IsDeleted', false)->sum('total');
        // $currentYear = Carbon::now()->year;
        // $academic_period = AcademicPeriod::where('year', $currentYear)->first();
        return view('auth.home', compact('diaActual', 'fechaActual', 'period', 'schoolRegistrationCount', 'teacherCount', 'courseCount', 'studentCount', 'treasuryMount'));
    }

    public function homePrincipal()
    {
        $diaActual = Carbon::now('America/Lima')->locale('es')->isoFormat('dddd');
        $fechaActual = Carbon::now('America/Lima')->locale('es')->isoFormat("MMMM D, YYYY");
        return view('home.principal', compact('diaActual', 'fechaActual'));
    }

    public function recovery(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'El email es requerido',
                'email.email' => 'El email debe tener un formato válido',
            ]
        );

        $userExists = User::where('email', $request->email)->exists();

        if (!$userExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'El email no existe en nuestra base de datos',
            ], 401);
        }

        $password_default = '123456789';

        $user = User::where('email', $request->email)->first();

        $user_fullName = $user->name . ' ' . $user->surname;

        User::where('email', $request->email)->update([
            'password' => Hash::make($password_default),
        ]);

        $mail = new RecoveryPasswordMail($password_default, $user_fullName);
        Mail::to($request->email)->send($mail);

        return response()->json([
            'status' => 'success',
            'message' => 'Se envió un mensaje con la contraseña. Porfavor revise su bandeja de entrada',
        ]);
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function storeProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $profilePicture = null;

        if ($request->hasFile('profilePicture')) {
            $imagen = $request->file('profilePicture');
            $fileFolderPath = '/assets/img/avatars/';
            $nombreImagen = $imagen->getClientOriginalName();
            $suffix = 1;
            $fileNameWithoutExtension = pathinfo($nombreImagen, PATHINFO_FILENAME);

            // Agregar sufijo numérico si el archivo ya existe en el sistema de archivos
            while (file_exists(public_path($fileFolderPath . $nombreImagen))) {
                $fileName = $fileNameWithoutExtension . "($suffix)." . $imagen->getClientOriginalExtension();
                $suffix++;
                $nombreImagen = $fileName;
            }

            $imagen->move(public_path($fileFolderPath), $nombreImagen);
            $user->profilePicture = $fileFolderPath . $nombreImagen;
        }

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phoneNumber = $request->phoneNumber;
        $user->save();

        return back();
    }
}
