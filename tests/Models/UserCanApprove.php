<?php

namespace Victorlap\Approvable\Tests\Models;


class UserCanApprove extends User
{
    protected function currentUserCanApprove()
    {
        return true;
    }
}