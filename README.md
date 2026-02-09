# Laravel LogSnag

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pgtruesdell/laravel-logsnag.svg?style=flat-square)](https://packagist.org/packages/pgtruesdell/laravel-logsnag)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/pgtruesdell/laravel-logsnag/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/pgtruesdell/laravel-logsnag/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/pgtruesdell/laravel-logsnag/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/pgtruesdell/laravel-logsnag/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pgtruesdell/laravel-logsnag.svg?style=flat-square)](https://packagist.org/packages/pgtruesdell/laravel-logsnag)

A full-featured Laravel integration for [LogSnag](https://logsnag.com)'s real-time event tracking API. Track events, monitor metrics, identify users, and pipe your Laravel logs straight to LogSnag.

## Requirements

- PHP 8.2+
- Laravel 11 or 12

## Installation

```bash
composer require pgtruesdell/laravel-logsnag
```

Publish the config file:

```bash
php artisan vendor:publish --tag="laravel-logsnag-config"
```

Add your LogSnag credentials to `.env`:

```dotenv
LOGSNAG_TOKEN=your-api-token
LOGSNAG_PROJECT=your-project-slug
```

## Configuration

The published config file (`config/logsnag.php`) contains:

```php
return [
    // Your LogSnag project slug
    'project' => env('LOGSNAG_PROJECT', 'my-laravel-app'),

    // Default channel for the Monolog driver
    'channel' => env('LOGSNAG_CHANNEL', 'app-events'),

    // Your LogSnag API token
    'token' => env('LOGSNAG_TOKEN', ''),

    // Icon mapping for the Monolog driver (keyed by Monolog level name)
    'icons' => [
        'Debug'     => 'ℹ️',
        'Info'      => 'ℹ️',
        'Notice'    => '📌',
        'Warning'   => '⚠️',
        'Error'     => '⚠️',
        'Critical'  => '🔥',
        'Alert'     => '🔔️',
        'Emergency' => '💀',
    ],
];
```

## Usage

The package provides three ways to interact with LogSnag: **helper functions**, the **Facade**, or by resolving the **`Logsnag` class** from the container. All three offer the same API.

### Log Events

Track events happening in your application.

```php
use PGT\Logsnag\Facades\Logsnag;

// Minimal
Logsnag::log(channel: 'waitlist', event: 'User Signed Up');

// With all options
Logsnag::log(
    channel: 'billing',
    event: 'Subscription Renewed',
    description: 'Pro plan renewed for another year.',
    icon: '💳',
    notify: true,
    tags: ['plan' => 'pro', 'cycle' => 'yearly'],
    parser: \PGT\Logsnag\Enums\Parser::Markdown,
    userId: 'user-123',
    timestamp: now()->subMinutes(5)->timestamp,
);
```

Or with the helper function:

```php
logsnag(
    channel: 'waitlist',
    event: 'User Signed Up',
    description: 'A new user joined the waitlist.',
    icon: '🎉',
);
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `channel` | `string` | *required* | Event channel/category |
| `event` | `string` | *required* | Event name |
| `description` | `?string` | `null` | Event description (supports Markdown when parser is set) |
| `icon` | `?string` | `null` | Emoji icon |
| `notify` | `bool` | `false` | Send push notification |
| `tags` | `?array` | `null` | Key-value tags for filtering |
| `parser` | `?Parser` | `null` | `Parser::Markdown` or `Parser::Text` |
| `userId` | `?string` | `null` | Associate event with a user |
| `timestamp` | `?int` | `null` | Unix timestamp (backdate events) |

### Insights

Create or set a metric value.

```php
use PGT\Logsnag\Facades\Logsnag;

Logsnag::insight(title: 'Total Users', value: 1250, icon: '👥');
Logsnag::insight(title: 'MRR', value: 14999.99, icon: '💰');
Logsnag::insight(title: 'Status', value: 'Operational');
```

Or with the helper:

```php
insight(title: 'Total Users', value: 1250, icon: '👥');
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | `string` | *required* | Metric name |
| `value` | `string\|int\|float` | *required* | Metric value |
| `icon` | `?string` | `null` | Emoji icon |

### Mutate Insights

Increment or decrement an existing metric without knowing its current value.

```php
use PGT\Logsnag\Facades\Logsnag;

// Increment
Logsnag::mutateInsight(title: 'API Calls', incrementBy: 1);

// Decrement
Logsnag::mutateInsight(title: 'Open Tickets', incrementBy: -1);

// Float values
Logsnag::mutateInsight(title: 'Revenue', incrementBy: 49.99, icon: '💰');
```

Or with the helper:

```php
mutate_insight(title: 'API Calls', incrementBy: 1);
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | `string` | *required* | Metric name |
| `incrementBy` | `int\|float` | *required* | Amount to increment (negative to decrement) |
| `icon` | `?string` | `null` | Emoji icon |

### Identify Users

Associate properties with a user for user-level analytics.

```php
use PGT\Logsnag\Facades\Logsnag;

Logsnag::identify(userId: 'user-123', properties: [
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'plan' => 'enterprise',
    'company' => 'Acme Inc.',
]);
```

Or with the helper:

```php
identify(userId: 'user-123', properties: [
    'name' => 'Jane Doe',
    'plan' => 'enterprise',
]);
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `userId` | `string` | *required* | Unique user identifier |
| `properties` | `array<string, mixed>` | *required* | Key-value user properties |

## Monolog Integration

Route your Laravel logs to LogSnag by adding a custom channel to `config/logging.php`:

```php
'channels' => [
    // ...

    'logsnag' => [
        'driver' => 'custom',
        'via' => \PGT\Logsnag\Logger\LogsnagLogger::class,
        'level' => 'error', // Minimum log level to send
    ],
],
```

Then use it like any other log channel:

```php
use Illuminate\Support\Facades\Log;

Log::channel('logsnag')->info('User logged in', ['user_id' => 123]);
Log::channel('logsnag')->error('Payment failed', ['order_id' => 456]);
```

You can also add it to your `stack` channel to send logs to LogSnag alongside your other drivers.

**Monolog driver behavior:**

- Uses the `logsnag.channel` config value as the LogSnag channel name
- Maps log levels to emoji icons via the `logsnag.icons` config
- Automatically enables notifications for `Error` level and above
- Appends log context as formatted JSON in the event description
- Respects the `level` setting to filter out lower-priority logs

## Error Handling

All API methods throw `PGT\Logsnag\Client\LogsnagClientException` on failure. The exception includes the HTTP response for debugging:

```php
use PGT\Logsnag\Client\LogsnagClientException;
use PGT\Logsnag\Facades\Logsnag;

try {
    Logsnag::log(channel: 'app', event: 'Something');
} catch (LogsnagClientException $e) {
    // $e->getMessage() contains the error details
    // $e->response contains the HTTP response (if available)
}
```

## API Reference

This package covers the full [LogSnag API](https://docs.logsnag.com):

| Endpoint | HTTP Method | Package Method |
|----------|-------------|----------------|
| `/v1/log` | `POST` | `Logsnag::log()` / `logsnag()` |
| `/v1/insight` | `POST` | `Logsnag::insight()` / `insight()` |
| `/v1/insight` | `PATCH` | `Logsnag::mutateInsight()` / `mutate_insight()` |
| `/v1/identify` | `POST` | `Logsnag::identify()` / `identify()` |

## Testing

```bash
composer test
```

## Code Quality

```bash
# Static analysis
composer analyse

# Code formatting
composer format
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
