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
        $rules['name'] = ['required'];
        $rules['responsible'] = ['nullable'];
        $rules['regional'] = ['required'];
        $rules["cep"] = ['nullable', 'formato_cep'];
        $rules["address"] = ['nullable', 'max:255'];
        $rules["number"] = ['nullable'];
        $rules["complement"] = ['nullable', 'string', 'max:255'];
        $rules["district"] = ['nullable', 'string', 'max:255'];
        $rules["city"] = ['nullable', 'string', 'max:255'];
        $rules["state"] = ['nullable', 'string'];

        return $rules;
    }
}
