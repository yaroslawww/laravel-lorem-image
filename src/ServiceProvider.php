<?php

namespace Limsum;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/lorem-image.php' => config_path('lorem-image.php'),
            ], 'config');


            $this->commands([
                //
            ]);
        }

        $this->app->bind('limsum', function ($app) {
            return new LipsumManager($app);
        });
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lorem-image.php', 'lorem-image');
    }
}
