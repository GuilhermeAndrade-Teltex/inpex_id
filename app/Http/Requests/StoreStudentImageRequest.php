<?php

namespace App\Http\Requests;

class StoreStudentImageRequest extends BasicRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules = array();
        $rules['school_id'] = ['required'];
        $rules['class'] = ['required', 'regex:/^[a-zA-Z0-9]/'];
        $rules['file'] = ['required', 'max:51200'];

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return parent::messages();
    }
}
