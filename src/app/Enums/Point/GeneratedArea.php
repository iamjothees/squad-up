<?php

namespace App\Enums\Point;

use App\Models\Requirement;

enum GeneratedArea: string
{
    case REQUIREMENT = 'requirement';
    case SIGNUP = 'signup';

    public function generatorKey(): string|null{
        return match($this){
            self::REQUIREMENT => Requirement::class,
            default => null,
        };
    }
}
