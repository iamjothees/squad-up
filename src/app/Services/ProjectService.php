<?php

namespace App\Services;

use App\DTOs\PointGenerationDTO;
use App\DTOs\ProjectDTO;
use App\Enums\Point\GenerationArea;
use App\Models\Project;

class ProjectService
{
    public function __construct( private PointService $pointService )
    {
        //
    }

    public function createProject(ProjectDTO $projectDTO): ProjectDTO{
        $project = Project::create($projectDTO->toCreateArray());

        // TODO: crediting points
        if ( $project->requirement?->reference ){
            $this->pointService->credit( pointGenerationDTO: PointGenerationDTO::fromModel($project->requirement->reference->pointGeneration) );
        }

        // TODO: notify owner
        return ProjectDTO::fromModel($project);
    }
}
