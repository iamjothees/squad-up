<?php

namespace App\Providers;

use App\Models\PointGeneration;
use App\Models\PointRedeem;
use App\Models\Project;
use App\Models\Reference;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Relation::enforceMorphMap([
            'user' => User::class,
            'service' => Service::class,
            'project' => Project::class,
            'requirement' => Requirement::class,
            'reference' => Reference::class,
            'point_generation' => PointGeneration::class,
            'point_redeem' => PointRedeem::class
        ]);

        Number::useCurrency('INR');
    }
}
