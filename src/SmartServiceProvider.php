<?php

namespace Dietercoopman\Smart;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SmartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('smart');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'smart');

        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('smart::components.smart-image', "smart-image");
        });

    }
}
