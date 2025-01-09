<?php

namespace App\Policies;

use App\Enums\RequirementStatus;
use App\Models\Requirement;
use App\Models\User;
use Filament\Facades\Filament;
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
        if ($requirement->status !== RequirementStatus::PENDING) return false; // To be moved to visibility restrictions

        switch (Filament::getCurrentPanel()->getId()) {
            case 'user': if ($requirement->owner_id !== $user->id) return false; break;
            case 'admin': if ($requirement->admin_id !== $user->id) return false; break;
            default: if (($requirement->owner_id !== $user->id) && ($requirement->admin_id !== $user->id)) return false;
        }
        return true;
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
        if ($requirement->status !== RequirementStatus::PENDING) return false;

        if ($user->hasRole('admin')) return true; // TO be moved to global permission provider

        if (!$user->hasRole('team-member')) return false;

        if ($requirement->admin_id && ($requirement->admin_id !== $user->id)) return false;

        return false;
    }

    public function rejectRequirement(User $user, Requirement $requirement): bool
    {
        return $this->acceptRequirement($user, $requirement);
    }

    public function createProject(User $user, Requirement $requirement): bool
    {
        if ($requirement->project) return false;
        if ($user->hasRole('admin')) return true; // TO be moved to global permission provider
        return $user->id === $requirement->admin_id && in_array($requirement->status, [RequirementStatus::PENDING, RequirementStatus::IN_PROGRESS]);
    }
}
