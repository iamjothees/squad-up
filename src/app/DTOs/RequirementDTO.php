<?php

namespace App\DTOs;

use App\Enums\RequirementStatus;
use App\Models\Requirement;
use App\Rules\User\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RequirementDTO extends FilamentResourceDTO
{
    use InteractsWithFilamentResourceDTO;

    private Requirement $model;
    private string $modelType = Requirement::class;

    public ?int $id;
    public ?string $referal_code;
    public string $title;
    public ?string $description;
    public int $service_id;
    public int $owner_id;
    public ?int $admin_id;
    public ?string $completion_at;
    public float $budget;
    public RequirementStatus $status;
    public ?int $project_id;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): self{
        $data = $model->toArray();
        $data['status'] = RequirementStatus::from($data['status']);
        return self::fromArray($data);
    }

    protected function fill(array $data): void{
        $Validator = Validator::make($data, [
            'id' => ['nullable', 'exists:requirements,id'],
            'referal_code' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'service_id' => ['required', 'exists:services,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'admin_id' => ['nullable', new TeamMember],
            'completion_at' => ['nullable', 'date'],
            'budget' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::enum(RequirementStatus::class)],
            'project_id' => ['nullable', 'exists:projects,id'],
        ]);

        if ($Validator->fails()) {
            throw new \Exception($Validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->referal_code = $data['referal_code'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->service_id = $data['service_id'];
        $this->owner_id = $data['owner_id'];
        $this->admin_id = $data['admin_id'] ?? null;
        $this->completion_at = $data['completion_at'] ?? null;
        $this->budget = $data['budget'];
        $this->status = $data['status'];
        $this->project_id = $data['project_id'] ?? null;
    }
}
