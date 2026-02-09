<?php

return [
    /**
     * The project name.
     */
    'project' => env('LOGSNAG_PROJECT', 'my-laravel-app'),

    /**
     * The default channel name for the monolog driver.
     */
    'channel' => env('LOGSNAG_CHANNEL', 'app-events'),

    /**
     * Your logsnag API token.
     */
    'token' => env('LOGSNAG_TOKEN', ''),

    /**
     * A mapping of icons for logging.
     * Keys match Monolog\Level enum ->name values.
     */
    'icons' => [
        'Debug' => 'ℹ️',
        'Info' => 'ℹ️',
        'Notice' => '📌',
        'Warning' => '⚠️',
        'Error' => '⚠️',
        'Critical' => '🔥',
        'Alert' => '🔔️',
        'Emergency' => '💀',
    ],
];
