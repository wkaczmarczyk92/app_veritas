<?php

namespace App\Http\Requests\Compendium;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::user()->hasRole(['course_moderator'])) {
            return true;
        }

        abort(403, 'Unauthorized action.');
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'time_to_read' => 'sometimes|nullable|string',
            'visibility_id' => 'required|integer',
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
