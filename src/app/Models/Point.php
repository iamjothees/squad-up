<?php

namespace App\Models;

use App\Enums\Point\GenerationArea;
use App\Enums\Point\Status;
use App\Observers\PointObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// #[ObservedBy(PointObserver::class)]
class Point extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => Status::class,
        'generation_area' => GenerationArea::class,
        'calc_config' => 'array',
    ];

    // Redeemable scope

    public function scopeExpecting($query){
        return $query->where('status', Status::EXPECTING);
    }

    public function scopeGenerationFromRequirement($query){
        return $query->where('generation_area', GenerationArea::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    // handle requirement with different referer
    public function requirement(){
        return $this->belongsTo(Requirement::class);
    }
}
