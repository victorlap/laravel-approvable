<?php

namespace Victorlap\Approvable\Tests;

use Victorlap\Approvable\Tests\Models\User;
use Victorlap\Approvable\Tests\Models\UserCanApprove;
use Victorlap\Approvable\Tests\Models\UserCannotApprove;


class BaseTests extends TestCase
{

    private function returnUserInstance($model = User::class)
    {
        $instance = new $model([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
        ]);
        $instance::boot();

        return $instance;
    }

    public function testApproverCanCreate() {
        $user = $this->returnUserInstance(UserCanApprove::class);

        $user->save();

        $this->assertTrue($user->exists);
    }

    public function testRegularCanCreate() {
        $user = $this->returnUserInstance(UserCannotApprove::class);

        $user->save();

        $this->assertTrue($user->exists);
    }

    public function testApproverCanEdit() {
        $user = $this->returnUserInstance(UserCanApprove::class);
        $user->save();

        $user->name = 'Doe John';
        $user->save();

        $this->assertEquals('Doe John',  $user->fresh()->name);
    }

    public function testRegularCannotEdit() {
        $user = $this->returnUserInstance(UserCannotApprove::class);
        $user->save();

        $user->name = 'Doe John';
        $user->save();

        $this->assertEquals('John Doe',  $user->fresh()->name);
    }
}