<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\LogsnagClient;
use PGT\Logsnag\Data\IdentifyData;
use PGT\Logsnag\Data\InsightData;
use PGT\Logsnag\Data\LogData;
use PGT\Logsnag\Data\MutateInsightData;

beforeEach(function () {
    Http::fake(['api.logsnag.com/*' => Http::response(['message' => 'ok'], 200)]);
});

it('stores project via constructor', function () {
    $client = new LogsnagClient(token: 'tk_test', project: 'my-proj');

    expect($client->getProject())->toBe('my-proj');
});

it('sends the bearer token on every request', function () {
    $client = new LogsnagClient(token: 'tk_secret_123', project: 'proj');

    $client->log(new LogData(project: 'proj', channel: 'ch', event: 'ev'));

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer tk_secret_123');
    });
});

it('sends log as POST to /log', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->log(new LogData(project: 'proj', channel: 'ch', event: 'ev'));

    Http::assertSent(function ($request) {
        return $request->method() === 'POST'
            && $request->url() === 'https://api.logsnag.com/v1/log';
    });
});

it('sends insight as POST to /insight', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->insight(new InsightData(project: 'proj', title: 'Users', value: 10));

    Http::assertSent(function ($request) {
        return $request->method() === 'POST'
            && $request->url() === 'https://api.logsnag.com/v1/insight';
    });
});

it('sends mutateInsight as PATCH to /insight', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->mutateInsight(new MutateInsightData(project: 'proj', title: 'Users', value: ['$inc' => 1]));

    Http::assertSent(function ($request) {
        return $request->method() === 'PATCH'
            && $request->url() === 'https://api.logsnag.com/v1/insight';
    });
});

it('sends identify as POST to /identify', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->identify(new IdentifyData(project: 'proj', userId: 'u1', properties: []));

    Http::assertSent(function ($request) {
        return $request->method() === 'POST'
            && $request->url() === 'https://api.logsnag.com/v1/identify';
    });
});

it('sends requests with json content type', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->log(new LogData(project: 'proj', channel: 'ch', event: 'ev'));

    Http::assertSent(function ($request) {
        return str_contains($request->header('Content-Type')[0], 'application/json');
    });
});

it('returns a Response object from each method', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $logResponse = $client->log(new LogData(project: 'proj', channel: 'ch', event: 'ev'));
    $insightResponse = $client->insight(new InsightData(project: 'proj', title: 'T', value: 1));
    $mutateResponse = $client->mutateInsight(new MutateInsightData(project: 'proj', title: 'T', value: ['$inc' => 1]));
    $identifyResponse = $client->identify(new IdentifyData(project: 'proj', userId: 'u', properties: []));

    expect($logResponse)->toBeInstanceOf(\Illuminate\Http\Client\Response::class)
        ->and($insightResponse)->toBeInstanceOf(\Illuminate\Http\Client\Response::class)
        ->and($mutateResponse)->toBeInstanceOf(\Illuminate\Http\Client\Response::class)
        ->and($identifyResponse)->toBeInstanceOf(\Illuminate\Http\Client\Response::class);
});

it('passes the DTO payload to the request body', function () {
    $client = new LogsnagClient(token: 'tk', project: 'proj');

    $client->log(new LogData(
        project: 'proj',
        channel: 'billing',
        event: 'Payment',
        description: 'Paid $50',
        notify: true,
    ));

    Http::assertSent(function ($request) {
        return $request['project'] === 'proj'
            && $request['channel'] === 'billing'
            && $request['event'] === 'Payment'
            && $request['description'] === 'Paid $50'
            && $request['notify'] === true;
    });
});
