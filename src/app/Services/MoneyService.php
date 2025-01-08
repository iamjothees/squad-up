<?php

namespace App\Services;

class MoneyService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function paiseToInr(int $paise): float{
        return round($paise / 100,  2);
    }

    public function inrToPaise(float $inr): int{
        return floatval($inr) * 100;
    }
}
