<?php

namespace App\Exceptions;

use Exception;

class PaymentProviderException extends Exception
{
    public function __construct(
        string $message,
        public int $statusCode = 0,
        public array $responseBody = [],
    ) {
        parent::__construct($message, $statusCode);
    }
}
