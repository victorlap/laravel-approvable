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

    public function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
        $this->unguardModels();
    }

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
            $table->string('body')->nullable();
            $table->timestamps();
        });

        include_once __DIR__ . '/../migrations/create_approvals_table.php.stub';
        (new \CreateApprovalsTable())->up();
    }

    protected function unguardModels()
    {
        Approval::unguard();
    }

    protected function getPackageProviders($app)
    {
        return [
            ApprovableServiceProvider::class,
        ];
    }

    protected function createPost($model = Post::class)
    {
        return $model::create([
            'title' => 'Cool Post',
        ]);
    }

    protected function createApproval(array $options = [])
    {
        return Approval::create(array_merge([
            'approvable_type' => Post::class,
            'approvable_id' => 1,
            'key' => 'title',
        ], $options));
    }
}
