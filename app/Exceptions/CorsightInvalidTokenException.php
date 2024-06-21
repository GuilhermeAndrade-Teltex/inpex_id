<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CorsightInvalidTokenException extends Exception
{
    public function __construct($message = "Invalid or expired Corsight API token.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // Método para logging mais detalhado (opcional)
    public function report()
    {
        Log::error('Corsight Invalid Token Error: ' . $this->getMessage(), [
            'code' => $this->getCode(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
