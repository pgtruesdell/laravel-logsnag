<?php

use PGT\Logsnag\Data\LogData;
use PGT\Logsnag\Enums\Parser;

it('converts required fields to array', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'User Signed Up',
    );

    expect($data->toArray())->toBe([
        'project' => 'my-project',
        'channel' => 'my-channel',
        'event' => 'User Signed Up',
        'notify' => false,
    ]);
});

it('includes all optional fields when set', function () {
    $data = new LogData(
        project: 'my-project',
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

    expect($data->toArray())->toBe([
        'project' => 'my-project',
        'channel' => 'my-channel',
        'event' => 'User Signed Up',
        'notify' => true,
        'description' => 'A new user signed up.',
        'icon' => '🎉',
        'tags' => ['environment' => 'production'],
        'parser' => 'markdown',
        'user_id' => 'user-123',
        'timestamp' => 1700000000,
    ]);
});

it('omits null optional fields', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test Event',
    );

    $array = $data->toArray();

    expect($array)->not->toHaveKey('description')
        ->not->toHaveKey('icon')
        ->not->toHaveKey('tags')
        ->not->toHaveKey('parser')
        ->not->toHaveKey('user_id')
        ->not->toHaveKey('timestamp');
});

it('preserves notify false in output', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test Event',
        notify: false,
    );

    expect($data->toArray())->toHaveKey('notify', false);
});

it('maps userId to user_id in array output', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
        userId: 'user-456',
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('user_id', 'user-456')
        ->not->toHaveKey('userId');
});

it('converts parser enum to its string value', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
        parser: Parser::Text,
    );

    expect($data->toArray()['parser'])->toBe('text');
});

it('includes timestamp zero as a valid value', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
        timestamp: 0,
    );

    expect($data->toArray())->toHaveKey('timestamp', 0);
});

it('includes empty string description', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
        description: '',
    );

    expect($data->toArray())->toHaveKey('description', '');
});

it('includes empty tags array', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
        tags: [],
    );

    expect($data->toArray())->toHaveKey('tags', []);
});

it('implements Arrayable', function () {
    $data = new LogData(
        project: 'my-project',
        channel: 'my-channel',
        event: 'Test',
    );

    expect($data)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
});
