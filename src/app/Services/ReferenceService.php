<?php

namespace App\Services;

use App\DTOs\PointGenerationDTO;
use App\DTOs\ReferenceDTO;
use App\Enums\Point\GenerationArea;
use App\Interfaces\PointGeneratorDTO;
use App\Models\Point;
use App\Models\Reference;

class ReferenceService
{
    public function __construct( private PointService $pointService )
    {
        //
    }

    public function createReference(ReferenceDTO $referenceDTO, bool $hasPoints = true) : ReferenceDTO
    {
        $referenceDTO = ReferenceDTO::fromModel(    Reference::create( $referenceDTO->toCreateArray() ) );

        if (!$hasPoints) return $referenceDTO;

        $this->pointService->generate( generationArea: GenerationArea::REFERENCE, pointGeneratorDTO: $referenceDTO );
        
        return $referenceDTO;
    }

    public function destroyReference(ReferenceDTO $referenceDTO): void{
        $reference = $referenceDTO->toModel();
        $this->pointService->destroy( pointGenerationDTO: PointGenerationDTO::fromModel($reference->pointGeneration) );
        $reference->delete();
    }
}
