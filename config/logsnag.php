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
     */
    'icons' => [
        'DEBUG' => 'ℹ️',
        'INFO' => 'ℹ️',
        'NOTICE' => '📌',
        'WARNING' => '⚠️',
        'ERROR' => '⚠️',
        'CRITICAL' => '🔥',
        'ALERT' => '🔔️',
        'EMERGENCY' => '💀',
    ],
];
