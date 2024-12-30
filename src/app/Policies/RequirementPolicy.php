<?php

namespace App\Policies;

use App\Enum\RequirementStatus;
use App\Models\Requirement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequirementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Requirement $requirement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Requirement $requirement): bool
    {
        return $requirement->status === RequirementStatus::PENDING || $user->id === $requirement->admin_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Requirement $requirement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Requirement $requirement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Requirement $requirement): bool
    {
        return true;
    }

    public function acceptRequirement(User $user, Requirement $requirement): bool
    {
        return $requirement->status === RequirementStatus::PENDING;
    }

    public function rejectRequirement(User $user, Requirement $requirement): bool
    {
        return $this->acceptRequirement($user, $requirement);
    }

    public function createProject(User $user, Requirement $requirement): bool
    {
        return (!$requirement->project) && $user->id === $requirement->admin_id && in_array($requirement->status, [RequirementStatus::PENDING, RequirementStatus::IN_PROGRESS]);
    }
}
