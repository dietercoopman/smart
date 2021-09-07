<?php

namespace Dietercoopman\Smart;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Dietercoopman\Smart\Commands\SmartCommand;

class SmartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('skeleton')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_skeleton_table')
            ->hasCommand(SmartCommand::class);
    }
}
