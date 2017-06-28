<?php

namespace Victorlap\Approvable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Victorlap\Approvable\Approvable;

class User extends Model
{
    use Approvable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $fillable = ['name', 'email'];
}
