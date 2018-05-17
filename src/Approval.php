<?php

namespace Victorlap\Approvable;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Approval
 * @package Victorlap\Approvable
 */
class Approval extends Eloquent
{
    public $table = 'approvals';

    public function approvable()
    {
        return $this->morphTo();
    }

    public function getFieldName()
    {
        return $this->key;
    }

    public function accept() {

    }

    public function reject() {

    }

    public function scopeOpen($query)
    {
        return $query->where('approved', null);
    }

    public function scopeRejected($query)
    {
        return $query->where('approved', false);
    }

    public function scopeAccepted($query)
    {
        return $query->where('approved', true);
    }
}
