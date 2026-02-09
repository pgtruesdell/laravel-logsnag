<?php

namespace PGT\Logsnag\Logger;

use Monolog\Level;
use Monolog\Logger;

class LogsnagLogger
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __invoke(array $config): Logger
    {
        $level = Level::fromName($config['level'] ?? 'debug');

        return new Logger(
            name: 'logsnag',
            handlers: [
                new LogsnagLoggerHandler($level),
            ],
        );
    }
}
