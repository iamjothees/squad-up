<?php

namespace App\DTOs;

use App\Models\Project;
use App\Rules\Money;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ProjectDTO extends ModelDTO
{
    private Project $model;

    public ?int $id;
    public string $title;
    public ?string $description;
    public int $service_id;
    public int $admin_id;
    public ?string $start_at;
    public ?string $completion_at;
    public ?string $deliver_at;
    public int $committed_budget;
    public int $initial_payment;
    public int $priority_level = 1;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): self{
        //
    }
    public static function fromArray(array $data): self{
        $dto = new self();
        $dto->fill($data);
        return $dto;
    }

    public function toArray(): array{
        //
    }
    public function toCreateArray(): array{
        //
    }
    public function toModel(): Model{
        //
    }

    protected function fill(array $data): void{
        $Validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'service_id' => ['required', 'exists:services,id'],
            'admin_id' => ['required', 'exists:users,id'],
            'start_at' => ['nullable', 'date'],
            'completion_at' => ['nullable', 'date'],
            'deliver_at' => ['nullable', 'date'],
            'committed_budget' => ['required', new Money],
            'initial_payment' => ['required', new Money],
            'priority_level' => ['nullable', 'numeric', 'min:1', 'max:10'],

        ]);

        if ($Validator->fails()) {
            throw new \Exception($Validator->errors()->first());
        }
        $data = $Validator->validated();

        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->service_id = $data['service_id'];
        $this->admin_id = $data['admin_id'];
        $this->start_at = $data['start_at'];
        $this->completion_at = $data['completion_at'];
        $this->deliver_at = $data['deliver_at'];
        $this->committed_budget = $data['committed_budget'];
        $this->initial_payment = $data['initial_payment'];
        $this->priority_level = $data['priority_level'];
    }

}
