<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PGT\Logsnag\Logger\LogsnagLogger;

beforeEach(function () {
    Http::fake(['api.logsnag.com/*' => Http::response(['message' => 'ok'], 200)]);

    config()->set('logging.channels.logsnag', [
        'driver' => 'custom',
        'via' => LogsnagLogger::class,
        'level' => 'debug',
    ]);
});

it('sends a log via the monolog channel', function () {
    Log::channel('logsnag')->info('Test message');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/log'
            && $request['channel'] === 'test-channel'
            && $request['event'] === 'Test message'
            && $request['notify'] === false;
    });
});

it('maps the log level to the correct icon', function () {
    Log::channel('logsnag')->warning('Warning message');

    Http::assertSent(function ($request) {
        return $request['icon'] === '⚠️';
    });
});

it('auto-notifies on error level and above', function () {
    Log::channel('logsnag')->error('Error occurred');

    Http::assertSent(function ($request) {
        return $request['notify'] === true;
    });
});

it('does not auto-notify below error level', function () {
    Log::channel('logsnag')->warning('Just a warning');

    Http::assertSent(function ($request) {
        return $request['notify'] === false;
    });
});

it('appends context as json in description', function () {
    Log::channel('logsnag')->info('User action', ['user_id' => 123, 'action' => 'login']);

    Http::assertSent(function ($request) {
        $description = $request['description'];

        return str_contains($description, '"user_id": 123')
            && str_contains($description, '"action": "login"');
    });
});

it('sends null description when context is empty', function () {
    Log::channel('logsnag')->info('Simple message');

    Http::assertSent(function ($request) {
        return ! isset($request['description']);
    });
});

it('respects the configured log level', function () {
    config()->set('logging.channels.logsnag', [
        'driver' => 'custom',
        'via' => LogsnagLogger::class,
        'level' => 'error',
    ]);

    Log::channel('logsnag')->info('Should be filtered');

    Http::assertNothingSent();
});

it('auto-notifies on critical level', function () {
    Log::channel('logsnag')->critical('Critical failure');

    Http::assertSent(function ($request) {
        return $request['notify'] === true
            && $request['icon'] === '🔥';
    });
});

it('auto-notifies on emergency level', function () {
    Log::channel('logsnag')->emergency('System down');

    Http::assertSent(function ($request) {
        return $request['notify'] === true
            && $request['icon'] === '💀';
    });
});

it('maps debug level to the correct icon', function () {
    Log::channel('logsnag')->debug('Debug info');

    Http::assertSent(function ($request) {
        return $request['icon'] === 'ℹ️';
    });
});

it('maps notice level to the correct icon', function () {
    Log::channel('logsnag')->notice('Notice this');

    Http::assertSent(function ($request) {
        return $request['icon'] === '📌'
            && $request['notify'] === false;
    });
});

it('falls back to null icon when level is missing from config', function () {
    config()->set('logsnag.icons', []);

    Log::channel('logsnag')->info('No icon configured');

    Http::assertSent(function ($request) {
        return ! isset($request['icon']);
    });
});

it('handles complex nested context in description', function () {
    Log::channel('logsnag')->info('Complex event', [
        'user' => ['id' => 1, 'name' => 'John'],
        'items' => [['sku' => 'A1'], ['sku' => 'B2']],
        'url' => 'https://example.com/path',
    ]);

    Http::assertSent(function ($request) {
        $description = $request['description'];

        return str_contains($description, '"name": "John"')
            && str_contains($description, '"sku": "A1"')
            && str_contains($description, 'https://example.com/path');
    });
});

it('uses the configured channel name', function () {
    config()->set('logsnag.channel', 'custom-channel');

    Log::channel('logsnag')->info('Test');

    Http::assertSent(function ($request) {
        return $request['channel'] === 'custom-channel';
    });
});

it('defaults to debug level when level is not specified in config', function () {
    config()->set('logging.channels.logsnag', [
        'driver' => 'custom',
        'via' => LogsnagLogger::class,
    ]);

    Log::channel('logsnag')->debug('Should be sent');

    Http::assertSent(function ($request) {
        return $request['event'] === 'Should be sent';
    });
});
