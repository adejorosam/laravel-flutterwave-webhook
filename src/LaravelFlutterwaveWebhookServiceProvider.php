<?php

namespace Adejorosam\LaravelFlutterwaveWebhook;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelFlutterwaveWebhookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-flutterwave-webhook.php'),
            ], 'config');
        }

            Route::macro('flutterwaveWebhooks', function ($url) {
                return Route::post($url, '\Adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveWebhookController');
            });
        }
        

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-flutterwave-webhook');

    }
}
