<?php

use PGT\Logsnag\Data\IdentifyData;

it('converts to array with user_id mapping', function () {
    $data = new IdentifyData(
        project: 'my-project',
        userId: 'user-123',
        properties: ['name' => 'John', 'email' => 'john@example.com'],
    );

    expect($data->toArray())->toBe([
        'project' => 'my-project',
        'user_id' => 'user-123',
        'properties' => ['name' => 'John', 'email' => 'john@example.com'],
    ]);
});

it('maps userId to user_id in array output', function () {
    $data = new IdentifyData(
        project: 'my-project',
        userId: 'user-456',
        properties: [],
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('user_id', 'user-456')
        ->not->toHaveKey('userId');
});

it('supports empty properties array', function () {
    $data = new IdentifyData(
        project: 'my-project',
        userId: 'user-789',
        properties: [],
    );

    expect($data->toArray()['properties'])->toBe([]);
});

it('supports nested property structures', function () {
    $data = new IdentifyData(
        project: 'my-project',
        userId: 'user-1',
        properties: [
            'address' => ['city' => 'NYC', 'zip' => '10001'],
            'tags' => ['vip', 'beta'],
        ],
    );

    $array = $data->toArray();

    expect($array['properties']['address'])->toBe(['city' => 'NYC', 'zip' => '10001'])
        ->and($array['properties']['tags'])->toBe(['vip', 'beta']);
});

it('implements Arrayable', function () {
    $data = new IdentifyData(
        project: 'my-project',
        userId: 'user-1',
        properties: [],
    );

    expect($data)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
});
