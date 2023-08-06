<?php

namespace PGT\Logsnag\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\Requests\LogsnagInsight;
use PGT\Logsnag\Client\Requests\LogsnagLog;

class LogsnagClient
{
    protected string $url = 'https://api.logsnag.com/v1/';

    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    protected function buildRequest(): PendingRequest
    {
        return Http::baseUrl($this->url)->withToken($this->token)->asJson();
    }

    public function log(LogsnagLog $request): Response
    {
        return $this->buildRequest()->post('/log', $request->toArray());
    }

    public function insight(LogsnagInsight $request): Response
    {
        return $this->buildRequest()->post('/insight', $request->toArray());
    }
}
