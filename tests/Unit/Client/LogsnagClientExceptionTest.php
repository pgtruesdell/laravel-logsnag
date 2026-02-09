<?php

use Illuminate\Http\Client\Response;
use PGT\Logsnag\Client\LogsnagClientException;

it('can be constructed with just a message', function () {
    $exception = new LogsnagClientException('Something went wrong');

    expect($exception->getMessage())->toBe('Something went wrong')
        ->and($exception->getCode())->toBe(0)
        ->and($exception->getPrevious())->toBeNull()
        ->and($exception->response)->toBeNull();
});

it('stores the response when provided', function () {
    $response = new Response(new \GuzzleHttp\Psr7\Response(422, [], '{"message": "Validation failed"}'));

    $exception = new LogsnagClientException('Validation failed', response: $response);

    expect($exception->response)->toBe($response)
        ->and($exception->response->status())->toBe(422);
});

it('accepts a custom error code', function () {
    $exception = new LogsnagClientException('Error', code: 42);

    expect($exception->getCode())->toBe(42);
});

it('accepts a previous exception', function () {
    $previous = new RuntimeException('Root cause');
    $exception = new LogsnagClientException('Wrapper', previous: $previous);

    expect($exception->getPrevious())->toBe($previous)
        ->and($exception->getPrevious()->getMessage())->toBe('Root cause');
});

it('extends the base Exception class', function () {
    $exception = new LogsnagClientException('test');

    expect($exception)->toBeInstanceOf(Exception::class);
});

it('defaults response to null', function () {
    $exception = new LogsnagClientException;

    expect($exception->response)->toBeNull()
        ->and($exception->getMessage())->toBe('');
});
