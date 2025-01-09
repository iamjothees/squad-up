<?php

namespace App\Models;

use App\Interfaces\PointGeneratorUsingConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model implements PointGeneratorUsingConfig
{
    use HasFactory;

    public $timestamps = false;

    public function referenceable(){
        return $this->morphTo();
    }

    public function convertedReferenceable(){
        return $this->referenceable()->convertedForReference();
    }

    public function pendingConversionReferenceable(){
        return $this->referenceable()->pendingConversionForReference();
    }

    public function referer(){
        return $this->belongsTo(User::class, 'referer_id');
    }

    public function pointGeneration(){
        return $this->morphOne(PointGeneration::class, 'generator');
    }

    public function getAmountforPointCalculation(): float{
        return $this->referenceable->getAmountForPointCalculation();
    }
}
