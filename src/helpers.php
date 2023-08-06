<?php

if (! function_exists('logsnag')) {
    function logsnag(string $channel, string $event, string $description = null, string $icon = null, bool $notify = false): void
    {
        app(\PGT\Logsnag\Logsnag::class)->log($channel, $event, $description, $icon, $notify);
    }
}

if (! function_exists('insight')) {
    function insight(string $title, string $value, string $icon = null): void
    {
        app(\PGT\Logsnag\Logsnag::class)->insight($title, $value, $icon);
    }
}
