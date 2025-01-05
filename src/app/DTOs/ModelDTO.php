<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

abstract class ModelDTO
{
    abstract public static function fromArray(array $data): self;
    abstract public static function fromModel(Model $model): self;

    abstract public function toArray(): array;
    abstract public function toCreateArray(): array;
    abstract public function toModel(): ?Model;

    abstract public function refresh(): self;

    abstract protected function fill(array $data): void;
}
