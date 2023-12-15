<?php

namespace App\Http\Requests\Caretaker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            return true;
        }

        abort(403, 'Unauthorized action.');
        return false;
    }

    public function rules(): array
    {
        return [
            'caretaker_first_name' => 'required|string',
                'caretaker_last_name' => 'required|string',
                'caretaker_email' => 'sometimes|nullable|email',
                'caretaker_phone_number' => 'required|string',
                'language_id' => 'required|integer'
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
