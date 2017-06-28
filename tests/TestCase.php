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

        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // set up database configuration
        $app['config']->set('database.default', 'testing');
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