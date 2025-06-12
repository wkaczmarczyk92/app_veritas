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
            'msg' => 'required_unless:subject_id,8|nullable|string',

            'bank_account.owner_name' => 'required_if:subject_id,8|nullable|string|max:255',
            'bank_account.account_type_id' => 'required_if:subject_id,8|nullable|integer|min:1',
            'bank_account.account_number' => 'required_if:subject_id,8|nullable|string|max:255',
            'bank_account.bank_name' => 'required_if:subject_id,8|nullable|string|max:255',
            'bank_account.swift' => 'required_if:bank_account.account_type_id,1|nullable|string|max:255'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ]));
    }
}
