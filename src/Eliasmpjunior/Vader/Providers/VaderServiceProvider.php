<?php

namespace Eliasmpjunior\Vader\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use Eliasmpjunior\Vader\Console\Commands\VaderInfoCommand;
use Eliasmpjunior\Vader\Console\Commands\VaderIndexCommand;


class VaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    	//
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                VaderInfoCommand::class,
                VaderIndexCommand::class,
            ]);
        }




        /*Services

        /**
         * Set up publish
         *//*
        $this->publishes([
            str_replace('/src/Eliasmpjunior/Vader/Providers', '/config/config.php', __DIR__) => config_path('empj-caravela.php'),
        ]);

        /**
         * Set up migrations
         *//*
        $this->loadMigrationsFrom(str_replace('/src/Eliasmpjunior/Vader/Providers', '/database/migrations', __DIR__));

        /**
         * Set up connection
         *//*
        $caravelaConnection = config('database.connections.'.(is_null(config('empj-caravela.connection')) ? config('database.default') : config('empj-caravela.connection')));

        Config::set('database.connections.caravela', $caravelaConnection);
        */
    }
}
