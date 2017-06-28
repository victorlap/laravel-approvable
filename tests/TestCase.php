<?php

namespace Victorlap\Approvable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Victorlap\Approvable\ApprovableServiceProvider;

class TestCase extends Orchestra
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'laravel_approvable',
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ]);
    }

    /**
     * Get Approvable package providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TestServiceProvider::class,
            ApprovableServiceProvider::class,
        ];
    }
}