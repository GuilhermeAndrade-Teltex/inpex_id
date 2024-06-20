<?php

namespace App\Http\Requests;

use App\Models\Client;

class StoreClientRequest extends BasicRequest
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

        $data = $this->validationData();

        $cep = str_replace("-", "", $data["cep"]);
        $cnpj = str_replace([".", "/", "-"], "", $data["cnpj"]);

        $this->request->set("cep", $cep);
        $this->request->set("cnpj", $cnpj);

        $rules['name'][] = 'required';
        $rules['cnpj'] = ['required', 'digits:14', 'unique:' . Client::class];
        $rules["cep"] = ['required', 'digits:8'];
        $rules["address"] = ['required', 'max:255'];
        $rules["number"] = ['required'];
        $rules["complement"] = ['string', 'max:255'];
        $rules["district"] = ['required', 'string', 'max:255'];
        $rules["city"] = ['required', 'string', 'max:255'];
        $rules["state"] = ['string'];

        return $rules;
    }
}
