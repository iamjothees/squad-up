<?php

namespace App\Enum;

enum RequirementStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
