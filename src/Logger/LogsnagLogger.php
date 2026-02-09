<?php

namespace PGT\Logsnag\Logger;

use Monolog\Logger;

class LogsnagLogger
{
    public function __invoke(array $config): Logger
    {
        return new Logger(
            name: 'logsnag',
            handlers: [
                new LogsnagLoggerHandler(),
            ],
        );
    }
}
