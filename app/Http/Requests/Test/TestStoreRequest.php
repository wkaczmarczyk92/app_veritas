<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestStoreRequest extends FormRequest
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

        $rules = [
            'name' => 'required|string|max:255',
            'class_and_id' => 'sometimes|nullable|string',

            'permissions.roles' => 'array|nullable',
            'permissions.roles.*' => 'integer|exists:roles,id',

            'premissions.company_branches' => 'array|nullable',
            'premissions.company_branches.*' => 'integer|exists:company_branches,id',

            'visibility_id' => 'required|integer|exists:visibilities,id',
            'time' => 'sometimes|nullable|string',
            'questions' => 'required|array|min:1'
        ];

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->validationData();

            $roles_empty = empty($data['permissions']['roles'] ?? []);
            $company_branches_empty = empty($data['permissions']['company_branches'] ?? []);

            // Jeśli oba są puste, dodaj błąd walidacji
            if ($roles_empty && $company_branches_empty) {
                $validator->errors()->add('empty_roles_and_company_branches', 'Wybierz dla kogo ma być dostępny test.');
            }
        });
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'errors'    => $validator->errors()
        ]));
    }
}
