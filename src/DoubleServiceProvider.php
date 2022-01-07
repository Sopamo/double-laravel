<?php

namespace Sopamo\Double;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DoubleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'double');
    }

    public function boot()
    {
        $this->registerRoutes();


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('double.php'),
            ], 'config');
        }
    }

    public function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'../../routes/api.php');
        });
    }

    public function routeConfiguration()
    {
        return [
            'prefix' => config('double.api_prefix'),
        ];
    }
}
