<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $points_config;
    
    public float $default_point_percent;

    public int $point_per_amount;

    public int $least_redeemable_point;

    public int $points_redeem_interval;

    public ?int $signup_bonus_points = null;

    public static function group(): string
    {
        return 'general';
    }
}