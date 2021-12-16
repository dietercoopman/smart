<?php

namespace Dietercoopman\Smart;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class SmartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/smart.php' => config_path('smart.php'),
        ]);


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'smart');

        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('smart::components.smart-image', "smart-image");
        });

        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('smart::components.smart-download', "smart-download");
        });

        $filename_pattern = '[ \w\\.\\/\\-\\@\(\)]+';

        //this makes it possible to generate a dynamic route based on a config value
        $this->mergeConfigFrom(__DIR__ . '/../config/smart.php', 'smart');
        $config = $this->app->make('config');
        $route = '/' . $config['smart']['image']['path'] . '/{filename}';

        $this->app['router']->get($route, [
            'uses' => 'Dietercoopman\Smart\Factories\ImageTag@serve',
            'as' => 'images',
        ])->where(['imagTag' => $filename_pattern]);

        $downloadroute = '/' . $config['smart']['download']['path'] . '/{hash}/{filename}';

        $this->app['router']->get($downloadroute, [
            'uses' => 'Dietercoopman\Smart\Factories\ATag@download',
            'as' => 'downloads',
        ])->where(['downloadTag' => $filename_pattern]);

    }
}
