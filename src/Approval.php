<?php

namespace Victorlap\Approvable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Approval extends Eloquent
{
    public $table = 'approvals';

    protected $casts = ['approved' => 'bool'];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFieldName(): string
    {
        return $this->key;
    }

    public function accept(): void
    {
        $approvable  = $this->approvable;
        $approvable->withoutApproval();
        $approvable->{$this->getFieldName()} = $this->value;
        $approvable->save();
        $approvable->withoutApproval(false);

        $this->approved = true;
        $this->save();
    }

    public function reject(): void
    {
        $this->approved = false;
        $this->save();
    }

    public function scopeOpen($query): Builder
    {
        return $query->where('approved', null);
    }

    public function scopeRejected($query): Builder
    {
        return $query->where('approved', false);
    }

    public function scopeAccepted($query): Builder
    {
        return $query->where('approved', true);
    }

    public function scopeOfClass($query, $class): Builder
    {
        return $query->where('approvable_type', $class);
    }
}
