<?php

namespace Victorlap\Approvable\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Victorlap\Approvable\ApprovableServiceProvider;
use Victorlap\Approvable\Approval;
use Victorlap\Approvable\Tests\Models\Post;

class TestCase extends Orchestra
{
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setupDatabase();

        Approval::unguard();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setupDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        include_once __DIR__ . '/../migrations/create_approvals_table.php.stub';
        (new \CreateApprovalsTable())->up();
    }

    /**
     * Get Approvable package providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ApprovableServiceProvider::class,
        ];
    }

    protected function returnPostInstance($model = Post::class)
    {
        return $model::create([
            'title' => 'Cool Post',
        ]);
    }

    protected function createApproval(array $options = [])
    {
        $data = array_merge([
            'approvable_type' => Post::class,
            'approvable_id' => 1,
            'key' => 'title',
        ], $options);

        return Approval::create($data);
    }
}
