<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Logsnag;

it('sends an insight with a string value', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->insight(title: 'User Count', value: '100');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/insight'
            && $request->method() === 'POST'
            && $request['project'] === 'test-project'
            && $request['title'] === 'User Count'
            && $request['value'] === '100'
            && ! isset($request['icon']);
    });
});

it('sends an insight with an integer value', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->insight(title: 'User Count', value: 42);

    Http::assertSent(function ($request) {
        return $request['value'] === 42;
    });
});

it('sends an insight with a float value', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->insight(title: 'Conversion Rate', value: 3.14);

    Http::assertSent(function ($request) {
        return $request['value'] === 3.14;
    });
});

it('sends an insight with an icon', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->insight(title: 'Revenue', value: 9999, icon: '💰');

    Http::assertSent(function ($request) {
        return $request['icon'] === '💰';
    });
});

it('throws an exception on invalid token', function () {
    Http::fake([
        'api.logsnag.com/v1/insight' => Http::response([
            'validation' => ['headers' => [['message' => 'Invalid token']]],
        ], 400),
    ]);

    app(Logsnag::class)->insight(title: 'Test', value: 1);
})->throws(LogsnagClientException::class, 'Invalid Logsnag api token.');

it('throws an exception on server error', function () {
    Http::fake([
        'api.logsnag.com/v1/insight' => Http::response(['message' => 'Server Error'], 500),
    ]);

    app(Logsnag::class)->insight(title: 'Test', value: 1);
})->throws(LogsnagClientException::class);
