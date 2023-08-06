# Logsnag's realtime monitoring + your Laravel project = 😎

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pgtruesdell/laravel-logsnag.svg?style=flat-square)](https://packagist.org/packages/pgtruesdell/laravel-logsnag)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/pgtruesdell/laravel-logsnag/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/pgtruesdell/laravel-logsnag/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/pgtruesdell/laravel-logsnag/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/pgtruesdell/laravel-logsnag/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pgtruesdell/laravel-logsnag.svg?style=flat-square)](https://packagist.org/packages/pgtruesdell/laravel-logsnag)

This package allows you to easily integrate [Logsnag](https://logsnag.com) into your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require pgtruesdell/laravel-logsnag
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-logsnag-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * The project name.
     */
    'project' => env('LOGSNAG_PROJECT', 'my-laravel-app'),

    /**
     * The default channel name for the monolog driver.
     */
    'channel' => env('LOGSNAG_CHANNEL', 'app-events'),

    /**
     * Your logsnag API token.
     */
    'token' => env('LOGSNAG_TOKEN', ''),

    /**
     * A mapping of icons for logging.
     */
    'icons' => [
        'DEBUG'     => 'ℹ️',
        'INFO'      => 'ℹ️',
        'NOTICE'    => '📌',
        'WARNING'   => '⚠️',
        'ERROR'     => '⚠️',
        'CRITICAL'  => '🔥',
        'ALERT'     => '🔔️',
        'EMERGENCY' => '💀',
    ],
];

```

## Usage

Using the built-in helper function:

```php
logsnag(
    'app',
    'Artist Created',
    'Artist id 589 created by user 2',
    '🎨',
);
```

Using the facade:

```php
use PGT\Logsnag\Facades\Logsnag;

Logsnag::log(
    channel: 'app',
    event: 'Artist Created',
    description: 'Artist id 589 created by user 2',
    icon: '🎨',
    notify: false
);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Paul Grant Truesdell, II](https://github.com/pgtruesdell)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
