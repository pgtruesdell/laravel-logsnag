<?php

namespace PGT\Logsnag\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class MutateInsightData implements Arrayable
{
    /**
     * @param  array<string, int|float>  $value
     */
    public function __construct(
        public string $project,
        public string $title,
        public array $value,
        public ?string $icon = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'project' => $this->project,
            'title' => $this->title,
            'value' => $this->value,
        ];

        if ($this->icon !== null) {
            $data['icon'] = $this->icon;
        }

        return $data;
    }
}
