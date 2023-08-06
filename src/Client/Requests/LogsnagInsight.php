<?php

namespace PGT\Logsnag\Client\Requests;

class LogsnagInsight
{
    public function __construct(
        public string $project,
        public string $title,
        public string $value,
        public ?string $icon,
    ) {
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this));
    }
}
