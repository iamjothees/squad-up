<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PointGeneratorDTO
{
    public function toModel(): ?Model;
    public function getPointsToGenerateInAmount(): int;
    public function getPointOwnerId(): int;
}
