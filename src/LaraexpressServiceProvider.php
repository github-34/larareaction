<?php

namespace Insomnicles\Laraexpress;

use Illuminate\Support\ServiceProvider;

class LaraexpressServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // config
        $this->publishes([__DIR__.'/../config/laraexpress.php' => config_path('laraexpress.php')]);
        //$this->mergeConfigFrom(__DIR__.'/../config/laraexpress.php', 'laraexpress');

        //database
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([__DIR__.'/../database/seeders/LaraexpressSeeder.php' => database_path('seeders/LaraexpressSeeder.php')]);

        // routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Publish Translation Files
        // $this->publishes([ __DIR__.'/../lang' => $this->app->langPath('vendor/courier') ]);
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Publish views
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'insomnicles');
        // $this->publishes([ __DIR__.'/../resources/views' => resource_path('views/vendor/courier') ]);
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // For Facade
        $this->app->bind('Express', function ($app) {
            return new ExpressionService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laraexpress'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
    }
}
