<?php

namespace Victorlap\Approvable;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Approval
 * @package Victorlap\Approvable
 */
class Approval extends Eloquent
{
    /**
     * @var string
     */
    public $table = 'approvals';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function approvable()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->key;
    }
}
