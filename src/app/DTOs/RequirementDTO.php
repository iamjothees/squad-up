<?php

namespace App\DTOs;

use App\Enums\RequirementStatus;
use App\Interfaces\ReferenceableDTO;
use App\Models\Requirement;
use App\Models\User;
use App\Rules\User\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RequirementDTO extends FilamentResourceDTO implements ReferenceableDTO
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

    public ?string $referer_id;

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

    public function toCreateArray(): array{
        return collect($this->toArray())->only([
            'referal_code', 'title', 'description', 'service_id', 'owner_id', 'admin_id', 'completion_at', 'budget', 'status', 'project_id'
        ])->toArray();
    }

    public function toUpdateArray(): array{
        return collect($this->toCreateArray())->filter(fn ($value) => !is_null($value))->toArray();
    }

    public function getPointOwnerId(): int{
        return $this->referer_id ?? $this->toModel()->getPointOwnerId();
    }

    public function getReferenceableType(): string{
        return app($this->modelType)->getMorphClass();
    }

    protected function fill(array $data): void{
        $Validator = Validator::make($data, [
            'id' => ['nullable', 'int', 'exists:requirements,id'],
            'referal_code' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'service_id' => ['required', 'int', 'exists:services,id'],
            'owner_id' => ['required', 'int', 'exists:users,id'],
            'admin_id' => ['nullable', new TeamMember],
            'completion_at' => ['nullable', 'date'],
            'budget' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', Rule::enum(RequirementStatus::class)],
            'project_id' => ['nullable', 'exists:projects,id'],
            
            'referer_id' => ['nullable', 'int', 'prohibits:referal_partner_code', 'exists:users,id'],
            'referal_partner_code' => ['nullable', 'prohibits:referer_id', 'exists:users,referal_partner_code'],
        ],[
            'referer_id.prohibits' => 'You cannot set both referer_id and referal_partner_code',
            'referal_partner_code.prohibits' => 'You cannot set both referer_id and referal_partner_code',
            'referer_id.exists' => 'Referer does not exist',
            'referal_partner_code.exists' => 'Referal partner code does not exist',
        ]
    );

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
        $this->status = $data['status'] ?? RequirementStatus::PENDING;
        $this->project_id = $data['project_id'] ?? null;
        $data['referal_partner_code'] ??= null;
        $this->referer_id = $data['referer_id'] ?? User::where('referal_partner_code', $data['referal_partner_code'])->first()?->id;
    }
}
