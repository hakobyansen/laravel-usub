<?php

namespace Usub\Core;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\UsubSignIn;

class UsubServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish configuration and RBRequest files
        $this->publishes([
            __DIR__.'/../Configs/config.php' => config_path( 'usub.php' ),
            __DIR__ . '/../Migrations/2019_01_23_201042_create_usub_tokens_table.php' => database_path('migrations/2019_01_23_201042_create_usub_tokens_table.php'),
            __DIR__ . '/../Http/Middleware/UsubSignIn.php' => app_path('Http/Middleware/UsubSignIn.php'),
            __DIR__ . '/../Commands/ClearUsubTokens.php' => app_path('Console/Commands/ClearUsubTokens.php'),
        ], 'laravel-usub');

        // Register middleware
        if( $this->app->runningUnitTests() )
        {
            $this->app['router']->aliasMiddleware( 'usub_sign_in', UsubSignIn::class );
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../Configs/config.php', 'usub');
    }
}
