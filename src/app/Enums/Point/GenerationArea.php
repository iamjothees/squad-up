<?php

namespace App\Enums\Point;

use App\Models\Requirement;

enum GenerationArea: string
{
    case REQUIREMENT = 'requirement';
    case SIGNUP = 'signup';

    public function generatorKey(): string|null{
        return match($this){
            self::REQUIREMENT => Requirement::class,
            default => null,
        };
    }

    public function getPointsToGenerateInAmount(): float{
        return match($this){
            default => 0
        };
    }
}
