<?php

namespace Ttmn\Tencentmini;

use Illuminate\Support\ServiceProvider;

class TencentminiServiceProvider extends ServiceProvider
{

    protected $defer = true;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadViewsFrom(__DIR__ . '/views', 'Tencentmini');

        $this->publishes([

//            __DIR__.'/views' => base_path('resources/views/vendor/tencentmini'),

            __DIR__.'/config/tencentmini.php' => config_path('tencentmini.php'),

        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tencentmini', function ($app) {

            return new Tencentmini($app['session'], $app['config']);

        });
    }

    public function provides()

    {

        return ['tencentmini'];

    }
}
