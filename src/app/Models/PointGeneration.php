<?php

namespace App\Models;

use App\Enums\Point\GenerationArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointGeneration extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $casts = [
        'generation_area' => GenerationArea::class,
        'calc_config' => 'array',
    ];

    public function scopeCredited($query){
        return $query->whereNotNull('credited_at');
    }

    public function scopeNonCredited($query){
        return $query->whereNull('credited_at');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
}
