<?php

use PGT\Logsnag\Data\InsightData;

it('converts to array with string value', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'User Count',
        value: '100',
    );

    expect($data->toArray())->toBe([
        'project' => 'my-project',
        'title' => 'User Count',
        'value' => '100',
    ]);
});

it('accepts integer values', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'User Count',
        value: 42,
    );

    expect($data->toArray()['value'])->toBe(42);
});

it('accepts float values', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Conversion Rate',
        value: 3.14,
    );

    expect($data->toArray()['value'])->toBe(3.14);
});

it('includes icon when set', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Revenue',
        value: 9999,
        icon: '💰',
    );

    expect($data->toArray())->toHaveKey('icon', '💰');
});

it('omits icon when null', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Revenue',
        value: 100,
    );

    expect($data->toArray())->not->toHaveKey('icon');
});

it('accepts zero as a valid value', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Errors',
        value: 0,
    );

    expect($data->toArray()['value'])->toBe(0);
});

it('accepts negative values', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Balance',
        value: -50.25,
    );

    expect($data->toArray()['value'])->toBe(-50.25);
});

it('implements Arrayable', function () {
    $data = new InsightData(
        project: 'my-project',
        title: 'Test',
        value: 1,
    );

    expect($data)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
});
