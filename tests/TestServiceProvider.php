<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 12-2-17
 * Time: 20:53
 */

namespace Victorlap\Approvable\Tests;


class TestServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations/migrations'));
        $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
    }
}