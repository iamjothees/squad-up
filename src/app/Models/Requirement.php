<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\RequirementStatus;
use App\Interfaces\Referenceable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model implements Referenceable
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'required_at' => 'datetime',
        'budget' => MoneyCast::class,
        'status' => RequirementStatus::class
    ];

    protected $appends = [ 'referer_id' ];

    public function scopeConvertedForReference(Builder $query): Builder{
        return $query->where('status', RequirementStatus::APPROVED);
    }

    public function scopePendingConversionForReference(Builder $query): Builder
    {
        return $query->whereIn('status', [RequirementStatus::PENDING, RequirementStatus::IN_PROGRESS]);
    }

    public function getRefererIdAttribute(): ?int{
        return $this->reference?->referer_id;
    }

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

    public function reference(): MorphOne{
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function getAmountforPointCalculation(): float{
        return $this->budget;
    }

}
