<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        $userExists = User::where('email', $request->email)->first();

        Auth::loginUsingId($userExists->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Bienvenido al sistema'
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

        $userCreated = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::loginUsingId($userCreated->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario creado con existo'
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function home()
    {
        return view('auth.home');
    }
}
