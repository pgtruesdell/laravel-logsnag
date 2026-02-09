<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Logsnag;

it('sends an identify request with user_id and properties', function () {
    Http::fake(['api.logsnag.com/v1/identify' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->identify(
        userId: 'user-123',
        properties: ['name' => 'John', 'email' => 'john@example.com'],
    );

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/identify'
            && $request->method() === 'POST'
            && $request['project'] === 'test-project'
            && $request['user_id'] === 'user-123'
            && $request['properties'] === ['name' => 'John', 'email' => 'john@example.com'];
    });
});

it('supports empty properties', function () {
    Http::fake(['api.logsnag.com/v1/identify' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->identify(userId: 'user-456', properties: []);

    Http::assertSent(function ($request) {
        return $request['user_id'] === 'user-456'
            && $request['properties'] === [];
    });
});

it('throws an exception on invalid token', function () {
    Http::fake([
        'api.logsnag.com/v1/identify' => Http::response([
            'validation' => ['headers' => [['message' => 'Invalid token']]],
        ], 400),
    ]);

    app(Logsnag::class)->identify(userId: 'user-123', properties: []);
})->throws(LogsnagClientException::class, 'Invalid Logsnag api token.');

it('throws an exception on server error', function () {
    Http::fake([
        'api.logsnag.com/v1/identify' => Http::response(['message' => 'Server Error'], 500),
    ]);

    app(Logsnag::class)->identify(userId: 'user-123', properties: []);
})->throws(LogsnagClientException::class);
