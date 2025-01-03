<?php

namespace App\Models;

use App\Enum\Point\GeneratedArea;
use App\Enum\Point\Status;
use App\Observers\PointObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(PointObserver::class)]
class Point extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => Status::class,
        'generated_area' => GeneratedArea::class,
        'calc_config' => 'array',
    ];

    // Redeemable scope

    public function scopeExpecting($query){
        return $query->where('status', Status::EXPECTING);
    }

    public function scopeGeneratedFromRequirement($query){
        return $query->where('generated_area', GeneratedArea::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    // handle requirement with different referer
    public function requirement(){
        return $this->belongsTo(Requirement::class);
    }
}
