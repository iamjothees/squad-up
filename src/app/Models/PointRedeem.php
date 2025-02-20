<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointRedeem extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeRedeemed($query){
        return $query->whereNotNull('redeemed_at');
    }
}
