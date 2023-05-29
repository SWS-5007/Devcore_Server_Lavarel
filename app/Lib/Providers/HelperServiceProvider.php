<?php

namespace App\Lib\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
class HelperServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::instance();
            $loader->alias('Helpers', \App\Lib\Helpers\Helpers::class);
        });

        foreach (glob(app_path() . '/Lib/Helpers/*.php') as $filename) {
            require_once($filename);
        }

        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }
}
