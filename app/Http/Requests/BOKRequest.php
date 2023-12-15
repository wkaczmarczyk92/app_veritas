<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BOKRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return Auth::user()->hasRole('user');
    }
    
    public function rules(): array
    {
        return [
            'subject_id' => 'required|integer|min:1',
            'msg' => 'required|string'
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ]));
    }
}
