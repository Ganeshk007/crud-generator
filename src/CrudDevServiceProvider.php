<?php

namespace CrudDev\CrudDev;

use CrudDev\CrudDev\App\Console\Commands\GenerateModelWithCustomFile;
use Illuminate\Support\ServiceProvider;

class CrudDevServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'crud-dev');
         $this->loadViewsFrom(__DIR__.'/resources/stubs', 'crud-dev');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('crud-dev.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/resources/stubs' => resource_path('views/vendor/crud-dev'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/crud-dev'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/crud-dev'),
            ], 'lang');*/

            // Registering package commands.
             $this->commands([GenerateModelWithCustomFile::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'crud-dev');

        // Register the main class to use with the facade
        $this->app->singleton('crud-dev', function () {
            return new CrudDev;
        });
    }
}
