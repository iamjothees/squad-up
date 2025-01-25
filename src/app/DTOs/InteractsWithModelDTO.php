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

        $model = $this->model ??= app($this->modelType)::find( $this->id );
        return $model;
    }

    public function toArray(): array{
        return collect(get_object_vars($this))->except(['model', 'modelType'])->toArray();
    }

    public function toCreateArray(): array{
        return collect($this->toArray())->except('id')->filter(fn ($value) => !is_null($value))->toArray();
    }

    public function toUpdateArray(): array{
        return collect($this->toArray())->except('id')->filter(fn ($value) => !is_null($value))->toArray();
    }

    // TODO: need fix
    public function refreshModel(): self{
        if(!$this->model){
            $this->toModel();
            return $this;
        }
        $this->model->refresh();
        return $this;
    }
}
