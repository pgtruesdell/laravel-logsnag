<?php

namespace PGT\Logsnag\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use PGT\Logsnag\Logsnag;

class LogsnagLoggerHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        $icon = config('logsnag.icons')[$record->level->name] ?? null;
        $notify = $record->level->value >= Level::Error->value;

        $description = ! empty($record->context)
            ? json_encode($record->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            : null;

        app(Logsnag::class)->log(
            channel: config('logsnag.channel'),
            event: $record->message,
            description: $description ?: null,
            icon: $icon,
            notify: $notify,
        );
    }
}
