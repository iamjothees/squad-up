<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointGeneration extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeCredited($query){
        return $query->whereNotNull('credited_at');
    }

    public function scopeNonCredited($query){
        return $query->whereNull('credited_at');
    }
}
