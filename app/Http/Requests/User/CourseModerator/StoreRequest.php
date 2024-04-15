<?php

namespace App\Http\Requests\User\CourseModerator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\User;

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
        return [
            'email' => 'required|unique:users,email|nullable|email|max:255',
            'crm_id' => 'sometimes|nullable|integer|min:1',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|confirmed|string|min:8|max:255',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $errors = collect($validator->errors());
        // dd($errors['email'][0]);
        if ($errors['email'] and $errors['email'][0] == 'The email has already been taken.') {
            $user = User::where('email', $this->email)->first();
        }

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'errors'    => $validator->errors(),
            'user' => $user ?? false
        ]));
    }
}
