<?php

namespace App\Observers;

use App\Models\Requirement;
use App\Service\RequirementService;

class RequirementObserver
{
    public function created(Requirement $requirement): void{
        app(RequirementService::class)->createPoints(requirement: $requirement);
    }
 
    public function updated(Requirement $requirement): void
    {
        if ( $requirement->isDirty('referer_id') || $requirement->isDirty('expecting_budget') ){
            $requirement->referer_id
                ? app(RequirementService::class)->updatePoints(requirement: $requirement)
                : app(RequirementService::class)->destroyPoints(requirement: $requirement);
        }
    }
 
    public function deleted(Requirement $requirement): void
    {
        app(RequirementService::class)->destroyPoints(requirement: $requirement);
    }
 
    public function restored(Requirement $requirement): void
    {
        if ( $requirement->referer_id ) app(RequirementService::class)->updatePoints(requirement: $requirement);
    }
 
    public function forceDeleted(Requirement $requirement): void
    {
        //
    }
}
