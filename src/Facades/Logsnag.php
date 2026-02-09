<?php

namespace PGT\Logsnag\Facades;

use Illuminate\Support\Facades\Facade;
use PGT\Logsnag\Enums\Parser;

/**
 * @method static void log(string $channel, string $event, ?string $description = null, ?string $icon = null, bool $notify = false, ?array $tags = null, ?Parser $parser = null, ?string $userId = null, ?int $timestamp = null)
 * @method static void insight(string $title, string|int|float $value, ?string $icon = null)
 * @method static void mutateInsight(string $title, int|float $incrementBy, ?string $icon = null)
 * @method static void identify(string $userId, array $properties)
 *
 * @see \PGT\Logsnag\Logsnag
 */
class Logsnag extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \PGT\Logsnag\Logsnag::class;
    }
}
