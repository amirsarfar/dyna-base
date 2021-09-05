<?php

namespace Amirsarfar\DynaBase;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Web64\Colors\Facades\Colors;

// use Web64\Colors\Facades\Colors;
class DynaBaseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'amirsarfar');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'amirsarfar');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadDynamicRoutesFromDatabase();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dyna-base.php', 'dyna-base');

        // Register the service the package provides.
        $this->app->singleton('dyna-base', function ($app) {
            return new DynaBase;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['dyna-base'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/dyna-base.php' => config_path('dyna-base.php'),
        ], 'dyna-base.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/amirsarfar'),
        ], 'dyna-base.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/amirsarfar'),
        ], 'dyna-base.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/amirsarfar'),
        ], 'dyna-base.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    public function loadDynamicRoutesFromDatabase()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $db_name = DB::connection()->getConfig('database');
            $db_host = DB::connection()->getConfig('host');
            $db_port = DB::connection()->getConfig('port');
            if(Env::get('APP_ENV') != 'testing'){
                Colors::error("DYNA ERROR: Dynamic routes are not loaded!");
                Colors::error("DYNA ERROR: Could not connect to the database ($db_name at $db_host:$db_port)!  Please check your configuration!" );
            }
            return;
        }
        
        if(Schema::hasTable('types')){
            $this->loadRoutesFrom(__DIR__.'/../routes/dyna.php');
        }else{
            if(Env::get('APP_ENV') != 'testing'){
                Colors::error("DYNA ERROR: Dynamic routes are not loaded!");
                Colors::error("DYNA ERROR: Types table not found! Please run migrations! ");
            }
        }
    }
}
