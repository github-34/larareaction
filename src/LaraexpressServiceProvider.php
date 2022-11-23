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
        $this->publishes([
            __DIR__.'/../config/laraexpress.php' => config_path('laraexpress.php')
        ]);
        //$this->mergeConfigFrom(__DIR__.'/../config/laraexpress.php', 'laraexpress');

        //database
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([ __DIR__.'/../database/seeders' => database_path('seeders') ], 'laraexpress seeds');
//        $this->publishes([ __DIR__ . '/../database/seeders/LaraexpressSeeder.php' => database_path('seeders/LaraexpressSeeder.php'), ], 'laraexpress-seeds');

        // routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // front end: language translations
        // $this->publishes([ __DIR__.'/../lang' => $this->app->langPath('vendor/courier') ]);

        // front end: views
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'insomnicles');
        // $this->publishes([ __DIR__.'/../resources/views' => resource_path('views/vendor/courier') ]);

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {

        // Register the service the package provides.
        $this->app->singleton('laraexpress', function ($app) {
            return new ExpressionService();
        });

        // $this->app->bind('calculator', function($app) {
        //     return new Calculator();
        // });
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
        // Publishing the configuration file.
        // $this->publishes([
        //     __DIR__.'/../config/laraexpress.php' => config_path('laraexpress.php'),
        // ], 'laraexpress.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/insomnicles'),
        ], 'laraexpress.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
