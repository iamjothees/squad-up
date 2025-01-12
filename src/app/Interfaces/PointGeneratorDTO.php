<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PointGeneratorDTO
{
    public function toModel(): ?Model;
    public function getPointsToGenerateInAmount(): float;
    public function getPointOwnerId(): int;
    public function getIdentificationReference(): string;
}
