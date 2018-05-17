<?php

namespace Victorlap\Approvable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Victorlap\Approvable\Approvable;

class Post extends Model
{
    use Approvable;

    protected $table = 'posts';

    protected $fillable = ['title'];
}
