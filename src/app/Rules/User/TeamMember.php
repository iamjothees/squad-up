<?php

namespace App\Rules\User;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TeamMember implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!User::find($value)?->hasRole('team-member')) {
            $fail("The {$attribute} is not a valid team member.");
        }
    }
}
