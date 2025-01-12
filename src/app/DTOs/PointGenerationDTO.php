<?php

namespace App\DTOs;

use App\Enums\Point\GenerationArea;
use App\Interfaces\PointGeneratorDTO;
use App\Models\PointGeneration;
use App\Models\Reference;
use App\Models\Requirement;
use App\Models\User;
use App\Rules\MorphId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PointGenerationDTO extends ModelDTO
{
    use InteractsWithModelDTO;

    private PointGeneration $model;
    private string $modelType = PointGeneration::class;
    
    public ?int $id;
    public int $points;
    public int $owner_id;
    public GenerationArea $generation_area;
    public ?string $generator_type;
    public ?int $generator_id;
    public ?array $calc_config;
    public ?Carbon $credited_at;

    public ?PointGeneratorDTO $pointGeneratorDTO;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): self{
        $data = $model->attributesToArray();
        $data['generation_area'] = $model->generation_area;
        $self = self::fromArray($data);
        $self->model = $model;
        return $self;
    }

    public function fill( array $data ): void{
        $validator = Validator::make($data, [
            'id' => ['nullable', 'exists:point_generations,id'],
            'points' => ['required', 'numeric', 'min:0'],
            'owner_id' => ['required', 'exists:users,id'],
            'generation_area' => ['required', Rule::enum(GenerationArea::class)],
            'generator_type' => ['nullable', 'string', 'max:255', Rule::in([ $data['generation_area']->generatorKey() ])],
            'generator_id' => ['nullable', new MorphId ], // TODO: validate generator's getOwnerId matches owner_id
            'calc_config' => ['nullable', 'array'],
            'credited_at' => ['nullable', 'date', 'before_or_equal:now'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->points = $data['points'];
        $this->owner_id = $data['owner_id'];
        $this->generation_area = $data['generation_area'];
        $this->generator_type = $data['generator_type'] ?? null;
        $this->generator_id = $data['generator_id'] ?? null;
        $this->calc_config = $data['calc_config'] ?? null;
        $this->credited_at = Carbon::make($data['credited_at'] ?? null);

        $this->pointGeneratorDTO = match($this->generation_area){
            GenerationArea::SIGNUP => UserDTO::fromModel(User::find($this->generator_id)),
            GenerationArea::REFERENCE => ReferenceDTO::fromModel(Reference::find($this->generator_id)),
            default => null
        };
    }
}
