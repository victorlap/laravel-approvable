<?php

namespace Victorlap\Approvable\Tests\Models;

class PostCanBeApproved extends Post
{
    protected function currentUserCanApprove()
    {
        return true;
    }
}
