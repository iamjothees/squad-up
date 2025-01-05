<?php

namespace App\DTOs;

use App\Enums\RequirementStatus;
use App\Interfaces\PointGeneratorDTO;
use App\Models\Requirement;
use App\Rules\User\TeamMember;
use App\Services\PointService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RequirementDTO extends FilamentResourceDTO implements PointGeneratorDTO
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
    public ?Carbon $completion_at;
    public float $budget;
    public RequirementStatus $status;
    public ?int $project_id;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): self{
        $data = $model->attributesToArray();
        $data['status'] = $model->status;
        $self = self::fromArray($data);
        $self->model = $model;
        return $self;
    }

    public function getPointsToGenerateInAmount(): int{
        $pointService = app(PointService::class);
        return  $pointService->calcPointsInAmount( amount: $this->toModel()->getAmountforPointCalculation() );
    }

    public function getPointOwnerId(): int{
        return $this->toModel()->getPointOwnerId();
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
        $this->completion_at = Carbon::make($data['completion_at'] ?? null);
        $this->budget = $data['budget'];
        $this->status = $data['status'];
        $this->project_id = $data['project_id'] ?? null;
    }
}
