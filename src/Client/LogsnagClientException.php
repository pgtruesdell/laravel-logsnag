<?php

namespace PGT\Logsnag\Client;

use Exception;
use Illuminate\Http\Client\Response;

class LogsnagClientException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        public ?Response $response = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
