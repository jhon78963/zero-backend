<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'roleId' => ['required', 'numeric'],
            'userId' => ['required', 'numeric']
        ];
    }
}
