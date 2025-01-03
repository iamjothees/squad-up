<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\Models\Project;

class ProjectService
{
    public function __construct()
    {
        //
    }

    public function createProjects(ProjectDTO $projectDTO): ProjectDTO{
        $project = Project::create($projectDTO->toCreateArray());

        // TODO: crediting points
        // TODO: notify owner
        return ProjectDTO::fromModel($project);
    }
}
