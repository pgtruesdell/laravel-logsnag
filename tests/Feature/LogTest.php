<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Enums\Parser;
use PGT\Logsnag\Logsnag;

it('sends a log with required parameters only', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'User Signed Up',
    );

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/log'
            && $request->method() === 'POST'
            && $request['project'] === 'test-project'
            && $request['channel'] === 'my-channel'
            && $request['event'] === 'User Signed Up'
            && $request['notify'] === false
            && ! isset($request['description'])
            && ! isset($request['icon'])
            && ! isset($request['tags'])
            && ! isset($request['parser'])
            && ! isset($request['user_id'])
            && ! isset($request['timestamp']);
    });
});

it('sends a log with all parameters', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'User Signed Up',
        description: 'A new user signed up.',
        icon: '🎉',
        notify: true,
        tags: ['environment' => 'production'],
        parser: Parser::Markdown,
        userId: 'user-123',
        timestamp: 1700000000,
    );

    Http::assertSent(function ($request) {
        return $request['project'] === 'test-project'
            && $request['channel'] === 'my-channel'
            && $request['event'] === 'User Signed Up'
            && $request['description'] === 'A new user signed up.'
            && $request['icon'] === '🎉'
            && $request['notify'] === true
            && $request['tags'] === ['environment' => 'production']
            && $request['parser'] === 'markdown'
            && $request['user_id'] === 'user-123'
            && $request['timestamp'] === 1700000000;
    });
});

it('sends a log with tags', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'billing',
        event: 'Payment',
        tags: ['plan' => 'pro', 'cycle' => 'monthly'],
    );

    Http::assertSent(function ($request) {
        return $request['tags'] === ['plan' => 'pro', 'cycle' => 'monthly'];
    });
});

it('sends a log with text parser', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'Test',
        parser: Parser::Text,
    );

    Http::assertSent(function ($request) {
        return $request['parser'] === 'text';
    });
});

it('sends a log with user_id', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'Test',
        userId: 'user-456',
    );

    Http::assertSent(function ($request) {
        return $request['user_id'] === 'user-456';
    });
});

it('sends a log with timestamp', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'Test',
        timestamp: 1700000000,
    );

    Http::assertSent(function ($request) {
        return $request['timestamp'] === 1700000000;
    });
});

it('preserves notify false in the request', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(
        channel: 'my-channel',
        event: 'Test',
        notify: false,
    );

    Http::assertSent(function ($request) {
        return $request['notify'] === false;
    });
});

it('throws an exception on invalid token', function () {
    Http::fake([
        'api.logsnag.com/v1/log' => Http::response([
            'validation' => ['headers' => [['message' => 'Invalid token']]],
        ], 400),
    ]);

    app(Logsnag::class)->log(channel: 'test', event: 'Test');
})->throws(LogsnagClientException::class, 'Invalid Logsnag api token.');

it('throws an exception on server error', function () {
    Http::fake([
        'api.logsnag.com/v1/log' => Http::response(['message' => 'Server Error'], 500),
    ]);

    app(Logsnag::class)->log(channel: 'test', event: 'Test');
})->throws(LogsnagClientException::class);

it('throws a generic exception on 400 without validation structure', function () {
    Http::fake([
        'api.logsnag.com/v1/log' => Http::response(['message' => 'Bad Request'], 400),
    ]);

    expect(fn () => app(Logsnag::class)->log(channel: 'test', event: 'Test'))
        ->toThrow(LogsnagClientException::class, '400:');
});

it('includes the response on the exception', function () {
    Http::fake([
        'api.logsnag.com/v1/log' => Http::response(['message' => 'Not Found'], 404),
    ]);

    try {
        app(Logsnag::class)->log(channel: 'test', event: 'Test');
    } catch (LogsnagClientException $e) {
        expect($e->response)->not->toBeNull()
            ->and($e->response->status())->toBe(404);

        return;
    }

    $this->fail('Expected LogsnagClientException was not thrown.');
});

it('includes the error message in the exception', function () {
    Http::fake([
        'api.logsnag.com/v1/log' => Http::response(['message' => 'Project not found'], 422),
    ]);

    expect(fn () => app(Logsnag::class)->log(channel: 'test', event: 'Test'))
        ->toThrow(LogsnagClientException::class, 'Project not found');
});

it('sends the bearer token in the authorization header', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(channel: 'test', event: 'Test');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer test-token');
    });
});

it('sends requests as json', function () {
    Http::fake(['api.logsnag.com/v1/log' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->log(channel: 'test', event: 'Test');

    Http::assertSent(function ($request) {
        return str_contains($request->header('Content-Type')[0], 'application/json');
    });
});
