<?php

namespace Usub\Core;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\UsubSignIn;

class UsubServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// Publish files
		$this->publishes([
			__DIR__ . '/../Configs/config.php' => config_path('usub.php'),
			__DIR__ . '/../Http/Middleware/UsubSignIn.php' => app_path('Http/Middleware/UsubSignIn.php'),
			__DIR__ . '/../Commands/ClearUsubTokens.php' => app_path('Console/Commands/ClearUsubTokens.php'),
		], 'laravel-usub');

		// Register middleware
		if ($this->app->runningUnitTests()) {
			$this->app['router']->aliasMiddleware('usub_sign_in', UsubSignIn::class);
		}

		// Load routes
		$this->loadRoutesFrom(__DIR__ . '/../routes.php');

		// Load migrations
		$this->loadMigrationsFrom(__DIR__ . '/../Migrations');
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../Configs/config.php', 'usub');
	}
}
