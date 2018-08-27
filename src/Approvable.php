<?php

namespace Victorlap\Approvable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Approvable
{

    /** @var array */
    public $approveOf = array();

    /** @var array */
    public $dontApproveOf = array();

    /** @var bool */
    protected $withoutApproval = false;

    /**
     * Create the event listeners for the saving event
     * This lets us save approvals whenever a save is made, no matter the
     * http method
     */
    public static function bootApprovable(): void
    {
        static::saving(function ($model) {
            return $model->preSave();
        });
    }

    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Check if this model has pending changes,
     * If an attribute is provided, check if the attribute has pending changes.
     *
     * @param null $attribute
     * @return bool
     */
    public function isPendingApproval($attribute = null): bool
    {
        return $this->approvals()
            ->when($attribute !== null, function ($query) use ($attribute) {
                $query->where('key', $attribute);
            })
            ->where('approved', null)
            ->exists();
    }

    /**
     * List all the attributes, that currently have pending changes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPendingApprovalAttributes(): Collection
    {
        return $this->approvals()
            ->where('approved', null)
            ->groupBy('key')
            ->pluck('key');
    }

    /**
     * Disable the approval process for this model instance.
     *
     * @param bool $withoutApproval Deprecated, see withoApproval()
     *                              Will be removed in 2.0.0
     *
     * @return self
     */
    public function withoutApproval(bool $withoutApproval = true): self
    {
        $this->withoutApproval = $withoutApproval;

        return $this;
    }

    /**
     * Enable the approval process for this model instance
     *
     * @return self
     */
    public function withApproval(): self
    {
        $this->withoutApproval = false;

        return $this;
    }

    /**
     * Invoked before a model is saved. Return false to abort the operation.
     *
     * @return bool
     */
    protected function preSave(): bool
    {
        if ($this->withoutApproval) {
            return true;
        }

        if ($this->currentUserCanApprove()) {
            // If the user is able to approve edits, do nothing.
            return true;
        }

        if (!$this->exists) {
            // There is currently no way (implemented) to enable this for new models.
            return true;
        }

        $changes_to_record = $this->changedApprovableFields();

        $approvals = array();
        foreach ($changes_to_record as $key => $change) {
            $approvals[] = array(
                'approvable_type' => $this->getMorphClass(),
                'approvable_id' => $this->getKey(),
                'key' => $key,
                'value' => $change,
                'user_id' => $this->getSystemUserId(),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );
        }

        if (count($approvals) > 0) {
            $approval = new Approval();
            DB::table($approval->getTable())->insert($approvals);
        }

        return true;
    }

    /**
     * Get all of the changes that have been made, that are also supposed
     * to be approved.
     *
     * @return array fields with new data, that should be recorded
     */
    private function changedApprovableFields(): array
    {
        $dirty = $this->getDirty();
        $changes_to_record = array();

        foreach ($dirty as $key => $value) {
            if ($this->isApprovable($key)) {
                if (!isset($this->original[$key]) || $this->original[$key] != $this->attributes[$key]) {
                    $changes_to_record[$key] = $value;

                    // Reset changes that we want to approve
                    if (!isset($this->original[$key])) {
                        unset($this->attributes[$key]);
                    } else {
                        $this->attributes[$key] = $this->original[$key];
                    }
                }
            }
        }

        return $changes_to_record;
    }

    /**
     * Return whether an attribute of this model should be approvable.
     *
     * @param string $key
     * @return bool
     */
    private function isApprovable(string $key): bool
    {
        if (isset($this->approveOf) && in_array($key, $this->approveOf)) {
            return true;
        }
        if (isset($this->dontApproveOf) && in_array($key, $this->dontApproveOf)) {
            return false;
        }

        return empty($this->approveOf);
    }

    /**
     * Get the user id that should be stored as the requester for the approval.
     *
     * @return int|null
     */
    protected function getSystemUserId(): ?int
    {
        return Auth::id() ?? null;
    }

    /**
     * Check if the approval process needs to happen for the currently logged in user.
     *
     * @return bool
     */
    protected function currentUserCanApprove(): bool
    {
        return Auth::check() && Auth::user()->can('approve', $this) ?? false;
    }
}
