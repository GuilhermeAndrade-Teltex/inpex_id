<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ValidationService
{
    public function validateFormRequest(FormRequest $formRequest)
    {
        $validator = Validator::make(
            $formRequest->all(),
            $formRequest->rules(),
            $formRequest->messages()
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }
}