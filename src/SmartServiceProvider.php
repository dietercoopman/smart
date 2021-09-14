<?php

namespace Dietercoopman\Smart;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SmartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('smart');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/smart-views'),
        ], 'smart-views');
    }
}
