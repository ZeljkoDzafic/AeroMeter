<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
        if(\Schema::hasTable('configs')) {
            $configs = \App\Config::get();

            foreach ($configs as $config) {
                \Config::set($config->key, $config->value);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
