<?php

namespace App\Enums;

enum EnquiryStatus: string
{
    case PENDING = 'pending';
    case RESPONDED = 'responded';
    case CANCELLED = 'cancelled';

    function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::RESPONDED => 'Responded',
            self::CANCELLED => 'Cancelled',
        };
    }
}
