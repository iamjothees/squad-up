<?php

namespace App;

use App\Rules\PointConfig as RulesPointConfig;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Validator;

class PointConfig
{
    public function __construct( private GeneralSettings $generalSettings )
    {
        Validator::make(['config_by_participation_level' => $this->generalSettings->points_config], [
            'config_by_participation_level' => [ 'required',  'array', new RulesPointConfig ],
        ]);
    }

    public function getPercent(float $amount, int $participationLevel){
        $collection = collect($this->generalSettings->points_config[$participationLevel] ?? [])->sortBy('least');
        return $collection
                    ->firstWhere(
                        fn ($item) =>  (($item['least'] ?? 0) <= $amount) && ($amount <= ($item['most'] ?? $amount))
                    )['percent'] 
                ??
                (
                    $collection
                        ->take(1)
                        ->firstWhere( fn ($item) => $item['least'] > $amount ) 
                        ? 0 : null
                )
                ??
                $collection
                    ->skip($collection->count() - 1)
                    ->firstWhere( fn ($item) => $item['most'] < $amount )['percent']
                ??
                $this->generalSettings->default_point_percent;
    }

}
