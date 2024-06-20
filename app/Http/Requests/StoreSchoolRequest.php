<?php

namespace App\Http\Requests;

use App\Models\Client;

class StoreSchoolRequest extends BasicRequest
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

        $rules['client_id'] = ['required'];
        $rules['name'][] = 'required';
        $rules['education_level'] = ['required'];
        $rules['responsible'] = ['required'];
        $rules['cnpj'] = ['required', 'formato_cnpj', 'unique:' . Client::class];
        $rules["cep"] = ['required', 'formato_cep'];
        $rules["address"] = ['required', 'max:255'];
        $rules["number"] = ['required'];
        $rules["complement"] = ['string', 'max:255'];
        $rules["district"] = ['required', 'string', 'max:255'];
        $rules["city"] = ['required', 'string', 'max:255'];
        $rules["state"] = ['string'];

        return $rules;
    }
}
