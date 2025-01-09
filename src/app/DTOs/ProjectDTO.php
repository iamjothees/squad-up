<?php

namespace App\DTOs;

use App\Models\Project;
use App\Rules\Money;
use App\Rules\User\TeamMember;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ProjectDTO extends ModelDTO
{
    use InteractsWithModelDTO;

    private Project $model;
    private string $modelType = Project::class;

    public ?int $id;
    public string $title;
    public ?string $description;
    public int $service_id;
    public int $owner_id;
    public int $admin_id;
    public ?Carbon $start_at;
    public ?Carbon $completion_at;
    public ?Carbon $deliver_at;
    public float $committed_budget;
    public float $initial_payment;
    public int $priority_level;

    public function __construct()
    {
        //
    }

    protected function fill(array $data): void{
        $Validator = Validator::make($data, [
            'id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'service_id' => ['required', 'exists:services,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'admin_id' => ['required', new TeamMember],
            'start_at' => ['nullable', 'date'],
            'completion_at' => ['nullable', 'date'],
            'deliver_at' => ['nullable', 'date'],
            'committed_budget' => ['required', new Money],
            'initial_payment' => ['required', new Money, 'lte:committed_budget'], // TODO: validate is above 50% of requirement
            'priority_level' => ['nullable', 'numeric', 'min:1', 'max:10'],

        ]);

        if ($Validator->fails()) {
            throw new \Exception($Validator->errors()->first());
        }
        $data = $Validator->validated();

        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->service_id = $data['service_id'];
        $this->owner_id = $data['owner_id'];
        $this->admin_id = $data['admin_id'];
        $this->start_at = Carbon::make($data['start_at'] ?? null);
        $this->completion_at = Carbon::make($data['completion_at'] ?? null);
        $this->deliver_at = Carbon::make($data['deliver_at'] ?? null);
        $this->committed_budget = $data['committed_budget'];
        $this->initial_payment = $data['initial_payment'];
        $this->priority_level = $data['priority_level'] ?? 1;
    }

}
