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
        $this->app->singleton(LogsnagClient::class, function () {
            return new LogsnagClient(
                token: config('logsnag.token'),
                project: config('logsnag.project'),
            );
        });

        $this->app->singleton(Logsnag::class, function ($app) {
            return new Logsnag($app->make(LogsnagClient::class));
        });
    }
}
