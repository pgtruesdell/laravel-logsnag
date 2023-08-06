<?php

if (! function_exists('logsnag')) {
    function logsnag(string $channel, string $event, string $description = null, string $icon = null, bool $notify = false): void
    {
        app(\PGT\Logsnag\Logsnag::class)->log($channel, $event, $description, $icon, $notify);
    }
}
