<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSecretaryRequest;
use App\Http\Requests\UpdateSecretaryRequest;
use App\Models\AcademicPeriod;
use App\Models\Api\User;
use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class SecretaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.secretary')->only(['index', 'getAll', 'get']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.secretary.modify')->only(['create', 'update']);
        $this->middleware('check.permissions:Admin-Secretaria,pages.secretary.delete')->only(['delete']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('entity.secretary.index', compact('period'));
    }

    public function create(CreateSecretaryRequest $request, $period_id)
    {
        $emailExists = Secretary::where('institutional_email', $request->input('institutional_email'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();
        $codeExists = Secretary::where('code', $request->input('code'))->where('IsDeleted', false)->where('TenantId', $period_id)->exists();

        if ($emailExists || $codeExists) {
            return response()->json([
                'status' => 'error',
                'msg' => $emailExists && $codeExists ? 'The email and code already exist' : ($emailExists ? 'The email already exists' : 'The code already exists')
            ], 400);
        }

        $secretary = new Secretary([
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

        $secretary->save();

        $this->generateUser($secretary, $period_id);

        $count = Secretary::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary,
            'count' => $count,
        ], 201);
    }

    public function generateUser($secretary, $period_id)
    {
        $user = new User([
            'username' => $secretary->code,
            'email' => $secretary->institutional_email,
            'name' => $secretary->first_name,
            'surname' => $secretary->surname,
            'password' => Hash::make('123456789'),
            'phoneNumber' => $secretary->phone,
            'profilePicture' => '/assets/img/avatars/1.png',
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);

        $user->save();

        DB::table('user_roles')->insert([
            'roleId' => 2,
            'userId' => $user->id,
            'CreatorUserId' => Auth::id(),
            'TenantId' => $period_id,
        ]);
    }

    public function delete($period_id, $id)
    {
        $secretary = Secretary::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($secretary)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The secretary does not exist'
            ], 404);
        }

        $secretary->IsDeleted = true;
        $secretary->DeleterUserId = Auth::id();
        $secretary->DeletionTime = now()->format('Y-m-d H:i:s');
        $secretary->save();

        $count = Secretary::where('IsDeleted', false)->where('TenantId', $period_id)->count();

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary,
            'count' => $count,
        ]);
    }

    public function get($period_id, $id)
    {
        $secretaryExist = DB::table('secretaries')->where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($secretaryExist)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The secretary does not exist'
            ], 404);
        }

        $secretary = Secretary::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary
        ]);
    }

    public function getAll($period_id)
    {
        $secretaries = Secretary::where('IsDeleted', false)->where('TenantId', $period_id)->get();
        $count = count($secretaries);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'secretaries' => $secretaries
        ]);
    }

    public function update(UpdateSecretaryRequest $request, $period_id, $id)
    {
        $secretary = Secretary::where('id', $id)->where('IsDeleted', false)->where('TenantId', $period_id)->first();

        if (empty($secretary)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The secretary does not exist'
            ], 404);
        }

        $secretary->dni = $request->input('dni');
        $secretary->first_name = $request->input('first_name');
        $secretary->other_names = $request->input('other_names');
        $secretary->surname = $request->input('surname');
        $secretary->mother_surname = $request->input('mother_surname');
        $secretary->code = $request->input('code');
        $secretary->institutional_email = $request->input('institutional_email');
        $secretary->phone = $request->input('phone');
        $secretary->address = $request->input('address');
        $secretary->LastModificationTime = now()->format('Y-m-d H:i:s');
        $secretary->LastModifierUserId = Auth::id();
        $secretary->save();

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary
        ]);
    }
}
