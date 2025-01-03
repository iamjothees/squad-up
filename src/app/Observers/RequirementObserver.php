<?php

namespace App\Observers;

use App\Enums\RequirementStatus;
use App\Models\Requirement;
use App\Services\RequirementService;

class RequirementObserver
{
    public function creating(Requirement $requirement): void
    {
        $requirement->referal_code = str()->uuid();
    }

    public function created(Requirement $requirement): void
    {
        // generating referal code
        $requirement->referal_code = app(RequirementService::class)->generateReferalCode(requirement: $requirement);
        $requirement->saveQuietly();

        // crediting points
        if ( $requirement->referer_id ) app(RequirementService::class)->createPoints(requirement: $requirement);
    }

    public function updated(Requirement $requirement): void
    {
        // updating points
        if ( $requirement->isDirty('referer_id') || $requirement->isDirty('expecting_budget') ){
            $requirement->referer_id
                ? app(RequirementService::class)->updateOrCreatePoints(requirement: $requirement)
                : app(RequirementService::class)->destroyPoints(requirement: $requirement);
        }

        // updating status
        if ( $requirement->isDirty('project_id') ){
            $requirement->status = ($requirement->project_id) ? RequirementStatus::APPROVED : RequirementStatus::PENDING;
            $requirement->saveQuietly();
        }
    }

    public function deleted(Requirement $requirement): void
    {
        app(RequirementService::class)->destroyPoints(requirement: $requirement);
    }

    public function restored(Requirement $requirement): void
    {
        if ( $requirement->referer_id ) app(RequirementService::class)->updateOrCreatePoints(requirement: $requirement);
    }

    public function forceDeleted(Requirement $requirement): void
    {
        //
    }
}
