<?php

namespace Dietercoopman\Smart;

use Dietercoopman\Smart\Commands\SmartCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SmartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('smart');
    }

}
