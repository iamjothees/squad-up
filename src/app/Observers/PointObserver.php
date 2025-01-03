<?php

namespace App\Observers;

use App\Enums\Point\GeneratedArea;
use App\Models\Point;

class PointObserver
{


    public function creating(Point $point): void{
        $point->generated_area = $point->requirement_id 
                                    ? GeneratedArea::REQUIREMENT 
                                    : GeneratedArea::SIGNUP;
    }

    public function created(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "updated" event.
     */
    public function updated(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "deleted" event.
     */
    public function deleted(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "restored" event.
     */
    public function restored(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "force deleted" event.
     */
    public function forceDeleted(Point $point): void
    {
        //
    }
}
