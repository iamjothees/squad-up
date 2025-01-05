<?php

namespace App;

use App\Rules\PointConfig as RulesPointConfig;
use Illuminate\Support\Facades\Validator;

class PointConfig
{
    public function __construct( private array $config, )
    {
        Validator::make(['config_by_participation_level' => $config], [
            'config_by_participation_level' => [ 'required',  'array', new RulesPointConfig ],
        ]);
    }

    public function getPercent(float $amount, int $participationLevel){
        $collection = collect($this->config[$participationLevel] ?? [])->sortBy('least');
        return $collection
                    ->firstWhere(
                        fn ($item) =>  (($item['least'] ?? 0) <= $amount) && ($amount <= ($item['most'] ?? $amount))
                    )['percent'] 
                ?? 
                $collection
                    ->skip($collection->count() - 1)
                    ->firstWhere( fn ($item) => $item['most'] < $amount )['percent']
                ?? 0;
    }

}
