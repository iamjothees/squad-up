<?php

namespace App\Observers;

use App\Models\Reference;
use App\Settings\GeneralSettings;

class ReferenceObserver
{

    public function __construct( private GeneralSettings $generalSettings )
    {
        //
    }

    /**
     * Handle the Reference "created" event.
     */
    public function creating(Reference $reference): void
    {
        $reference->calc_config = $this->generalSettings->points_config;
    }

    public function created(Reference $reference): void
    {
        //
    }

    /**
     * Handle the Reference "updated" event.
     */
    public function updated(Reference $reference): void
    {
        //
    }

    /**
     * Handle the Reference "deleted" event.
     */
    public function deleted(Reference $reference): void
    {
        //
    }

    /**
     * Handle the Reference "restored" event.
     */
    public function restored(Reference $reference): void
    {
        //
    }

    /**
     * Handle the Reference "force deleted" event.
     */
    public function forceDeleted(Reference $reference): void
    {
        //
    }
}
