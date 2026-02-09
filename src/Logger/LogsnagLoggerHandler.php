<?php

namespace PGT\Logsnag\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PGT\Logsnag\Logsnag;

class LogsnagLoggerHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        // TODO: Implement write() method.
//        ray($record);

//        if ($record->)

        Logsnag::log(
            channel: config('logsnag.channel'),
            event: $record->message,
            description: json_encode($record->context),
            icon: config('logsnag.icon')[$record->level->getName()],
        );
    }
}
