<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $config = [
            1 => [
                ['least' => 3500, 'most' => 99999, 'percent' => 3],
                ['least' => 100000, 'most' => 499999, 'percent' => 4],
            ],
        ];

        $this->migrator->add('points.points_config', $config);
        $this->migrator->add('points.default_point_percent', 2);
        $this->migrator->add('points.point_per_amount', $pointsPerAmount = 100);
        $this->migrator->add('points.least_redeemable_point', 50 * $pointsPerAmount);
        $this->migrator->add('points.points_redeem_interval', 50 * $pointsPerAmount);
        $this->migrator->add('points.signup_bonus_points', 50 * $pointsPerAmount);
    }
};
