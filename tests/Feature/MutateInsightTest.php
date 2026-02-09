<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Logsnag;

it('sends a PATCH request to mutate an insight', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->mutateInsight(title: 'User Count', incrementBy: 1);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/insight'
            && $request->method() === 'PATCH'
            && $request['project'] === 'test-project'
            && $request['title'] === 'User Count'
            && $request['value'] === ['$inc' => 1];
    });
});

it('supports negative increment for decrement', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->mutateInsight(title: 'User Count', incrementBy: -5);

    Http::assertSent(function ($request) {
        return $request['value'] === ['$inc' => -5];
    });
});

it('supports float increment values', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->mutateInsight(title: 'Revenue', incrementBy: 19.99);

    Http::assertSent(function ($request) {
        return $request['value'] === ['$inc' => 19.99];
    });
});

it('sends a mutate insight with an icon', function () {
    Http::fake(['api.logsnag.com/v1/insight' => Http::response(['message' => 'ok'], 200)]);

    app(Logsnag::class)->mutateInsight(title: 'Revenue', incrementBy: 10, icon: '💰');

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

    app(Logsnag::class)->mutateInsight(title: 'Test', incrementBy: 1);
})->throws(LogsnagClientException::class, 'Invalid Logsnag api token.');

it('throws an exception on server error', function () {
    Http::fake([
        'api.logsnag.com/v1/insight' => Http::response(['message' => 'Server Error'], 500),
    ]);

    app(Logsnag::class)->mutateInsight(title: 'Test', incrementBy: 1);
})->throws(LogsnagClientException::class);
