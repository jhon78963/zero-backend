<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSecretaryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dni' => 'required|max:8',
            'first_name' => 'required',
            'surname' => 'required',
            'code' => 'required|max:4',
            'intitutional_email' => 'required',
        ];
    }
}
