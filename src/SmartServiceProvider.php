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
        $package->name('smart')
            ->hasConfigFile();;

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'smart');

        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('smart::components.smart-image', "smart-image");
        });

        $filename_pattern = '[ \w\\.\\/\\-\\@\(\)]+';

        //this makes it possible to generate a dynamic route based on a config value
        $this->mergeConfigFrom(__DIR__ . '/../config/smart.php', 'smart');
        $config = $this->app->make('config');
        $route  = '/' . $config['smart']['image']['path'] . '/{filename}';

        $this->app['router']->get($route, [
            'uses' => 'Dietercoopman\Smart\Factories\ImageTag@serve',
            'as'   => 'images',
        ])->where(['imagTag' => $filename_pattern]);
    }
}
