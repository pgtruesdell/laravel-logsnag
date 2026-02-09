<?php

use PGT\Logsnag\Enums\Parser;

it('has a markdown case with correct value', function () {
    expect(Parser::Markdown->value)->toBe('markdown');
});

it('has a text case with correct value', function () {
    expect(Parser::Text->value)->toBe('text');
});

it('has exactly two cases', function () {
    expect(Parser::cases())->toHaveCount(2);
});
