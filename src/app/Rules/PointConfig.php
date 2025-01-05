<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class PointConfig implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $keys = array_keys($value);

        if (array_unique($keys) !== $keys) {
            $fail("The {$attribute} must have unique participation levels.");
        }

        $validator = Validator::make($value, [
            '*.least' => ['required', 'numeric', 'min:0'],
            '*.most' => ['required', 'numeric', 'min:0', 'gte:least'],
            '*.percent' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            $fail($validator->errors()->first());
        }

        $ranges = [];
        foreach ($value as $participation_level => $config) {
            $ranges[$participation_level] = [
                'least' => $config['least'],
                'most' => $config['most'],
            ];

            // Check for overlapping ranges
            foreach ($ranges as $range_participation_level => $range) {
                if ( !( $config['most'] < $range['least'] || $config['least'] > $range['most'] ) ) {
                    $fail("The range for participation level {$participation_level} overlaps with participation level {$range_participation_level}.");
                }
            }
        }
    }
}
