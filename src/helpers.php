<?php

use PGT\Logsnag\Enums\Parser;

if (! function_exists('logsnag')) {
    function logsnag(
        string $channel,
        string $event,
        ?string $description = null,
        ?string $icon = null,
        bool $notify = false,
        ?array $tags = null,
        ?Parser $parser = null,
        ?string $userId = null,
        ?int $timestamp = null,
    ): void {
        app(\PGT\Logsnag\Logsnag::class)->log($channel, $event, $description, $icon, $notify, $tags, $parser, $userId, $timestamp);
    }
}

if (! function_exists('insight')) {
    function insight(string $title, string|int|float $value, ?string $icon = null): void
    {
        app(\PGT\Logsnag\Logsnag::class)->insight($title, $value, $icon);
    }
}

if (! function_exists('mutate_insight')) {
    function mutate_insight(string $title, int|float $incrementBy, ?string $icon = null): void
    {
        app(\PGT\Logsnag\Logsnag::class)->mutateInsight($title, $incrementBy, $icon);
    }
}

if (! function_exists('identify')) {
    /**
     * @param  array<string, mixed>  $properties
     */
    function identify(string $userId, array $properties): void
    {
        app(\PGT\Logsnag\Logsnag::class)->identify($userId, $properties);
    }
}
