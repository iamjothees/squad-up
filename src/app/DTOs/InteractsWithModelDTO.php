<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithModelDTO
{
    public static function fromModel(Model $model): self{
        $self = self::fromArray($model->attributesToArray());
        $self->model = $model;
        return $self;
    }

    public static function fromArray(array $data): self{
        $dto = new self();
        $dto->fill($data);
        return $dto;
    }

    public function toModel(): ?Model{
        if(is_null($this->id)) return null;

        $model = $this->model ??= app($this->modelType)::make( [ ...$this->toCreateArray(), 'id' => $this->id ] );
        return $model;
    }

    public function toArray(): array{
        return collect($this)->toArray();
    }

    public function toCreateArray(): array{
        return collect($this->toArray())->except('id')->toArray();
    }

    // TODO: need fix
    public function refresh(): self{
        if(!$this->model){
            $this->toModel();
            return $this;
        }
        $this->model->refresh();
        return $this;
    }
}
