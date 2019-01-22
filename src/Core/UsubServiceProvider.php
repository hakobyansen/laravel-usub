<?php

namespace Usub\Core;

use Illuminate\Support\ServiceProvider;
use RB\Commands\ClearUsubTokens;

class UsubServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish configuration and RBRequest files
        $this->publishes([
            __DIR__.'/../Configs/config.php' => config_path( 'usub.php' ),
            __DIR__ . '/../Migrations/2019_01_23_201042_create_usub_tokens_table.php' => database_path('migrations/2019_01_23_201042_create_usub_tokens_table.php'),
        ], 'laravel-usub');

        // Register commands
        $this->commands([
            ClearUsubTokens::class
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../Configs/config.php', 'usub');
    }
}
