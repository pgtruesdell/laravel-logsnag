<?php

namespace PGT\Logsnag;

use PGT\Logsnag\Client\LogsnagClient;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LogsnagServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-logsnag')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $client = new LogsnagClient(
            token: config('logsnag.token'),
        );

        $this->app->instance(Logsnag::class, new Logsnag($client));
        $this->app->instance(LogsnagClient::class, $client);
    }
}
