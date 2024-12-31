<?php

use Illuminate\Support\Collection;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $config = [
            1 => [
                ['least' => 3500, 'most' => 100000, 'percent' => 3],
                ['least' => 100000, 'most' => 500000, 'percent' => 4],
            ],
        ];

        $this->migrator->add('general.default_point_percent', 2);
        $this->migrator->add('general.points_config', $config);
    }
};
