<?php

namespace PGT\Logsnag\Data;

use Illuminate\Contracts\Support\Arrayable;
use PGT\Logsnag\Enums\Parser;

/**
 * @implements Arrayable<string, mixed>
 */
class LogData implements Arrayable
{
    public function __construct(
        public string $project,
        public string $channel,
        public string $event,
        public ?string $description = null,
        public ?string $icon = null,
        public bool $notify = false,
        public ?array $tags = null,
        public ?Parser $parser = null,
        public ?string $userId = null,
        public ?int $timestamp = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'project' => $this->project,
            'channel' => $this->channel,
            'event' => $this->event,
            'notify' => $this->notify,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->icon !== null) {
            $data['icon'] = $this->icon;
        }

        if ($this->tags !== null) {
            $data['tags'] = $this->tags;
        }

        if ($this->parser !== null) {
            $data['parser'] = $this->parser->value;
        }

        if ($this->userId !== null) {
            $data['user_id'] = $this->userId;
        }

        if ($this->timestamp !== null) {
            $data['timestamp'] = $this->timestamp;
        }

        return $data;
    }
}
