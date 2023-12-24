<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSecretaryRequest;
use App\Http\Requests\UpdateSecretaryRequest;
use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecretaryController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = view()->shared('academic_period');
    }

    public function index()
    {
        return view('entity.secretary.index');
    }

    public function create(CreateSecretaryRequest $request)
    {
        $emailExists = Secretary::where('intitutional_email', $request->input('intitutional_email'))->where('IsDeleted', false)->exists();
        $codeExists = Secretary::where('code', $request->input('code'))->where('IsDeleted', false)->exists();

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
            'intitutional_email' => $request->input('intitutional_email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'CreatorUserId' => Auth::id(),
            'TenantId' => $this->academic_period->id,
        ]);

        $secretary->save();

        $count = Secretary::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary,
            'count' => $count,
        ], 201);
    }

    public function delete($id)
    {
        $secretary = Secretary::where('id', $id)->where('IsDeleted', false)->first();

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

        $count = Secretary::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->count();

        return response()->json([
            'status' => 'success',
            'secretary' => $secretary,
            'count' => $count,
        ]);
    }

    public function get($id)
    {
        $secretaryExist = DB::table('secretaries')->where('id', $id)->first();

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

    public function getAll()
    {
        $secretaries = Secretary::where('IsDeleted', false)->where('TenantId', $this->academic_period->id)->get();
        $count = count($secretaries);

        return response()->json([
            'status' => 'success',
            'maxCount' => $count,
            'secretaries' => $secretaries
        ]);
    }

    public function update(UpdateSecretaryRequest $request, $id)
    {
        $secretary = Secretary::where('id', $id)->where('IsDeleted', false)->first();

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
        $secretary->intitutional_email = $request->input('intitutional_email');
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
