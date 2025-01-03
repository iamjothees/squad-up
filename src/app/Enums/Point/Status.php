<?php

namespace App\Enums\Point;

enum Status: string
{
    case EXPECTING = 'expecting';
    case CREDITED = 'credited';
}
