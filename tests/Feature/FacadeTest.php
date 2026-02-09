<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Facades\Logsnag;

beforeEach(function () {
    Http::fake(['api.logsnag.com/*' => Http::response(['message' => 'ok'], 200)]);
});

it('forwards log calls to the Logsnag instance', function () {
    Logsnag::log(channel: 'facade-test', event: 'Facade Event');

    Http::assertSent(function ($request) {
        return $request['channel'] === 'facade-test'
            && $request['event'] === 'Facade Event';
    });
});

it('forwards insight calls to the Logsnag instance', function () {
    Logsnag::insight(title: 'Facade Metric', value: 99);

    Http::assertSent(function ($request) {
        return $request['title'] === 'Facade Metric'
            && $request['value'] === 99;
    });
});

it('forwards mutateInsight calls to the Logsnag instance', function () {
    Logsnag::mutateInsight(title: 'Counter', incrementBy: 3);

    Http::assertSent(function ($request) {
        return $request->method() === 'PATCH'
            && $request['title'] === 'Counter'
            && $request['value'] === ['$inc' => 3];
    });
});

it('forwards identify calls to the Logsnag instance', function () {
    Logsnag::identify(userId: 'facade-user', properties: ['role' => 'admin']);

    Http::assertSent(function ($request) {
        return $request['user_id'] === 'facade-user'
            && $request['properties'] === ['role' => 'admin'];
    });
});
