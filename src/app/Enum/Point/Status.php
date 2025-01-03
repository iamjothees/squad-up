<?php

namespace App\Enum\Point;

enum Status: string
{
    case EXPECTING = 'expecting';
    case CREDITED = 'credited';
}
