<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use App\Models\UserRole;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['logout','home']]);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],
        [
            'email.required'=>'El email es requerido',
            'email.max'=>'Maximo 20 caracteres para el username del usuario',
            'email.email'=>'El email debe tener un formato válido',
            'password.required'=>'La contraseña es requerida',
            'password.max'=>'Maximo 20 caracteres permitidos',
            'password.min'=>'Mínimo 8 caracteres permitidos',

        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Revise sus credenciales de acceso',
            ], 401);
        }

        $userExists = User::join('userRoles as ur', 'users.id', 'ur.userId')
                            ->join('roles as r', 'ur.roleId', 'r.id')
                            ->where('email', $request->email)
                            ->select('users.id as userId', 'r.id as roleId')
                            ->first();

        Auth::loginUsingId($userExists->userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Bienvenido al sistema',
            'role' => $userExists->roleId
        ], 200);

    }

    public function create()
    {
        return view('auth.register');
    }

    public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpassword' => 'required|string|min:8',
        ],
        [
            'name.required'=>'El nombre es requerido',
            'name.max'=>'Maximo 20 caracteres para el nombre del usuario',
            'email.required'=>'El email es requerido',
            'email.max'=>'Maximo 20 caracteres para el username del usuario',
            'email.email'=>'El email debe tener un formato válido',
            'email.unique'=>'El email ya existe en nuestra base de datos',
            'password.required'=>'La contraseña es requerida',
            'password.max'=>'Maximo 20 caracteres permitidos',
            'password.min'=>'Mínimo 8 caracteres permitidos',
            'cpassword.required'=>'La contraseña para validar es requerida',

        ]);

        if($request->password != $request->cpassword){
            return response()->json([
                'status' => 'error',
                'message' => 'Las contraseñas no coinciden, Porfavor verifique!',
            ], 401);
        }

        if(empty($request->termino))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Porfavor aceptar los terminos y condiciones',
            ], 401);
        }

        if (User::all()->count()) {
                $last_user_id = User::all()->last()->id+1;
            } else {
                $last_user_id = 1;
            }

        $userCreated = User::create([
            'id' => $last_user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (isset($userCreated))
        {
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

    public function home()
    {
        return view('auth.home');
    }

    public function recovery(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ],
        [
            'email.required'=>'El email es requerido',
            'email.email'=>'El email debe tener un formato válido',
        ]);

        $userExists = User::where('email', $request->email)->exists();

        if(!$userExists)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'El email no existe en nuestra base de datos',
            ], 401);
        }

        $password_default = '123456789';

        $user = User::where('email', $request->email)->first();

        $user_fullName = $user->name.' '.$user->surname;

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
        // if ($request->hasFile('profilePicture')) {
        //     $imagen = $request->file('profilePicture');
        //     $fileFolderPath = '/assets/img/avatars/';
        //     $nombreImagen = $imagen->getClientOriginalName();
        //     $suffix = 1;
        //     $fileNameWithoutExtension = pathinfo($nombreImagen, PATHINFO_FILENAME);
        //     while (User::where('profilePicture', $nombreImagen)->exists()) {
        //         $fileName = $fileNameWithoutExtension . "($suffix)." . $imagen->getClientOriginalExtension();
        //         $suffix++;
        //         $nombreImagen = $fileName;
        //     }
        //     $imagen->move(public_path($fileFolderPath), $nombreImagen);
        //     $profilePicture = $fileFolderPath . $nombreImagen;
        // }

        // User::findOrFail($id)->update([
        //     'name' => $request->name,
        //     'surname' => $request->surname,
        //     'email' => $request->email,
        //     'phoneNumber' => $request->phoneNumber,
        //     'profilePicture' => $profilePicture,
        // ]);
        $user = User::findOrFail($id);
$nombreFotoAnterior = $user->profilePicture;

if (!is_null($nombreFotoAnterior) && file_exists(public_path($nombreFotoAnterior))) {
    unlink(public_path($nombreFotoAnterior));
}

$profilePicture = null; // Inicializa la variable como nula por defecto

if ($request->hasFile('profilePicture')) {
    $imagen = $request->file('profilePicture');
    $fileFolderPath = '/assets/img/avatars/';
    $nombreImagen = $imagen->getClientOriginalName();
    $imagen->move(public_path($fileFolderPath), $nombreImagen);
    $profilePicture = $fileFolderPath . $nombreImagen;
}

$user->update([
    'name' => $request->name,
    'surname' => $request->surname,
    'email' => $request->email,
    'phoneNumber' => $request->phoneNumber,
    'profilePicture' => $profilePicture,
]);

return back();

    }
}