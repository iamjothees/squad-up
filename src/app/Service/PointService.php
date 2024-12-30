<?php

namespace App\Service;

use App\Models\Requirement;
use App\Settings\GeneralSettings;
use Illuminate\Support\Number;

class PointService
{
    public function __construct()
    {
        //
    }

    public function createPoints(Requirement $requirement, int $participationLevel = 1): void{
        $requirement->point()->create([
            'owner_id' => $requirement->referer_id,
            'points' => $this->generatePoints(budget: $requirement->expecting_budget, participationLevel: $participationLevel),
            'participation_level' => $participationLevel,
            'calc_config' => app(GeneralSettings::class)->points_config
        ]);
    }

    public function updatePoints(Requirement $requirement, int $participationLevel = 1): void{
        if($requirement->point){
            $requirement->point->update([
                'owner_id' => $requirement->referer_id,
                'points' => $this->generatePoints( budget: $requirement->expecting_budget, participationLevel: $participationLevel, pointsConfig: $requirement->point->calc_config),
                'participation_level' => $participationLevel
            ]);

            return;
        }

        $this->createPoints(requirement: $requirement);
    }

    public function destroyPoints(Requirement $requirement): void{
        $requirement->point?->delete();
    }

    

    public function creditPoints(Requirement $requirement): void{
        $requirement->point()->update([
            'points' => $this->generatePoints($requirement->project->committed_budget, pointsConfig: $requirement->point->calc_config)
        ]);
    }


    // TODO: points config to be DTO
    private function generatePoints(float $budget, int $participationLevel = 1, ?array $pointsConfig = null): int{
        $pointsConfig ??= app(GeneralSettings::class)->points_config;
        // dd($budget, $participationLevel, $pointsConfig[$participationLevel]);
        $percent = collect($pointsConfig[$participationLevel] ?? [])
            ->sortBy('least')
            
            ->firstWhere(
                fn ($item) =>  ($item['least'] ?? 0 <= $budget) && ($budget <= $item['most'] ?? $budget) && dd($item, $budget)
            )['percent'];
    
        return $budget * $percent;
    }
}
