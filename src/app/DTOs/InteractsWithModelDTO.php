<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithModelDTO
{
    public static function fromModel(Model $model): self{
        return self::fromArray($model->toArray());
    }

    public static function fromArray(array $data): self{
        $dto = new self();
        $dto->fill($data);
        return $dto;
    }

    public function toModel(): Model{
        return $this->model ??= app($this->modelType)::find($this->id);
    }

    public function toArray(): array{
        return collect($this)->toArray();
    }

    public function toCreateArray(): array{
        return collect($this->toArray())->except('id')->toArray();
    }
}
