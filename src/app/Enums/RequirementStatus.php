<?php

namespace App\Enums;

enum RequirementStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string{
        return match($this){
            self::PENDING => 'Queued',
            self::IN_PROGRESS => 'Active',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
}
