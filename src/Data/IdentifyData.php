<?php

namespace PGT\Logsnag\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class IdentifyData implements Arrayable
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function __construct(
        public string $project,
        public string $userId,
        public array $properties,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'project' => $this->project,
            'user_id' => $this->userId,
            'properties' => $this->properties,
        ];
    }
}
