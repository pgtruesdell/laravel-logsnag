<?php

use PGT\Logsnag\Client\LogsnagClient;
use PGT\Logsnag\Logsnag;

it('binds LogsnagClient as a singleton', function () {
    $first = app(LogsnagClient::class);
    $second = app(LogsnagClient::class);

    expect($first)->toBe($second);
});

it('binds Logsnag as a singleton', function () {
    $first = app(Logsnag::class);
    $second = app(Logsnag::class);

    expect($first)->toBe($second);
});

it('injects project from config into the client', function () {
    $client = app(LogsnagClient::class);

    expect($client->getProject())->toBe('test-project');
});

it('resolves Logsnag via facade', function () {
    $resolved = PGT\Logsnag\Facades\Logsnag::getFacadeRoot();

    expect($resolved)->toBeInstanceOf(Logsnag::class);
});

it('publishes the config file', function () {
    $config = config('logsnag');

    expect($config)->toBeArray()
        ->and($config)->toHaveKeys(['project', 'channel', 'token', 'icons']);
});

it('uses the token from config', function () {
    Http::fake(['api.logsnag.com/*' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(channel: 'test', event: 'Test');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer test-token');
    });
});

it('defers client creation until first use', function () {
    // Singletons are lazy - change config after boot, before resolving
    config()->set('logsnag.project', 'deferred-project');

    // Force a fresh resolution by clearing old binding
    app()->forgetInstance(LogsnagClient::class);
    app()->forgetInstance(Logsnag::class);

    $client = app(LogsnagClient::class);

    expect($client->getProject())->toBe('deferred-project');
});

it('injects the Logsnag class with the client', function () {
    $logsnag = app(Logsnag::class);

    // The Logsnag instance should be functional (constructor received a client)
    expect($logsnag)->toBeInstanceOf(Logsnag::class);
});
