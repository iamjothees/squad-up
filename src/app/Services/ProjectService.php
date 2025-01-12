<?php

namespace App\Services;

use App\DTOs\PointGenerationDTO;
use App\DTOs\ProjectDTO;
use App\DTOs\RequirementDTO;
use App\Enums\Point\GenerationArea;
use App\Models\Project;
use App\Notifications\RequirementAdvancedToProject;

class ProjectService
{
    public function __construct( private PointService $pointService )
    {
        //
    }

    public function createProject(ProjectDTO $projectDTO, ?RequirementDTO $requirementDTO = null): ProjectDTO{
        $project = Project::create($projectDTO->toCreateArray());


        if ( !$requirementDTO ) return ProjectDTO::fromModel($project);

        $requirement = $requirementDTO->toModel();
        $requirement->project_id = $project->id;
        $requirement->save();

        // crediting points
        if ( $project->requirement?->reference )
            $this->pointService->credit(pointGenerationDTO: PointGenerationDTO::fromModel($project->requirement->reference->pointGeneration));

        // Notify owner
        $requirement->owner->notify(new RequirementAdvancedToProject(requirementDTO: $requirementDTO));
        
        return ProjectDTO::fromModel($project);
    }
}
