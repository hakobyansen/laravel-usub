<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Usub\Core\UsubServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'laravel_usub',
            '--path' => realpath(__DIR__ . '/../src/Migrations')
        ]);
    }

    protected function getEnvironmentSetUp( $app )
    {
        $app['config']->set('app.debug', env('APP_DEBUG', true));
        $app['config']->set('database.default', 'laravel_usub');
        $app['config']->set('database.connections.laravel_usub', [
            'username' => 'root',
            'password' => '',
            'host'     => 'localhost',
            'driver'   => 'mysql',
            'database' => 'laravel_usub',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders( $app )
    {
        return [ UsubServiceProvider::class ];
    }
}