<?php

namespace Tests;

use Env\Env;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Usub\Core\UsubServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
	use DatabaseMigrations;

	protected function setUp(): void
	{
		parent::setUp();

		$this->loadMigrationsFrom([
			'--database' => 'laravel_usub',
			'--path' => realpath(__DIR__ . '/../src/Migrations')
		]);
	}

	protected function getEnvironmentSetUp($app)
	{
		$envFile = dirname( __DIR__, 1 ) . '/.env';
		$env = Env::getInstance( $envFile );

		$app['config']->set('app.debug', env('APP_DEBUG', true));
		$app['config']->set('database.default', 'laravel_usub');
		$app['config']->set('database.connections.laravel_usub', [
			'username' => $env->get('DB_USERNAME'),
			'password' => $env->get('DB_PASSWORD'),
			'host' => $env->get('DB_HOST'),
			'port' => $env->get('DB_PORT'),
			'driver' => $env->get('DB_CONNECTION'),
			'database' => $env->get('DB_DATABASE'),
			'prefix' => $env->get('DB_PREFIX')
		]);
	}

	protected function getPackageProviders($app)
	{
		return [UsubServiceProvider::class];
	}
}
