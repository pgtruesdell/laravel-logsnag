<?php

use PGT\Logsnag\Data\MutateInsightData;

it('converts to array with inc structure', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'User Count',
        value: ['$inc' => 1],
    );

    expect($data->toArray())->toBe([
        'project' => 'my-project',
        'title' => 'User Count',
        'value' => ['$inc' => 1],
    ]);
});

it('supports negative increment for decrement', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'User Count',
        value: ['$inc' => -5],
    );

    expect($data->toArray()['value'])->toBe(['$inc' => -5]);
});

it('supports float increment values', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'Revenue',
        value: ['$inc' => 19.99],
    );

    expect($data->toArray()['value'])->toBe(['$inc' => 19.99]);
});

it('includes icon when set', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'Revenue',
        value: ['$inc' => 10],
        icon: '💰',
    );

    expect($data->toArray())->toHaveKey('icon', '💰');
});

it('omits icon when null', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'Revenue',
        value: ['$inc' => 10],
    );

    expect($data->toArray())->not->toHaveKey('icon');
});

it('supports zero increment value', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'Counter',
        value: ['$inc' => 0],
    );

    expect($data->toArray()['value'])->toBe(['$inc' => 0]);
});

it('implements Arrayable', function () {
    $data = new MutateInsightData(
        project: 'my-project',
        title: 'Test',
        value: ['$inc' => 1],
    );

    expect($data)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
});
