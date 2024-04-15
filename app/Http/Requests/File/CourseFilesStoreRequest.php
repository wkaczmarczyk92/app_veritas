<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourseFilesStoreRequest extends FormRequest
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
            'files_data.*.name' => 'required|string|max:255',
            // 'files_data.*.size' => 'required|integer|max:2048',
            'files_data.*.opt_for' => 'required|integer|exists:opt_fors,id',
            // 'files_data.*.file' => 'required|extension:pdf,doc,docx,odt,txt,rtf,zip,rar,7z|max:2048',

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
