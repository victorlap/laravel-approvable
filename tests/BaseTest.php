<?php

namespace Victorlap\Approvable\Tests;

use Illuminate\Support\Facades\DB;
use Victorlap\Approvable\Tests\Models\UserCanApprove;
use Victorlap\Approvable\Tests\Models\UserCannotApprove;

class BaseTests extends TestCase
{

    public function testApproverCanCreate() {
        $user = UserCanApprove::create(['name' => 'John Doe', 'email' => 'john@doe.com']);
        $this->assertTrue($user->exists);
    }

    public function testRegularCanCreate() {
        $user = UserCannotApprove::create(['name' => 'Jane Doe', 'email' => 'jane@doe.com']);
        $this->assertTrue($user->exists);
    }

    public function testApproverCanEdit() {
        $user = UserCanApprove::find(['email' => 'john@doe.com']);

        $user->update(['name' => 'Doe John'])->fresh();

        $this->assertEquals('Doe Jonh', $user->name);
    }

    public function testRegularCannotEdit() {
        $user = UserCannotApprove::find(['email' => 'jane@doe.com']);

        $this->assertEquals(0, DB::table('approvals')->count());

        $user->update(['name' => 'Doe Jane'])->fresh();

        $this->assertEquals('Jane Doe', $user->name);
        $this->assertEquals(1, DB::table('approvals')->count());
    }
}