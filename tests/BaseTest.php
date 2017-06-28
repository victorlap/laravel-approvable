<?php

namespace Victorlap\Approvable\Tests;

use Victorlap\Approvable\Tests\Models\UserCanApprove;
use Victorlap\Approvable\Tests\Models\UserCannotApprove;

class BaseTests extends TestCase
{

    public function testApproverCanCreate() {
        $user = UserCanApprove::create(['name' => 'John Doe', 'email' => 'john@doe.com']);
        $this->assertTrue($user->exists);
    }

    public function testRegularCanCreate() {
        $user = UserCannotApprove::create(['name' => 'John Doe', 'email' => 'john2@doe.com']);
        $this->assertTrue($user->exists);
    }
}