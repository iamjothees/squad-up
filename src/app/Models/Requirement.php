<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\RequirementStatus;
use App\Interfaces\PointGeneratorUsingConfig;
use App\Observers\RequirementObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([RequirementObserver::class])]
class Requirement extends Model implements PointGeneratorUsingConfig
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'required_at' => 'datetime',
        'expecting_budget' => MoneyCast::class,
        'status' => RequirementStatus::class
    ];

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function admin(){
        return $this->belongsTo(User::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function reference(){
        return $this->belongsTo(Reference::class);
    }

    public function pointGeneration(){
        return $this->morphOne(PointGeneration::class, 'generator');
    }

    public function getAmountforPointCalculation(): float{
        return $this->expecting_budget;
    }

}
