<?php

namespace PGT\Logsnag\Client;

use Illuminate\Contracts\Support\Arrayable;

class LogsnagRequest implements Arrayable
{
    public function __construct(
        public string $project,
        public string $channel,
        public string $event,
        public ?string $description,
        public ?string $icon,
        public bool $notify,
    ) {}

    public function toArray(): array
    {
        return array_filter(get_object_vars($this));
    }
}
