<?php

use Illuminate\Support\Facades\Http;
use PGT\Logsnag\Enums\Parser;

beforeEach(function () {
    Http::fake([
        'api.logsnag.com/*' => Http::response(['message' => 'ok'], 200),
    ]);
});

it('logsnag helper sends a log request', function () {
    logsnag(channel: 'my-channel', event: 'Test Event');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/log'
            && $request['channel'] === 'my-channel'
            && $request['event'] === 'Test Event';
    });
});

it('logsnag helper passes all parameters', function () {
    logsnag(
        channel: 'my-channel',
        event: 'Test',
        description: 'Desc',
        icon: '🎉',
        notify: true,
        tags: ['env' => 'prod'],
        parser: Parser::Markdown,
        userId: 'user-1',
        timestamp: 1700000000,
    );

    Http::assertSent(function ($request) {
        return $request['description'] === 'Desc'
            && $request['icon'] === '🎉'
            && $request['notify'] === true
            && $request['tags'] === ['env' => 'prod']
            && $request['parser'] === 'markdown'
            && $request['user_id'] === 'user-1'
            && $request['timestamp'] === 1700000000;
    });
});

it('insight helper sends an insight request', function () {
    insight(title: 'Users', value: 42, icon: '👥');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/insight'
            && $request['title'] === 'Users'
            && $request['value'] === 42
            && $request['icon'] === '👥';
    });
});

it('mutate_insight helper sends a PATCH request', function () {
    mutate_insight(title: 'Users', incrementBy: 5);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/insight'
            && $request->method() === 'PATCH'
            && $request['value'] === ['$inc' => 5];
    });
});

it('identify helper sends an identify request', function () {
    identify(userId: 'user-123', properties: ['name' => 'John']);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.logsnag.com/v1/identify'
            && $request['user_id'] === 'user-123'
            && $request['properties'] === ['name' => 'John'];
    });
});
