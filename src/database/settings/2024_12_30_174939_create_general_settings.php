<?php

use Illuminate\Support\Collection;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $config = [
            1 => [
                ['least' => 0, 'most' => 15000, 'percent' => 5],
                ['least' => 15001, 'most' => 100000, 'percent' => 3],
                ['least' => 100001, 'most' => null, 'percent' => 1],
            ],

            2 => [
                ['least' => 0, 'most' => 15000, 'percent' => 7],
                ['least' => 15001, 'most' => 100000, 'percent' => 5],
                ['least' => 100001, 'most' => null, 'percent' => 3],
            ],

            3 => [
                ['least' => 0, 'most' => 15000, 'percent' => 10],
                ['least' => 15001, 'most' => 100000, 'percent' => 7],
                ['least' => 100001, 'most' => null, 'percent' => 5],
            ]
        ];

        $this->migrator->add('general.default_point_percent', 5);
        $this->migrator->add('general.points_config', $config);
    }
};
