<?php

namespace GustavoLima\JsonSQS;

use Illuminate\Support\Facades\Queue;
use GustavoLima\JsonSQS\Sqs\Connector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;

/**
 * Class CustomQueueServiceProvider
 * @package App\Providers
 */
class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/json-sqs.php' => config_path('json-sqs.php')
        ]);

        Queue::after(function (JobProcessed $event) {
            $event->job->delete();
        });
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
         $this->app->booted(function () {
            $this->app['queue']->extend('json-sqs', function () {
                return new Connector();
            });
        });
    }
}