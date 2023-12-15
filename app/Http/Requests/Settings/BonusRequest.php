<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BonusRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (Auth::user()->hasAnyRole(['admin', 'super-admin']))
            return true;
        else
            abort(403, 'Brak dostępu do tego zasobu.');
        return false;
    }

    public function rules(): array
    {
        return [
            'caretaker_bonus'   => 'required|integer|min:1',
            'family_bonus'      => 'required|integer|min:1',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'errors'    => $validator->errors()
        ]));
    }
}
