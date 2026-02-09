# Changelog

All notable changes to `laravel-logsnag` will be documented in this file.

## 2.0 - 2026-02-09

Full LogSnag API implementation with new endpoints, DTOs, and comprehensive tests

Rewrite the package to fully cover all four LogSnag API endpoints
(Log, Insight, Mutate Insight, Identify) with proper data objects,
centralized error handling, and a working Monolog integration.

API Coverage:

- POST /v1/log: Add missing parameters (tags, parser, user_id, timestamp)
- POST /v1/insight: Accept string, int, and float values (was string-only)
- PATCH /v1/insight: New mutateInsight() method for incrementing/decrementing metrics
- POST /v1/identify: New identify() method for associating properties with users

Architecture:

- Move DTOs from Client/Requests/ to Data/ namespace (LogData, InsightData,
  MutateInsightData, IdentifyData) with explicit array building that preserves
  falsy values like notify:false (fixes array_filter bug in old DTOs)
- Add Parser enum (Markdown, Text) for log description formatting
- Inject project into LogsnagClient constructor instead of reading config
  in every method call
- Use singleton closures in ServiceProvider for deferred config resolution
- Centralize duplicated response-checking into private handleResponse()
- Add optional Response property to LogsnagClientException for debugging

Monolog Integration:

- LogsnagLoggerHandler resolves Logsnag from container (was broken static call)
- Map log levels to icons via config using Monolog Level->name (TitleCase keys)
- Auto-notify on Error level and above
- Append log context as formatted JSON in event description
- Respect configurable minimum log level

Helpers & Facade:

- Update logsnag() and insight() helpers with new signatures
- Add mutate_insight() and identify() global helpers
- Add @method PHPDoc annotations to Facade for IDE autocompletion
- Add return type to getFacadeAccessor()

Tests (111 tests, 149 assertions):

- Unit tests for all 4 DTOs, Parser enum, LogsnagClientException
- Feature tests for all 4 API endpoints with Http::fake()
- LogsnagClient tests verifying HTTP methods, auth headers, endpoints
- Facade forwarding tests for all methods
- Monolog integration tests covering level icons, auto-notify, context
  serialization, configurable channel/level, missing icon fallback
- Service provider tests for singleton bindings, deferred resolution,
  config injection
- Helper function tests for all 4 helpers
- Error handling edge cases (400 without validation, response on exception)
- Falsy-but-valid value tests (timestamp:0, empty tags, zero increment)

Breaking Changes:

- insight() value parameter type widened from string to string|int|float
- Config icon keys changed from uppercase (DEBUG) to TitleCase (Debug)
- ServiceProvider uses singleton closures instead of eager instance binding
- Old Client/Requests/ namespace removed

Deleted:

- src/Client/Requests/LogsnagLog.php
- src/Client/Requests/LogsnagInsight.php
- tests/ExampleTest.php

## 1.3 - 2025-01-23

- Fix PHP 8.4 deprecations

## 1.2 - 2024-04-02

- Added Laravel 11 support
- Removed PHP 8.1 support.

## 1.1 - 2023-08-06

- Logsnag Insights support
- insight helper function

## 1.0.1 - 2023-08-06

- Remove ray reference
- Remove nonexistent directory from phpstan

## 1.0 - 2023-08-06

Initial release.
