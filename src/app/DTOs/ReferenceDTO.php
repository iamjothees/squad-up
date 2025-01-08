<?php

namespace App\DTOs;

use App\Interfaces\PointGeneratorDTO;
use App\Interfaces\Referenceable;
use App\Models\Reference;
use App\Rules\MorphId;
use App\Services\PointService;
use Illuminate\Support\Facades\Validator;

class ReferenceDTO extends ModelDTO implements PointGeneratorDTO
{
    use InteractsWithModelDTO;

    private Reference $model;
    private string $modelType = Reference::class;

    public ?int $id;
    public string $referenceable_type;
    public int $referenceable_id;
    public int $referer_id;
    public int $participation_level;

    public function __construct()
    {
        //
    }

    public function getPointsToGenerateInAmount(): float{
        return app(PointService::class)->calcPointsInAmount( amount: $this->toModel()->getAmountforPointCalculation() );
    }

    public function getPointOwnerId(): int{
        return $this->referer_id;
    }

    protected function fill(array $data): void{
        $validator =Validator::make($data, [
            'id' => ['nullable', 'exists:references,id'],
            'referenceable' => [
                'required_without:referenceable_id,referenceable_type', 
                'prohibits:referenceable_id,referenceable_type',
                fn ($attribute, $value, $fail) => $value instanceof Referenceable ?: $fail("The {$attribute} must be an instance of Referenceable.")
            ],
            'referenceable_type' => [
                'required_if:referenceable,null', 
                'prohibits:referenceable', 
                'string', 'max:255'
            ],
            'referenceable_id' => ['required_if:referenceable,null', 'prohibits:referenceable',  new MorphId ],
            'referer_id' => ['required', 'exists:users,id'],
            'participation_level' => ['nullable', 'numeric', 'min:1', 'max:10'],
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->referenceable_type = $data['referenceable_type'] ?? $data['referenceable']->getMorphClass();
        $this->referenceable_id = $data['referenceable_id'] ?? $data['referenceable']->getKey();
        $this->referer_id = $data['referer_id'];
        $this->participation_level = $data['participation_level'] ?? 1;
    }
}
