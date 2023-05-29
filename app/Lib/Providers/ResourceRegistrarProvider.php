<?php

namespace App\Lib\Providers;

use Illuminate\Support\ServiceProvider;

class ResourceRegistrarProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function ($app) {

            // *php7* anonymous class for brevity,
            // feel free to create ordinary `ResourceRegistrar` class instead
            return new class ($app['router']) extends \Illuminate\Routing\ResourceRegistrar
            {

                public function register($name, $controller, array $options = [])
                {
                    if (str_contains($name, '/')) {
                        return parent::register($name, $controller, $options);
                    }

                    // ---------------------------------
                    // this is the part that we override
                    $base = array_get($options, 'param', $this->getResourceWildcard(last(explode('.', $name))));
                    // ---------------------------------

                    $defaults = $this->resourceDefaults;

                    foreach ($this->getResourceMethods($defaults, $options) as $m) {
                        $this->{'addResource' . ucfirst($m)}($name, $base, $controller, $options);
                    }
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { }
}
