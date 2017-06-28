<?php

namespace Victorlap\Approvable\Tests\Models;


class UserCannotApprove extends User
{
    protected function currentUserCanApprove()
    {
        return false;
    }
}