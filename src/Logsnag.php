<?php

namespace PGT\Logsnag;

use Illuminate\Http\Client\Response;
use PGT\Logsnag\Client\LogsnagClient;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Data\IdentifyData;
use PGT\Logsnag\Data\InsightData;
use PGT\Logsnag\Data\LogData;
use PGT\Logsnag\Data\MutateInsightData;
use PGT\Logsnag\Enums\Parser;

class Logsnag
{
    public function __construct(protected readonly LogsnagClient $client) {}

    public function log(
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
        $response = $this->client->log(new LogData(
            project: $this->client->getProject(),
            channel: $channel,
            event: $event,
            description: $description,
            icon: $icon,
            notify: $notify,
            tags: $tags,
            parser: $parser,
            userId: $userId,
            timestamp: $timestamp,
        ));

        $this->handleResponse($response);
    }

    public function insight(string $title, string|int|float $value, ?string $icon = null): void
    {
        $response = $this->client->insight(new InsightData(
            project: $this->client->getProject(),
            title: $title,
            value: $value,
            icon: $icon,
        ));

        $this->handleResponse($response);
    }

    public function mutateInsight(string $title, int|float $incrementBy, ?string $icon = null): void
    {
        $response = $this->client->mutateInsight(new MutateInsightData(
            project: $this->client->getProject(),
            title: $title,
            value: ['$inc' => $incrementBy],
            icon: $icon,
        ));

        $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function identify(string $userId, array $properties): void
    {
        $response = $this->client->identify(new IdentifyData(
            project: $this->client->getProject(),
            userId: $userId,
            properties: $properties,
        ));

        $this->handleResponse($response);
    }

    /**
     * @throws LogsnagClientException
     */
    private function handleResponse(Response $response): void
    {
        if ($response->successful()) {
            return;
        }

        if ($response->status() === 400 && $response->json('validation.headers.0.message') !== null) {
            throw new LogsnagClientException('Invalid Logsnag api token.', response: $response);
        }

        throw new LogsnagClientException(
            $response->status().':  '.$response->json('message').' - '.$response->body(),
            response: $response,
        );
    }
}
