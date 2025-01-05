<?php

use Illuminate\Support\Collection;
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

        $this->migrator->add('general.points_config', $config);
        $this->migrator->add('general.default_point_percent', 2);
        $this->migrator->add('general.point_per_amount', $pointsPerAmount = 100);
        $this->migrator->add('general.least_redeemable_point', 50 * $pointsPerAmount);
        $this->migrator->add('general.points_redeem_interval', 50 * $pointsPerAmount);
        $this->migrator->add('general.signup_bonus_points', 50 * $pointsPerAmount);
    }
};
