<?php

namespace PGT\Logsnag;

use PGT\Logsnag\Client\LogsnagClient;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Client\LogsnagRequest;

class Logsnag
{
    protected LogsnagClient $client;

    public function __construct(LogsnagClient $client)
    {
        $this->client = $client;
    }

    public function log(string $channel, string $event, string $description = null, string $icon = null, bool $notify = false): void
    {
        $response = $this->client->log(new LogsnagRequest(
            project: config('logsnag.project'),
            channel: $channel,
            event: $event,
            description: $description,
            icon: $icon,
            notify: $notify,
        ));

        if ($response->failed() && $response->status() === 400 && $response->json('validation.headers.0.message') !== null) {
            throw new LogsnagClientException('Invalid Logsnag api token.');
        }

        if ($response->failed()) {
            throw new LogsnagClientException($response->status().':  '.$response->json('message').' - '.$response->body());
        }
    }
}
