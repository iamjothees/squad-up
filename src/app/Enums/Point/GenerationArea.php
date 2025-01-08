<?php

namespace App\Enums\Point;

use App\Models\Reference;
use App\Models\Requirement;
use App\Models\User;

enum GenerationArea: string
{
    case SIGNUP = 'signup';
    case REFERENCE = 'reference';

    public function generatorKey(): string|null{
        return match($this){
            self::SIGNUP => app(User::class)->getMorphClass(),
            self::REFERENCE => app(Reference::class)->getMorphClass(),
            default => null,
        };
    }

    public function getPointsToGenerateInAmount(): float{
        return match($this){
            default => 0
        };
    }
}
