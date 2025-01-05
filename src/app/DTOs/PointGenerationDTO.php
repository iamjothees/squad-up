<?php

namespace App\DTOs;

use App\Enums\Point\GeneratedArea;
use App\Models\PointGeneration;
use App\Rules\MorphId;
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
    public GeneratedArea $generated_area;
    public ?string $generator_type;
    public ?int $generator_id;
    public ?Carbon $credited_at;

    public function __construct()
    {
        //
    }

    public function fill( array $data ): void{
        $validator = Validator::make($data, [
            'id' => ['nullable', 'exists:point_generations,id'],
            'points' => ['required', 'numeric', 'min:0'],
            'generated_area' => ['required', Rule::enum(GeneratedArea::class)],
            'generator_type' => ['nullable', 'string', 'max:255', Rule::in([ $data['generated_area']->generatorKey() ])],
            'generator_id' => ['nullable', new MorphId],
            'credited_at' => ['nullable', 'date', 'before_or_equal:now'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->points = $data['points'];
        $this->generated_area = $data['generated_area'];
        $this->generator_type = $data['generator_type'] ?? null;
        $this->generator_id = $data['generator_id'] ?? null;
        $this->credited_at = Carbon::make($data['credited_at'] ?? null);
    }
}
