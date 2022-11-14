<?php

namespace RabbitDigital\Bugtify;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Monolog\Logger;

class BugtifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $source = __DIR__ . '/../config/bugtify.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => base_path('config/bugtify.php')], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('bugtify');
        }

        $this->mergeConfigFrom($source, 'bugtify');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bugtify.php', 'config');

        $this->app->singleton('bugtify', function () {
            $config = config('bugtify');

            return new Bugtify($config);
        });

        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('bugtify', function ($app) {
                $handler = new BugtifyLogger(
                    $app['bugtify']
                );

                return new Logger('bugtify', [$handler]);
            });
        }
    }
}
