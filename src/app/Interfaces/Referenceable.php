<?php

namespace App\Interfaces;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface Referenceable
{
    public function getAmountForPointCalculation(): float;

    public function scopeConvertedForReference(Builder $query): Builder;
    public function scopePendingConversionForReference(Builder $query): Builder;
}
