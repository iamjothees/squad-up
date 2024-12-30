<?php

namespace App\Casts;

use App\Services\MoneyService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed{
        return round(floatval($value) / 100,  2);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed{
        return round(floatval($value) * 100);
    }
}
