<?php

namespace App\Http\Requests;

use App\Models\User;

class UserRequest extends BasicRequest
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

        $rules['name'] = ['required', 'string', 'max:255'];
        $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:' . User::class];
        $rules['cpf'] = ['required', 'digits:11', 'unique:' . User::class];
        $rules['perfil'] = ['required'];

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
