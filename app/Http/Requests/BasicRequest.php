<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use App\Models\User;

class BasicRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['confirmed', Rules\Password::defaults()],
            'perfil' => [''],
            'cpf' => ['digits:14', 'unique:' . User::class],
        ];

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'Nome não deve ser somente numérico',
            'fullname.required' => 'O campo nome completo é obrigatório.',

            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está sendo utilizado.',
            'email.string' => 'E-mail não deve ser somente numérico',

            'perfil.required' => 'O campo perfil é obrigatório.',

            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.digits' => 'O CPF deve conter exatamente :digits dígitos.',
            'cpf.unique' => 'Este CPF já está sendo utilizado.',

            'school_id.required' => 'O campo escola é obrigatório.',
            'school_id.exists' => 'A escola selecionada não existe.',

            'date_of_birth.required' => 'O campo data de nascimento é obrigatório.',
            'date_of_birth.date' => 'Informe uma data de nascimento válida.',

            'enrollment.required' => 'O campo matrícula é obrigatório.',
            'enrollment.string' => 'A matrícula deve ser um texto.',
            'enrollment.max' => 'A matrícula não pode exceder :max caracteres.',

            'grade.required' => 'O campo série é obrigatório.',
            'grade.string' => 'A série deve ser um texto.',
            'grade.max' => 'A série não pode exceder :max caracteres.',

            'class.required' => 'O campo turma é obrigatório.',
            'class.string' => 'A turma deve ser um texto.',
            'class.max' => 'A turma não pode exceder :max caracteres.',
            'class.regex' => 'O campo turma não pode começar com um caractere especial.',

            'regional.required' => 'O campo regional é obrigatório.',

            'responsible_name.required' => 'O campo nome do responsável é obrigatório.',
            'responsible_name.string' => 'O nome do responsável deve ser um texto.',
            'responsible_name.max' => 'O nome do responsável não pode exceder :max caracteres.',

            'responsible_phone.required' => 'O campo telefone do responsável é obrigatório.',
            'responsible_phone.string' => 'O telefone do responsável deve ser um texto.',
            'responsible_phone.max' => 'O telefone do responsável não pode exceder :max caracteres.',

            'responsible_email.required' => 'O campo email do responsável é obrigatório.',
            'responsible_email.email' => 'Informe um email válido para o responsável.',
            'responsible_email.max' => 'O email do responsável não pode exceder :max caracteres.',

            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.formato_cnpj' => 'Informe um CNPJ válido.',
            'cnpj.max' => 'O CNPJ não pode exceder :max caracteres.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.formato_cep' => 'Informe um CEP válido.',
            'cep.max' => 'O CEP não pode exceder :max caracteres.',

            'address.required' => 'O campo endereço é obrigatório.',
            'address.string' => 'O endereço deve ser um texto.',
            'address.max' => 'O endereço não pode exceder :max caracteres.',

            'number.required' => 'O campo número é obrigatório.',
            'number.string' => 'O número deve ser um texto.',
            'number.max' => 'O número não pode exceder :max caracteres.',

            'complement.string' => 'O complemento deve ser um texto.',
            'complement.max' => 'O complemento não pode exceder :max caracteres.',

            'district.required' => 'O campo bairro é obrigatório.',
            'district.string' => 'O bairro deve ser um texto.',
            'district.max' => 'O bairro não pode exceder :max caracteres.',

            'city.required' => 'O campo cidade é obrigatório.',
            'city.string' => 'A cidade deve ser um texto.',
            'city.max' => 'A cidade não pode exceder :max caracteres.',

            'state.required' => 'O campo estado é obrigatório.',
            'state.string' => 'O estado deve ser um texto.',
            'state.max' => 'O estado não pode exceder :max caracteres.',

            'observations.string' => 'As observações devem ser um texto.',
            'observations.max' => 'As observações não podem exceder :max caracteres.',

            'client_id' => 'O campo cliente é obrigatório.',
            'responsible' => 'O campo responsável é obrigatório.',
            'cpf_responsible.required' => 'O campo CPF do Responsável é obrigatório.',

            'password.required' => 'O campo senha é obrigatório.',
        ];
    }
}
