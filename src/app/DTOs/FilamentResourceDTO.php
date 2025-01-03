<?php

namespace App\DTOs;

abstract class FilamentResourceDTO extends ModelDTO
{
    abstract public static function fromFilamentData(array $data): self;
}
