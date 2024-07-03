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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $data = $this->validationData();

        $cpf = str_replace([".", "/", "-"], "", $data["cpf"]);

        $this->merge([
            'cpf' => $cpf,
        ]);
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
        $rules['date_of_birth'] = ['nullable', 'date'];
        $rules['enrollment'] = ['nullable', 'string', 'max:255'];
        $rules['grade'] = ['required', 'string', 'max:255'];
        $rules['class'] = ['required', 'string', 'max:255'];
        $rules['cpf_responsible'] = ['nullable', 'digits:11'];
        $rules['responsible_name'] = ['nullable', 'string', 'max:255'];
        $rules['responsible_phone'] = ['nullable', 'string', 'max:255'];
        $rules['responsible_email'] = ['nullable', 'email', 'max:255'];
        $rules['cep'] = ['nullable', 'formato_cep', 'max:9'];
        $rules['address'] = ['nullable', 'string', 'max:255'];
        $rules['number'] = ['nullable', 'string', 'max:255'];
        $rules['complement'] = ['nullable', 'string', 'max:255'];
        $rules['district'] = ['nullable', 'string', 'max:255'];
        $rules['city'] = ['nullable', 'string', 'max:255'];
        $rules['state'] = ['nullable', 'string', 'max:255'];
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
