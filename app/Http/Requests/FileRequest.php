<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'files.required' => 'The Files field is required.',
            'files.array' => 'The Files field must be an array.',
            'files.*.required' => 'The Files field is required.',
            'files.*.file' => 'The Files field must be a file.',
            'files.*.mimes' => 'The Files must be a file of type jpeg, jpg, png, pdf, doc, docx, xlsx, eml.',
            'files.*.max' => 'The Files must not be greater than 5 MB.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,doc,docx,xlsx,eml|max:5120',
            'sales_id' => 'required|integer|exists:sales,id',
            'requirement_id' => 'required|integer|exists:requirements,id',
        ];
    }
}
