<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends BasicRequest
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
        $studentId = $this->route('id');

        $rules['school_id'] = ['required'];
        $rules['name'] = ['required', 'string', 'max:255'];
        $rules['cpf'] = ['required', 'digits:11', Rule::unique(Student::class)->ignore($studentId)];
        $rules['date_of_birth'] = ['required', 'date'];
        $rules['enrollment'] = ['required', 'string', 'max:255'];
        $rules['grade'] = ['required', 'string', 'max:255'];
        $rules['class'] = ['required', 'string', 'max:255'];
        $rules['education_level'] = ['required', 'string', 'max:255'];
        $rules['responsible_name'] = ['required', 'string', 'max:255'];
        $rules['responsible_phone'] = ['required', 'string', 'max:255'];
        $rules['responsible_email'] = ['required', 'email', 'max:255'];
        $rules['cep'] = ['required', 'formato_cep', 'max:9'];
        $rules['address'] = ['required', 'string', 'max:255'];
        $rules['number'] = ['required', 'string', 'max:255'];
        $rules['complement'] = ['nullable', 'string', 'max:255'];
        $rules['district'] = ['required', 'string', 'max:255'];
        $rules['city'] = ['required', 'string', 'max:255'];
        $rules['state'] = ['required', 'string', 'max:255'];
        $rules['observations'] = ['nullable', 'string', 'max:255'];

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
