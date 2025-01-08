<?php

namespace App\Observers;

use App\DTOs\PointGenerationDTO;
use App\DTOs\RequirementDTO;
use App\Enums\Point\GenerationArea;
use App\Enums\RequirementStatus;
use App\Models\Requirement;
use App\Services\PointService;
use App\Services\RequirementService;

class RequirementObserver
{

    public function __construct(protected PointService $pointService)
    {
        //
    }

    public function creating(Requirement $requirement): void
    {
        // 
    }

    public function created(Requirement $requirement): void
    {
        //
    }

    public function updated(Requirement $requirement): void
    {
        //
    }

    public function deleted(Requirement $requirement): void
    {
        //
    }

    public function restored(Requirement $requirement): void
    {
        //
    }

    public function forceDeleted(Requirement $requirement): void
    {
        //
    }
}
