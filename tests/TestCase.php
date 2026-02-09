<?php

namespace PGT\Logsnag\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use PGT\Logsnag\LogsnagServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LogsnagServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('logsnag.project', 'test-project');
        $app['config']->set('logsnag.token', 'test-token');
        $app['config']->set('logsnag.channel', 'test-channel');
        $app['config']->set('logsnag.icons', [
            'Debug' => 'ℹ️',
            'Info' => 'ℹ️',
            'Notice' => '📌',
            'Warning' => '⚠️',
            'Error' => '⚠️',
            'Critical' => '🔥',
            'Alert' => '🔔️',
            'Emergency' => '💀',
        ]);
    }
}
