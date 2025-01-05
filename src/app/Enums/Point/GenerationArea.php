<?php

namespace App\Enums\Point;

use App\Models\Requirement;
use App\Models\User;

enum GenerationArea: string
{
    case REQUIREMENT = 'requirement';
    case SIGNUP = 'signup';

    public function generatorKey(): string|null{
        return match($this){
            self::REQUIREMENT => app(Requirement::class)->getMorphClass(),
            self::SIGNUP => app(User::class)->getMorphClass(),
            default => null,
        };
    }

    public function getPointsToGenerateInAmount(): float{
        return match($this){
            default => 0
        };
    }
}
