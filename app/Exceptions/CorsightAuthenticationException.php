<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CorsightAuthenticationException extends Exception
{
    public function __construct($message = "Authentication with Corsight API failed.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // Método para logging mais detalhado (opcional)
    public function report()
    {
        Log::error('Corsight Authentication Error: ' . $this->getMessage(), [
            'code' => $this->getCode(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
