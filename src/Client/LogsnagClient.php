<?php

namespace PGT\Logsnag\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Data\IdentifyData;
use PGT\Logsnag\Data\InsightData;
use PGT\Logsnag\Data\LogData;
use PGT\Logsnag\Data\MutateInsightData;

class LogsnagClient
{
    protected string $url = 'https://api.logsnag.com/v1/';

    public function __construct(
        protected string $token,
        protected string $project,
    ) {}

    public function getProject(): string
    {
        return $this->project;
    }

    protected function buildRequest(): PendingRequest
    {
        return Http::baseUrl($this->url)->withToken($this->token)->asJson();
    }

    public function log(LogData $request): Response
    {
        return $this->buildRequest()->post('/log', $request->toArray());
    }

    public function insight(InsightData $request): Response
    {
        return $this->buildRequest()->post('/insight', $request->toArray());
    }

    public function mutateInsight(MutateInsightData $request): Response
    {
        return $this->buildRequest()->patch('/insight', $request->toArray());
    }

    public function identify(IdentifyData $request): Response
    {
        return $this->buildRequest()->post('/identify', $request->toArray());
    }
}
