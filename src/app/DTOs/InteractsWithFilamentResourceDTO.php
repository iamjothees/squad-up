<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithFilamentResourceDTO
{
    use InteractsWithModelDTO;
    
    public static function fromFilamentData(array $data): self{
        $dto = new self();
        $dto->fill($data);
        return $dto;
    }
}
