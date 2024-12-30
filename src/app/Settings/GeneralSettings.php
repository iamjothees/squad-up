<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $points_config;
    
    public float $default_point_percent;

    public static function group(): string
    {
        return 'general';
    }
}