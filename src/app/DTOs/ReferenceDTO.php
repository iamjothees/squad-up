<?php

namespace App\DTOs;

use App\Models\Reference;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReferenceDTO extends ModelDTO
{
    use InteractsWithModelDTO;

    private Reference $model;
    private string $modelType = Reference::class;

    public ?int $id;
    public string $referenceable_type;
    public int $referenceable_id;
    public int $referer_id;
    public int $participation_level;
    public ?array $calc_config;

    public function __construct()
    {
        //
    }

    protected function fill(array $data): void{
        $validator =Validator::make($data, [
            'id' => ['nullable', 'exists:references,id'],
            'referenceable_type' => ['required', 'string', 'max:255'],
            'referenceable_id' => ['required', 
                                    function ( $attribute, $value, $fail) use ($data) {
                                        $referenceable = app($data['referenceable_type'])::find($value);
                                        if ($referenceable === null) {
                                            $fail("The {$attribute} is not a valid referenceable.");
                                        }
                                    }                    
                                ],
            'referer_id' => ['required', 'exists:users,id'],
            'participation_level' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'calc_config' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->referenceable_type = $data['referenceable_type'];
        $this->referenceable_id = $data['referenceable_id'];
        $this->referer_id = $data['referer_id'];
        $this->participation_level = $data['participation_level'] ?? 1;
        $this->calc_config = $data['calc_config'] ?? null;
    }
}
